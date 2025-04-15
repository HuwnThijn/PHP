from flask import Flask, render_template, request, jsonify, url_for, send_from_directory
from flask_cors import CORS
from dotenv import load_dotenv
from openai import OpenAI
import json
import os
import numpy as np
import tensorflow as tf
from tensorflow.keras.models import load_model
from keras.preprocessing import image
from PIL import Image
import io
import sys
import logging
import traceback
import pymysql

# Cấu hình logging
logging.basicConfig(level=logging.INFO, 
                   format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
                   handlers=[logging.StreamHandler()])
logger = logging.getLogger('o2skin_ai')

# Thêm thư mục Agent vào đường dẫn
current_dir = os.path.dirname(os.path.abspath(__file__))
if current_dir not in sys.path:
    sys.path.append(current_dir)

try:
    import connection
    import prompt_MySQL
    logger.info("Đã nhập modules connection và prompt_MySQL thành công")
except ImportError as e:
    logger.error(f"Lỗi khi nhập modules: {str(e)}")

load_dotenv() 

app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": ["http://localhost:5173", "http://127.0.0.1:5173", "*"]}})

# Tìm đường dẫn cho các file cần thiết
def find_file(filename, search_paths=None):
    if search_paths is None:
        search_paths = [
            '.',  # Current directory
            current_dir,  # Script directory
            os.path.join(current_dir, 'skin_disease_model'),  # Model directory
            'D:\Code\O2_Skin_Nodejs\DermatologyClinic-NodeJs-React-\Agent\skin_disease_model'  # Original path from flaskapp.py
        ]
    
    for path in search_paths:
        file_path = os.path.join(path, filename)
        if os.path.exists(file_path):
            logger.info(f"Tìm thấy file {filename} tại {file_path}")
            return file_path
    
    logger.error(f"Không tìm thấy file {filename}")
    return None

# Kiểm tra kết nối MySQL
try:
    success, message = connection.connect_to_mysql()
    if success:
        logger.info("Kết nối MySQL thành công")
    else:
        logger.error(f"Không thể kết nối đến MySQL: {message}")
except Exception as e:
    logger.error(f"Lỗi khi kết nối MySQL: {str(e)}")

# Load các file cần thiết cho mô hình
class_names_path = find_file('class_names.json')
if class_names_path:
    with open(class_names_path, 'r', encoding='utf-8') as f:
        class_names = json.load(f)
        logger.info(f"Đã tải class_names.json với {len(class_names)} lớp")
else:
    class_names = []
    logger.warning("Không thể tải class_names.json, sử dụng list rỗng")

# When reading training history
history_path = find_file('training_history.json')
if history_path:
    with open(history_path, 'r', encoding='utf-8') as f:
        history_dict = json.load(f)
        logger.info("Đã tải training_history.json")
else:
    history_dict = {}
    logger.warning("Không thể tải training_history.json, sử dụng dict rỗng")

# Load mô hình
model = None
try:
    # Thử tải file .h5 trước (ưu tiên định dạng từ flaskapp.py)
    h5_path = find_file('skin_disease_model.h5')
    if h5_path:
        model = load_model(h5_path)
        logger.info("Đã tải model từ H5 format")
    else:
        # Thử tải Keras format
        keras_path = find_file('skin_disease_model.keras')
        if keras_path:
            model = load_model(keras_path)
            logger.info("Đã tải model từ Keras format")
        else:
            # Sử dụng TFSMLayer cho SavedModel format
            model_path = find_file('skin_disease_model')
            if model_path:
                try:
                    # Thử tải thông thường trước
                    model = load_model(model_path)
                    logger.info("Đã tải model từ SavedModel format")
                except Exception as model_error:
                    logger.warning(f"Không thể tải model thông thường: {str(model_error)}")
                    try:
                        # Nếu không được, sử dụng TFSMLayer
                        from tensorflow.keras.layers import TFSMLayer
                        model = TFSMLayer(model_path, call_endpoint='serving_default')
                        logger.info("Đã tải model từ SavedModel format thông qua TFSMLayer")
                    except Exception as tfsm_error:
                        logger.error(f"Không thể tải model qua TFSMLayer: {str(tfsm_error)}")
            else:
                logger.error("Không tìm thấy model ở bất kỳ định dạng nào")
except Exception as e:
    logger.error(f"Lỗi khi tải model: {str(e)}")
    logger.error(traceback.format_exc())

def preprocess_image(img):
    img = img.resize((192, 192))
    img_array = image.img_to_array(img)
    img_array = np.expand_dims(img_array, axis=0)
    img_array = img_array / 255.0
    return img_array

# Cấu hình OpenAI API
api_key = os.getenv('OPENAI_API_KEY')
if not api_key:
    logger.error("Không tìm thấy OPENAI_API_KEY trong biến môi trường")
    api_key = ""

try:
    client = OpenAI(
        api_key=api_key,
        base_url="https://api.openai.com/v1"
    )
    # Test kết nối
    response = client.chat.completions.create(
        model="gpt-4o-mini",
        messages=[{"role": "user", "content": "Test"}],
        max_tokens=5
    )
    logger.info("Kết nối OpenAI API thành công")
except Exception as e:
    logger.error(f"Lỗi khi khởi tạo OpenAI client: {str(e)}")
    client = None

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/chat_test')
def chat_test():
    return render_template('chat_test.html')

@app.route('/ask', methods=['POST'])
def ask():
    try:
        if client is None:
            return jsonify({"response": "Lỗi kết nối đến OpenAI API. Vui lòng thử lại sau."}), 500

        # Hỗ trợ cả form data và json
        user_message = request.form.get('message')
        if not user_message:
            user_message = request.json.get('message', '')
            
        if not user_message:
            return jsonify({"response": "Vui lòng nhập câu hỏi"}), 400

        # Log thông tin request
        logger.info(f"Nhận câu hỏi: {user_message}")
        
        # Sử dụng quy trình RAG mới từ prompt_MySQL
        try:
            result = prompt_MySQL.processQuery(user_message, client)
            logger.info(f"Kết quả từ processQuery: {result}")
            return jsonify(result)
        except Exception as e:
            logger.error(f"Lỗi khi xử lý câu hỏi qua processQuery: {str(e)}")
            logger.error(traceback.format_exc())
            return jsonify({
                "response": f"Xin lỗi, đã xảy ra lỗi khi xử lý câu hỏi của bạn: {str(e)}",
                "suggested_follow_up": [
                    "Bạn có thể thử hỏi với cách diễn đạt khác không?",
                    "Tôi muốn biết về các sản phẩm chăm sóc da",
                    "Tôi muốn biết về các dịch vụ của phòng khám"
                ],
                "confidence": 0.0
            }), 500
    
    except Exception as e:
        logger.error(f"Lỗi trong endpoint /ask: {str(e)}")
        logger.error(traceback.format_exc())
        return jsonify({"error": str(e)}), 500

@app.route('/classify', methods=['POST'])
def classify_image():
    try:
        if model is None:
            return jsonify({'error': 'Mô hình AI chưa được tải. Vui lòng liên hệ quản trị viên'}), 500

        if 'image' not in request.files:
            return jsonify({'error': 'Không tìm thấy ảnh để phân tích'}), 400

        file = request.files['image']
        if file.filename == '':
            return jsonify({'error': 'Không có ảnh nào được chọn'}), 400

        # Tạo thư mục temp_uploads nếu chưa tồn tại
        temp_folder = os.path.join(current_dir, 'temp_uploads')
        if not os.path.exists(temp_folder):
            os.makedirs(temp_folder, exist_ok=True)
            logger.info(f"Đã tạo thư mục lưu ảnh tạm: {temp_folder}")

        # Lưu ảnh tạm để debug nếu cần
        try:
            # Tạo tên file duy nhất dựa theo timestamp
            from datetime import datetime
            timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
            filename = f"{timestamp}_{file.filename}"
            temp_path = os.path.join(temp_folder, filename)
            file.save(temp_path)
            logger.info(f"Đã lưu ảnh tạm tại: {temp_path}")
            
            # Mở lại file để xử lý
            img = Image.open(temp_path)
        except Exception as save_error:
            logger.warning(f"Không thể lưu ảnh tạm: {str(save_error)}")
            # Nếu lưu tệp thất bại, vẫn xử lý từ bộ nhớ
            file.seek(0)  # Reset file pointer
            img = Image.open(io.BytesIO(file.read()))
        
        processed_img = preprocess_image(img)
        
        # Kiểm tra loại model và dự đoán theo cách phù hợp
        if hasattr(model, 'predict'):
            # Model Keras tiêu chuẩn
            predictions = model.predict(processed_img)
            predicted_class_index = np.argmax(predictions[0])
            confidence = float(predictions[0][predicted_class_index])
            
            top_indices = predictions[0].argsort()[-3:][::-1]
            top_predictions = [
                {
                    'class': class_names[i] if i < len(class_names) else f"Unknown_Class_{i}",
                    'confidence': float(predictions[0][i])
                }
                for i in top_indices if i < len(class_names)
            ]
        elif hasattr(model, '__call__'):  # TFSMLayer
            # TFSMLayer trả về dict hoặc tensor
            logger.info("Sử dụng TFSMLayer để dự đoán")
            predictions = model(processed_img)
            
            # Xử lý output từ TFSMLayer
            if isinstance(predictions, dict):
                logger.info(f"Model trả về dictionary với các khóa: {list(predictions.keys())}")
                # Lấy tensor đầu tiên 
                pred_array = list(predictions.values())[0].numpy()
            else:
                logger.info(f"Model trả về tensor với shape: {predictions.shape}")
                pred_array = predictions.numpy()
                
            # Định dạng dữ liệu dự đoán cho phù hợp
            if len(pred_array.shape) > 1:
                predictions_flat = pred_array[0]
            else:
                predictions_flat = pred_array
            
            logger.info(f"Shape của predictions_flat: {predictions_flat.shape}")
            
            predicted_class_index = np.argmax(predictions_flat)
            confidence = float(predictions_flat[predicted_class_index])
            
            logger.info(f"Predicted class index: {predicted_class_index}, confidence: {confidence}")
            
            top_indices = predictions_flat.argsort()[-3:][::-1]
            top_predictions = [
                {
                    'class': class_names[i] if i < len(class_names) else f"Unknown_Class_{i}",
                    'confidence': float(predictions_flat[i])
                }
                for i in top_indices if i < len(class_names)
            ]
        else:
            return jsonify({'error': 'Mô hình không hỗ trợ phương thức dự đoán'}), 500
            
        # Xác định tên lớp dự đoán
        if not class_names or predicted_class_index >= len(class_names):
            predicted_class = f"Unknown_Class_{predicted_class_index}"
            logger.warning(f"Không tìm thấy tên lớp cho index {predicted_class_index}")
        else:
            predicted_class = class_names[predicted_class_index]
            logger.info(f"Kết quả dự đoán: {predicted_class} với độ tin cậy {confidence:.4f}")
        
        # Đường dẫn tương đối để hiển thị trong web
        relative_path = os.path.join('temp_uploads', os.path.basename(temp_path)) if 'temp_path' in locals() else None
        
        return jsonify({
            'predicted_class': predicted_class,
            'confidence': confidence,
            'top_predictions': top_predictions,
            'image_path': relative_path
        })
    except Exception as e:
        logger.error(f"Lỗi khi phân loại ảnh: {str(e)}")
        logger.error(traceback.format_exc())
        return jsonify({'error': str(e)}), 500

# Thêm route để phục vụ các file tĩnh từ thư mục temp_uploads
@app.route('/temp_uploads/<filename>')
def serve_uploaded_image(filename):
    return send_from_directory(os.path.join(current_dir, 'temp_uploads'), filename)

# Thêm route hiển thị danh sách các ảnh đã phân tích gần đây (cho mục đích debug)
@app.route('/recent_uploads')
def recent_uploads():
    try:
        temp_folder = os.path.join(current_dir, 'temp_uploads')
        if not os.path.exists(temp_folder):
            return jsonify({'error': 'Thư mục tạm không tồn tại'}), 404
            
        # Liệt kê các tệp trong thư mục
        files = os.listdir(temp_folder)
        files.sort(reverse=True)  # Sắp xếp theo thứ tự giảm dần (mới nhất lên đầu)
        
        # Lấy 20 file gần nhất
        recent_files = files[:20]
        
        # Tạo danh sách đường dẫn đầy đủ
        result = []
        for file in recent_files:
            file_path = os.path.join('temp_uploads', file)
            result.append({
                'filename': file,
                'url': url_for('serve_uploaded_image', filename=file, _external=True),
                'created': file.split('_')[0] if '_' in file else 'unknown'
            })
            
        return jsonify(result)
    except Exception as e:
        logger.error(f"Lỗi khi liệt kê ảnh gần đây: {str(e)}")
        return jsonify({'error': str(e)}), 500

# Thêm route để phục vụ trang web hiển thị các ảnh gần đây
@app.route('/uploads_viewer')
def uploads_viewer():
    return """
    <!DOCTYPE html>
    <html>
    <head>
        <title>O2Skin AI - Recent Uploads</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .gallery { display: flex; flex-wrap: wrap; gap: 15px; }
            .image-item { border: 1px solid #ddd; padding: 10px; width: 220px; }
            .image-item img { width: 200px; height: 200px; object-fit: cover; }
            .timestamp { color: #666; font-size: 12px; }
            h1 { color: #4CAF50; }
        </style>
    </head>
    <body>
        <h1>O2Skin AI - Ảnh phân tích gần đây</h1>
        <div id="gallery" class="gallery">
            <p>Đang tải dữ liệu...</p>
        </div>
        
        <script>
            // Fetch recent uploads data
            fetch('/recent_uploads')
                .then(response => response.json())
                .then(data => {
                    const gallery = document.getElementById('gallery');
                    gallery.innerHTML = '';
                    
                    if (data.length === 0) {
                        gallery.innerHTML = '<p>Không có ảnh nào gần đây</p>';
                        return;
                    }
                    
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'image-item';
                        
                        const img = document.createElement('img');
                        img.src = item.url;
                        img.alt = item.filename;
                        
                        const name = document.createElement('p');
                        name.textContent = item.filename;
                        
                        const timestamp = document.createElement('p');
                        timestamp.className = 'timestamp';
                        timestamp.textContent = `Upload: ${item.created}`;
                        
                        div.appendChild(img);
                        div.appendChild(name);
                        div.appendChild(timestamp);
                        gallery.appendChild(div);
                    });
                })
                .catch(error => {
                    document.getElementById('gallery').innerHTML = 
                        `<p>Lỗi khi tải dữ liệu: ${error.message}</p>`;
                });
        </script>
    </body>
    </html>
    """

@app.route('/test-mysql-connection', methods=['GET'])
def test_mysql_connection():
    try:
        success, message = connection.connect_to_mysql()
        
        if success:
            # Test query to list all tables
            conn = pymysql.connect(
                host=os.getenv('DB_HOST', 'localhost'),
                user=os.getenv('DB_USERNAME', 'root'),
                password=os.getenv('DB_PASSWORD', ''),
                db=os.getenv('DB_DATABASE', 'beauty_clinic'),
                charset='utf8mb4',
                cursorclass=pymysql.cursors.DictCursor
            )
            
            with conn.cursor() as cursor:
                cursor.execute("SHOW TABLES")
                tables = cursor.fetchall()
            
            conn.close()
            
            return jsonify({
                "success": True, 
                "message": message,
                "tables": [list(table.values())[0] for table in tables]
            })
        else:
            return jsonify({"success": False, "message": message}), 500
    except Exception as e:
        logger.error(f"Error testing MySQL connection: {str(e)}")
        return jsonify({"success": False, "message": str(e)}), 500

if __name__ == '__main__':
    logger.info("Khởi động ứng dụng O2Skin AI Agent... (MySQL Only Mode)")
    app.run(host='0.0.0.0', port=5000, debug=True)
