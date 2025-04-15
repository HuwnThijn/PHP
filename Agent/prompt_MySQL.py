# PROMPT CHO HỆ THỐNG TRẢ LỜI TỰ ĐỘNG DỰA TRÊN MYSQL
# File này chứa các prompt và hướng dẫn để hệ thống AI trả lời câu hỏi từ database MySQL

import json
import pymysql
import os
import logging
import re
from openai import OpenAI
from typing import Dict, List, Any, Tuple, Optional, Union

# Cấu hình logging
logging.basicConfig(level=logging.INFO, 
                    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s')
logger = logging.getLogger('o2skin_ai')

# ===================================
# THÔNG TIN CƠ SỞ DỮ LIỆU
# ===================================

DB_SCHEMA = """
CẤU TRÚC CƠ SỞ DỮ LIỆU:
- users (id_user, id_role, id_rank, name, email, phone, password, gender, age, specialization, status)
- roles (id_role, name) - admin(1), doctor(2), pharmacist(3), customer(4)
- ranks (id_rank, name, min_points) - Bronze(1), Silver(2), Gold(3), Member(4)
- cosmetics (id_cosmetic, id_category, name, price, rating, isHidden)
- categories (id_category, name) - La Beauty(1), Fixderma(2), GAMMA(3), On: The Body(4), Laroche posay(5)
- services (id_service, name, description, price, duration, is_active)
- appointments (id_appointment, id_patient, id_doctor, id_service, guest_name, guest_email, guest_phone, appointment_time, status, notes)
- reviews (id_review, id_cosmetic, id_user, comment, rating)
- medical_records (id_medical_record, id_patient, id_doctor, diagnosis, notes)
- chat_messages (id, user_id, message, type, parent_id)
"""

# ===================================
# PROMPTS CHO HỆ THỐNG
# ===================================

SYSTEM_PROMPT = f"""
Bạn là trợ lý AI của phòng khám da liễu O2_Skin, hỗ trợ tra cứu thông tin dựa trên cơ sở dữ liệu MySQL. 
Nhiệm vụ của bạn là phân tích câu hỏi của khách hàng, tạo truy vấn SQL phù hợp, và định dạng kết quả thành câu trả lời thân thiện.

{DB_SCHEMA}

NGUYÊN TẮC TRẢ LỜI:
1. CHỈ trả lời dựa trên dữ liệu thực tế từ database
2. KHÔNG bịa đặt thông tin không có trong kết quả truy vấn
3. Nếu không có thông tin, nên đề xuất khách hàng liên hệ trực tiếp hoặc thử từ khóa khác
4. Phong cách trả lời lịch sự, chuyên nghiệp, thân thiện

Khi khách hàng hỏi về sản phẩm làm đẹp, truy vấn bảng 'cosmetics'. Khi hỏi về dịch vụ, truy vấn bảng 'services'.
"""

# Bước 1: Phân loại câu hỏi (Triage)
TRIAGE_PROMPT = """
Phân loại câu hỏi sau: "{user_question}"

Hãy xác định xem câu hỏi này có cần truy cập cơ sở dữ liệu để trả lời không.

PHÂN LOẠI:
1. LIÊN QUAN ĐẾN DỮ LIỆU: Cần truy vấn cơ sở dữ liệu để trả lời (ví dụ: tìm kiếm sản phẩm, dịch vụ, thông tin khách hàng, v.v.)
2. CÂU HỎI CHUNG: Có thể trả lời mà không cần truy vấn cơ sở dữ liệu (ví dụ: giờ làm việc, địa chỉ, thông tin chung về phòng khám)

Nếu là LIÊN QUAN ĐẾN DỮ LIỆU, hãy xác định LOẠI CÂU HỎI:
1. CÂU HỎI TỔNG QUÁT (GENERAL): Yêu cầu liệt kê tất cả hoặc phần lớn các mục (ví dụ: "cửa hàng có những sản phẩm gì?", "có những dịch vụ nào?")
2. CÂU HỎI LỌC (FILTERED_LIST): Yêu cầu liệt kê một nhóm mục với điều kiện lọc (ví dụ: "có sản phẩm nào dành cho em bé không?", "có dịch vụ nào trị mụn không?")
3. CÂU HỎI CHI TIẾT (SPECIFIC): Yêu cầu thông tin cụ thể về một mục xác định (ví dụ: "dịch vụ A có tác dụng gì?", "sản phẩm B có thành phần gì?")

Với câu hỏi LIÊN QUAN ĐẾN DỮ LIỆU, hãy xác định:
- Bảng chính cần truy vấn: (users, cosmetics, services, v.v.)
- Loại thông tin cần lấy: (danh sách, chi tiết cụ thể, thống kê, v.v.)
- Điều kiện tìm kiếm: (tên sản phẩm, khoảng giá, v.v.)
- Từ khóa lọc: (nếu là CÂU HỎI LỌC, liệt kê các từ khóa lọc như "em bé", "trị mụn", v.v.)

LƯU Ý QUAN TRỌNG: Chỉ trả về duy nhất một đối tượng JSON theo định dạng sau, không thêm giải thích, không thêm markdown:
{{
    "requires_database": true/false,
    "query_type": "LIST/DETAIL/STATISTICS/GENERAL",
    "question_category": "GENERAL/FILTERED_LIST/SPECIFIC",
    "main_table": "tên bảng",
    "search_criteria": ["các điều kiện tìm kiếm"],
    "filter_keywords": ["từ khóa lọc 1", "từ khóa lọc 2"],
    "explanation": "Giải thích ngắn gọn về phân loại"
}}
"""

# Bước 2: Sinh truy vấn SQL
SQL_GENERATION_PROMPT = f"""
Dựa trên câu hỏi: "{{user_question}}"
Hãy tạo truy vấn SQL để truy xuất thông tin từ cơ sở dữ liệu.

{DB_SCHEMA}

HƯỚNG DẪN:
1. Sử dụng phép JOIN khi cần lấy thông tin từ nhiều bảng
2. Tránh sử dụng SELECT * để lấy chính xác các cột cần thiết
3. Đối với sản phẩm, chỉ hiển thị những sản phẩm có isHidden = 0
4. Đối với dịch vụ, chỉ hiển thị những dịch vụ có is_active = 1
5. Hãy sử dụng các điều kiện phù hợp với câu hỏi (WHERE, ORDER BY, LIMIT)
6. Sử dụng placeholders %s cho các tham số trong câu truy vấn

Phân loại câu hỏi: {{question_category}}
Từ khóa lọc: {{filter_keywords}}

- Nếu là CÂU HỎI TỔNG QUÁT (GENERAL): Tạo truy vấn liệt kê tất cả các mục với thông tin cơ bản
- Nếu là CÂU HỎI LỌC (FILTERED_LIST): Tạo truy vấn có điều kiện WHERE dựa vào từ khóa lọc
- Nếu là CÂU HỎI CHI TIẾT (SPECIFIC): Tạo truy vấn chi tiết kèm điều kiện lọc cụ thể

LƯU Ý QUAN TRỌNG: Chỉ trả về duy nhất một đối tượng JSON theo định dạng sau, không thêm giải thích, không thêm markdown:
{{{{
    "query": "Câu truy vấn SQL đầy đủ",
    "params": ["Danh sách các tham số cho placeholders"],
    "explanation": "Giải thích ngắn gọn về cách truy vấn này sẽ trả lời câu hỏi"
}}}}
"""

# Bước 3: Định dạng kết quả
FORMAT_RESPONSE_PROMPT = """
Dựa trên câu hỏi: "{user_question}" và kết quả từ cơ sở dữ liệu: {db_results}

Hãy tạo một câu trả lời đầy đủ, dễ hiểu và thân thiện cho người dùng.

Phân loại câu hỏi: {question_category}
- Nếu là CÂU HỎI TỔNG QUÁT (GENERAL): Tạo câu trả lời tổng quan, CHỈ liệt kê tên các mục (sản phẩm/dịch vụ), KHÔNG bao gồm thông tin chi tiết như giá, mô tả, hoặc đánh giá. Nếu có quá nhiều mục, chỉ liệt kê tối đa 10 mục và đề xuất người dùng hỏi thêm để biết chi tiết.
- Nếu là CÂU HỎI LỌC (FILTERED_LIST): Tạo câu trả lời về nhóm mục tìm được, nhấn mạnh tiêu chí lọc và bao gồm thông tin cơ bản (tên, giá, đánh giá nếu có)
- Nếu là CÂU HỎI CHI TIẾT (SPECIFIC): Tạo câu trả lời chi tiết, đầy đủ về đối tượng được hỏi bao gồm tất cả thông tin liên quan

HƯỚNG DẪN:
1. Nếu có nhiều kết quả, tóm tắt thông tin quan trọng nhất
2. Nếu không có kết quả, đề xuất cách tìm kiếm khác hoặc gợi ý liên hệ trực tiếp
3. Định dạng câu trả lời để dễ đọc, sử dụng gạch đầu dòng khi liệt kê
4. Thêm đề xuất về các sản phẩm/dịch vụ khác nếu phù hợp
5. Giữ giọng điệu chuyên nghiệp và thân thiện

LƯU Ý ĐẶC BIỆT:
- Với câu hỏi GENERAL: chỉ liệt kê tên, KHÔNG thêm chi tiết, giá cả hay mô tả
- Với câu hỏi FILTERED_LIST: liệt kê tên và thông tin cơ bản
- Với câu hỏi SPECIFIC: cung cấp tất cả thông tin chi tiết

LƯU Ý QUAN TRỌNG: Chỉ trả về duy nhất một đối tượng JSON theo định dạng sau, không thêm giải thích, không thêm markdown:
{{
    "response": "Câu trả lời hoàn chỉnh",
    "suggested_follow_up": ["Câu hỏi liên quan 1", "Câu hỏi liên quan 2", "Câu hỏi liên quan 3"],
    "confidence": 0.0-1.0
}}
"""

# Bước 4: Xử lý câu hỏi chung
GENERAL_RESPONSE_PROMPT = """
Hãy trả lời câu hỏi chung sau: "{user_question}"

Đây là câu hỏi không cần truy cập vào cơ sở dữ liệu. Hãy trả lời với thông tin chung về O2 Skin - phòng khám da liễu và chăm sóc da.

Thông tin tham khảo:
- Phòng khám O2 Skin chuyên về da liễu và chăm sóc da
- Dịch vụ bao gồm điều trị mụn, nám, lão hóa da và các vấn đề về da khác
- Có các sản phẩm từ nhiều thương hiệu như La Beauty, Fixderma, GAMMA, On: The Body, Laroche posay
- Các bác sĩ có chuyên môn cao và nhiều năm kinh nghiệm
- Giờ làm việc: 8:00 - 20:00 từ thứ Hai đến Chủ nhật
- Hotline: 0123.456.789

LƯU Ý QUAN TRỌNG: Chỉ trả về duy nhất một đối tượng JSON theo định dạng sau, không thêm giải thích, không thêm markdown:
{{
    "response": "Câu trả lời hoàn chỉnh",
    "suggested_follow_up": ["Câu hỏi liên quan 1", "Câu hỏi liên quan 2", "Câu hỏi liên quan 3"],
    "confidence": 0.0-1.0
}}
"""

# ===================================
# PIPELINE XỬ LÝ
# ===================================

def get_database_connection() -> Tuple[Optional[pymysql.connections.Connection], Optional[str]]:
    """
    Tạo kết nối tới cơ sở dữ liệu MySQL
    
    Returns:
        Tuple[Optional[Connection], Optional[str]]: Kết nối và thông báo lỗi (nếu có)
    """
    try:
        conn = pymysql.connect(
            host=os.getenv('DB_HOST', 'localhost'),
            user=os.getenv('DB_USERNAME', 'root'),
            password=os.getenv('DB_PASSWORD', ''),
            db=os.getenv('DB_DATABASE', 'beauty_clinic'),
            charset='utf8mb4',
            cursorclass=pymysql.cursors.DictCursor
        )
        return conn, None
    except Exception as e:
        error_message = f"Lỗi kết nối đến MySQL: {str(e)}"
        logger.error(error_message)
        return None, error_message

def execute_sql_query(query: str, params: List = None) -> Tuple[List[Dict], Optional[str]]:
    """
    Thực thi truy vấn SQL và trả về kết quả
    
    Args:
        query (str): Câu truy vấn SQL
        params (List, optional): Tham số cho câu truy vấn
        
    Returns:
        Tuple[List[Dict], Optional[str]]: Kết quả truy vấn và thông báo lỗi (nếu có)
    """
    if params is None:
        params = []
        
    logger.info(f"Thực thi truy vấn: {query}")
    logger.info(f"Với tham số: {params}")
    
    conn, error = get_database_connection()
    if conn is None:
        return [], error
    
    try:
        with conn.cursor() as cursor:
            cursor.execute(query, params)
            results = cursor.fetchall()
        conn.close()
        return results, None
    except Exception as e:
        error_message = f"Lỗi khi thực thi truy vấn: {str(e)}"
        logger.error(error_message)
        if conn:
            conn.close()
        return [], error_message

def clean_json_string(json_str: str) -> str:
    """
    Làm sạch chuỗi JSON từ phản hồi của OpenAI
    
    Args:
        json_str (str): Chuỗi JSON cần làm sạch
        
    Returns:
        str: Chuỗi JSON đã được làm sạch
    """
    # Tìm JSON block trong markdown nếu có
    json_block_matches = re.findall(r'```(?:json)?\s*\n([\s\S]*?)\n```', json_str)
    if json_block_matches:
        # Lấy khối JSON đầu tiên tìm thấy
        return json_block_matches[0].strip()
    
    # Nếu không tìm thấy khối JSON, tìm cấu trúc JSON bắt đầu và kết thúc bằng {}
    json_matches = re.findall(r'({[\s\S]*?})', json_str)
    if json_matches:
        # Lấy khối JSON đầu tiên và lớn nhất
        return max(json_matches, key=len).strip()
    
    # Loại bỏ các ký tự markdown nếu không tìm thấy khối JSON
    if json_str.startswith('```json'):
        json_str = json_str[7:]
    if json_str.endswith('```'):
        json_str = json_str[:-3]
    
    return json_str.strip()

def fix_json_structure(json_text: str) -> str:
    """
    Sửa cấu trúc JSON không hoàn chỉnh
    
    Args:
        json_text (str): Chuỗi JSON cần sửa
        
    Returns:
        str: Chuỗi JSON đã được sửa
    """
    # Đếm số lượng dấu mở ngoặc nhọn và vuông
    open_braces = json_text.count('{')
    close_braces = json_text.count('}')
    open_brackets = json_text.count('[')
    close_brackets = json_text.count(']')
    
    # Tìm các product id và name trước khi sửa
    product_ids = re.findall(r'"id"\s*:\s*(\d+)', json_text)
    product_names = re.findall(r'"name"\s*:\s*"([^"]+)"', json_text)
    product_reasons = re.findall(r'"reason"\s*:\s*"([^"]+)"', json_text)
    
    # Nếu có JSON không hoàn chỉnh nhưng có thông tin sản phẩm, thử tạo JSON mới
    if len(product_ids) > 0 and len(product_names) > 0:
        # Tạo JSON mới với đúng cấu trúc
        new_json = '{\n    "found_products": ['
        
        # Thêm thông tin sản phẩm
        for i in range(min(len(product_ids), len(product_names))):
            reason = product_reasons[i] if i < len(product_reasons) else "Sản phẩm phù hợp với yêu cầu"
            if i > 0:
                new_json += ','
            new_json += f'\n        {{\n            "id": {product_ids[i]},\n            "name": "{product_names[i]}",\n            "reason": "{reason}"\n        }}'
        
        # Đóng JSON
        new_json += '\n    ],\n    "explanation": "Sản phẩm được tìm thấy phù hợp với yêu cầu."\n}'
        
        logger.info(f"Đã tạo JSON mới với thông tin sản phẩm: {new_json}")
        return new_json
    
    # Thêm dấu đóng ngoặc nếu thiếu
    fixed_text = json_text
    if open_braces > close_braces:
        fixed_text += '}' * (open_braces - close_braces)
    if open_brackets > close_brackets:
        fixed_text += ']' * (open_brackets - close_brackets)
    
    # Cố gắng sửa các lỗi phổ biến
    # Thiếu dấu phẩy giữa các phần tử
    fixed_text = re.sub(r'}\s*{', '},{', fixed_text)
    fixed_text = re.sub(r']\s*\[', '],[', fixed_text)
    fixed_text = re.sub(r'}\s*\[', '},\[', fixed_text)
    fixed_text = re.sub(r']\s*{', '],{', fixed_text)
    
    # Thiếu dấu ngoặc kép cho key
    fixed_text = re.sub(r'(\w+):', r'"\1":', fixed_text)
    
    # Sửa lỗi chuỗi JSON bị cắt giữa chừng
    if '"reason": "' in fixed_text and not fixed_text.endswith("}"):
        # Tìm vị trí bắt đầu của thuộc tính reason cuối cùng
        last_reason_start = fixed_text.rfind('"reason": "')
        
        # Nếu không tìm thấy dấu đóng ngoặc kép cho reason
        if last_reason_start != -1 and fixed_text.find('"', last_reason_start + 10) == -1:
            # Thêm dấu đóng ngoặc kép và hoàn thiện JSON
            fixed_text = fixed_text[:last_reason_start + 10] + product_reasons[0] if product_reasons else "Phù hợp với yêu cầu"
            fixed_text += '"\n        }\n    ],\n    "explanation": "Sản phẩm được tìm thấy phù hợp với yêu cầu."\n}'
    
    return fixed_text

def parse_json_response(response_text: str) -> Dict:
    """
    Parse JSON từ phản hồi của OpenAI, tự động sửa các lỗi phổ biến
    
    Args:
        response_text (str): Phản hồi văn bản từ OpenAI
        
    Returns:
        Dict: Dữ liệu JSON đã parse
    """
    logger.info(f"Đang parse phản hồi: {response_text[:100]}...")
    
    # Nếu có 'found_products' trong phản hồi, xử lý đặc biệt
    if '"found_products"' in response_text:
        logger.info("Phát hiện định dạng found_products, xử lý đặc biệt")
        # Tìm tất cả các ID và tên sản phẩm
        product_ids = re.findall(r'"id"\s*:\s*(\d+)', response_text)
        product_names = re.findall(r'"name"\s*:\s*"([^"]+)"', response_text)
        product_reasons = re.findall(r'"reason"\s*:\s*"([^"]*?)(?:"|$)', response_text)
        
        if product_ids and product_names:
            logger.info(f"Tìm thấy {len(product_ids)} ID sản phẩm và {len(product_names)} tên sản phẩm")
            
            # Tạo JSON đúng cấu trúc
            constructed_json = {
                "found_products": [
                    {
                        "id": int(product_ids[i]),
                        "name": product_names[i],
                        "reason": product_reasons[i] if i < len(product_reasons) else "Sản phẩm phù hợp với yêu cầu"
                    }
                    for i in range(min(len(product_ids), len(product_names)))
                ],
                "explanation": "Đã trích xuất thông tin sản phẩm từ phản hồi"
            }
            
            logger.info(f"Tạo thành công JSON với thông tin sản phẩm: {constructed_json}")
            return constructed_json
    
    cleaned_text = clean_json_string(response_text)
    logger.info(f"Chuỗi JSON đã làm sạch: {cleaned_text[:200]}")
    
    try:
        return json.loads(cleaned_text)
    except json.JSONDecodeError as e:
        logger.warning(f"Không thể parse JSON ban đầu: {str(e)}")
        
        try:
            # Sửa cấu trúc JSON
            fixed_text = fix_json_structure(cleaned_text)
            logger.info(f"Đã sửa cấu trúc JSON: {fixed_text[:200]}")
            return json.loads(fixed_text)
        except json.JSONDecodeError:
            # Thử sửa lỗi phổ biến: thiếu dấu ngoặc kép cho khóa
            try:
                fixed_text = re.sub(r'(\w+):', r'"\1":', cleaned_text)
                return json.loads(fixed_text)
            except json.JSONDecodeError:
                # Thử tìm và trích xuất phần JSON đơn giản nhất
                try:
                    # Tìm tất cả cấu trúc có dạng {...}
                    simple_json_matches = re.findall(r'{[^{]*?}', cleaned_text)
                    if simple_json_matches:
                        for json_candidate in simple_json_matches:
                            try:
                                return json.loads(json_candidate)
                            except:
                                continue
                except:
                    pass
                
                # Tìm ID sản phẩm trong chuỗi nếu có
                product_id_match = re.search(r'"id"\s*:\s*(\d+)', cleaned_text)
                product_name_match = re.search(r'"name"\s*:\s*"([^"]+)"', cleaned_text)
                
                if product_id_match and product_name_match:
                    product_id = int(product_id_match.group(1))
                    product_name = product_name_match.group(1)
                    
                    # Tạo đối tượng found_products tạm thời
                    return {
                        "found_products": [{
                            "id": product_id,
                            "name": product_name,
                            "reason": "Trích xuất từ phản hồi không đầy đủ"
                        }],
                        "explanation": "JSON không hoàn chỉnh, đã trích xuất thông tin sản phẩm"
                    }
                
                # Nếu vẫn không parse được, trả về một cấu trúc mặc định
                logger.error(f"Không thể parse JSON: {str(e)}")
                logger.error(f"Chuỗi JSON gốc: {response_text}")
                logger.error(f"Chuỗi JSON đã làm sạch: {cleaned_text}")
                
                return {
                    "query": "SELECT name, price, rating FROM cosmetics WHERE isHidden = 0 ORDER BY rating DESC LIMIT 10",
                    "params": [],
                    "explanation": "Truy vấn mặc định để lấy top 10 sản phẩm có xếp hạng cao nhất"
                }

def triage_question(client: OpenAI, user_question: str) -> Dict:
    """
    Phân loại câu hỏi để xác định cần truy cập database hay không
    và xác định đây là câu hỏi tổng quát, lọc, hay chi tiết
    
    Args:
        client (OpenAI): Client OpenAI
        user_question (str): Câu hỏi của người dùng
        
    Returns:
        Dict: Kết quả phân loại
    """
    logger.info(f"Phân loại câu hỏi: {user_question}")
    
    messages = [
        {"role": "system", "content": "Bạn là chuyên gia phân loại câu hỏi."},
        {"role": "user", "content": TRIAGE_PROMPT.format(user_question=user_question)}
    ]
    
    response = client.chat.completions.create(
        model="gpt-4o-mini",
        messages=messages,
        max_tokens=500
    )
    
    response_text = response.choices[0].message.content
    logger.info(f"Kết quả phân loại: {response_text}")
    
    result = parse_json_response(response_text)
    
    # Mặc định là câu hỏi tổng quát nếu không được xác định
    if "question_category" not in result:
        result["question_category"] = "GENERAL"
    
    # Đảm bảo có trường filter_keywords nếu là FILTERED_LIST
    if result.get("question_category") == "FILTERED_LIST" and "filter_keywords" not in result:
        result["filter_keywords"] = []
        
    return result

def generate_sql_query(client: OpenAI, user_question: str, question_category: str = "GENERAL", 
                     filter_keywords: List[str] = None, error_feedback: str = None) -> Dict:
    """
    Sinh truy vấn SQL dựa trên câu hỏi và schema
    
    Args:
        client (OpenAI): Client OpenAI
        user_question (str): Câu hỏi của người dùng
        question_category (str): Loại câu hỏi (GENERAL/FILTERED_LIST/SPECIFIC)
        filter_keywords (List[str]): Danh sách từ khóa lọc (cho FILTERED_LIST)
        error_feedback (str, optional): Phản hồi lỗi từ lần sinh trước
        
    Returns:
        Dict: Kết quả sinh SQL
    """
    logger.info(f"Sinh truy vấn SQL cho câu hỏi: {user_question} (Loại: {question_category})")
    
    if filter_keywords is None:
        filter_keywords = []
    
    prompt = SQL_GENERATION_PROMPT.format(
        user_question=user_question,
        question_category=question_category,
        filter_keywords=", ".join(filter_keywords)
    )
    
    # Thêm phản hồi lỗi nếu có
    if error_feedback:
        prompt += f"\n\nLƯU Ý: Truy vấn trước gặp lỗi: {error_feedback}\nHãy sửa và tạo truy vấn mới."
        
    messages = [
        {"role": "system", "content": "Bạn là chuyên gia SQL."},
        {"role": "user", "content": prompt}
    ]
    
    response = client.chat.completions.create(
        model="gpt-4o-mini",
        messages=messages,
        max_tokens=1000
    )
    
    response_text = response.choices[0].message.content
    logger.info(f"Kết quả sinh SQL: {response_text}")
    
    return parse_json_response(response_text)

def format_response(client: OpenAI, user_question: str, db_results: Union[List[Dict], str], question_category: str = "GENERAL") -> Dict:
    """
    Định dạng kết quả từ database thành câu trả lời
    
    Args:
        client (OpenAI): Client OpenAI
        user_question (str): Câu hỏi của người dùng
        db_results (Union[List[Dict], str]): Kết quả từ database hoặc thông báo lỗi
        question_category (str): Loại câu hỏi (GENERAL/FILTERED_LIST/SPECIFIC)
        
    Returns:
        Dict: Câu trả lời đã định dạng
    """
    logger.info(f"Định dạng kết quả cho câu hỏi loại: {question_category}")
    
    if isinstance(db_results, list):
        try:
            # Chuyển đổi Decimal thành float để có thể serialize thành JSON
            def decimal_default(obj):
                from decimal import Decimal
                if isinstance(obj, Decimal):
                    return float(obj)
                raise TypeError(f"Object of type {type(obj)} is not JSON serializable")
            
            db_results_json = json.dumps(db_results, ensure_ascii=False, default=decimal_default)
        except Exception as e:
            logger.error(f"Lỗi khi chuyển đổi kết quả thành JSON: {str(e)}")
            # Xử lý thủ công kết quả để loại bỏ các đối tượng Decimal
            processed_results = []
            for item in db_results:
                processed_item = {}
                for key, value in item.items():
                    from decimal import Decimal
                    if isinstance(value, Decimal):
                        processed_item[key] = float(value)
                    else:
                        processed_item[key] = value
                processed_results.append(processed_item)
            db_results_json = json.dumps(processed_results, ensure_ascii=False)
    else:
        db_results_json = f'"{db_results}"'
    
    messages = [
        {"role": "system", "content": SYSTEM_PROMPT},
        {"role": "user", "content": FORMAT_RESPONSE_PROMPT.format(
            user_question=user_question,
            db_results=db_results_json,
            question_category=question_category
        )}
    ]
    
    response = client.chat.completions.create(
        model="gpt-4o-mini",
        messages=messages,
        max_tokens=1000
    )
    
    response_text = response.choices[0].message.content
    logger.info(f"Kết quả định dạng: {response_text}")
    
    return parse_json_response(response_text)

def generate_general_response(client: OpenAI, user_question: str) -> Dict:
    """
    Tạo câu trả lời cho câu hỏi chung không cần truy cập database
    
    Args:
        client (OpenAI): Client OpenAI
        user_question (str): Câu hỏi của người dùng
        
    Returns:
        Dict: Câu trả lời đã định dạng
    """
    logger.info(f"Tạo câu trả lời chung cho câu hỏi: {user_question}")
    
    messages = [
        {"role": "system", "content": SYSTEM_PROMPT},
        {"role": "user", "content": GENERAL_RESPONSE_PROMPT.format(user_question=user_question)}
    ]
    
    response = client.chat.completions.create(
        model="gpt-4o-mini",
        messages=messages,
        max_tokens=1000
    )
    
    response_text = response.choices[0].message.content
    logger.info(f"Câu trả lời chung: {response_text}")
    
    return parse_json_response(response_text)

def process_special_questions(user_question: str) -> Optional[Dict]:
    """
    Xử lý các câu hỏi đặc biệt như chào hỏi, cảm ơn
    
    Args:
        user_question (str): Câu hỏi của người dùng
        
    Returns:
        Optional[Dict]: Câu trả lời đặc biệt hoặc None nếu không phải câu hỏi đặc biệt
    """
    question_lower = user_question.lower().strip()
    
    # Xử lý câu chào
    greetings = ["xin chào", "chào", "hello", "hi", "hey"]
    if any(greeting == question_lower for greeting in greetings):
            return {
            "response": "Xin chào! Tôi là trợ lý AI của O2 Skin. Tôi có thể giúp gì cho bạn về các vấn đề da liễu?",
            "suggested_follow_up": [
                "Bạn có thể cho tôi biết về các sản phẩm chăm sóc da không?",
                "Tôi muốn tìm hiểu về các dịch vụ điều trị mụn",
                "Giá của các sản phẩm kem dưỡng là bao nhiêu?"
            ],
            "confidence": 1.0
        }
    
    # Xử lý câu cảm ơn
    thank_patterns = ["cảm ơn", "thank", "thanks", "cám ơn"]
    if any(pattern in question_lower for pattern in thank_patterns):
            return {
            "response": "Rất vui được giúp đỡ bạn! Bạn còn cần hỗ trợ gì khác không?",
            "suggested_follow_up": [
                "Tôi muốn biết thêm về các sản phẩm khác",
                "Làm thế nào để đặt lịch hẹn?",
                "Tôi muốn tìm hiểu về các dịch vụ của phòng khám"
            ],
            "confidence": 1.0
        }
    
    return None

# Hàm tiện ích để xử lý đối tượng Decimal trong JSON
def decimal_converter(obj):
    """
    Chuyển đổi đối tượng Decimal thành float cho JSON serialization
    
    Args:
        obj: Đối tượng cần chuyển đổi
        
    Returns:
        Đối tượng đã chuyển đổi hoặc raise TypeError
    """
    from decimal import Decimal
    if isinstance(obj, Decimal):
        return float(obj)
    raise TypeError(f"Object of type {type(obj).__name__} is not JSON serializable")

# Thêm hàm tìm kiếm sản phẩm thông minh
def search_products_with_ai(client: OpenAI, user_question: str) -> Dict:
    """
    Tìm kiếm sản phẩm sử dụng AI để phân tích yêu cầu của người dùng
    
    Args:
        client (OpenAI): Client OpenAI
        user_question (str): Câu hỏi của người dùng
        
    Returns:
        Dict: Kết quả tìm kiếm
    """
    logger.info(f"Tìm kiếm sản phẩm thông minh cho: {user_question}")
    
    # Bước 1: Lấy tất cả sản phẩm từ cơ sở dữ liệu
    query = """
        SELECT c.id_cosmetic, c.name, c.price, c.rating, cat.name as category_name 
        FROM cosmetics c 
        JOIN categories cat ON c.id_category = cat.id_category 
        WHERE c.isHidden = 0
    """
    all_products, error = execute_sql_query(query)
    
    if error:
        logger.error(f"Lỗi khi lấy danh sách sản phẩm: {error}")
        return {
            "response": f"Xin lỗi, tôi không thể tìm kiếm sản phẩm do lỗi cơ sở dữ liệu: {error}",
            "suggested_follow_up": [
                "Bạn có thể thử lại sau?",
                "Tôi muốn biết về các dịch vụ của phòng khám"
            ],
            "confidence": 0.0
        }
    
    if not all_products:
        logger.info("Không tìm thấy sản phẩm nào trong cơ sở dữ liệu")
        return {
            "response": "Xin lỗi, hiện tại không có sản phẩm nào trong hệ thống.",
            "suggested_follow_up": [
                "Bạn có thể cho tôi biết về các dịch vụ không?",
                "Tôi muốn tìm hiểu về các liệu trình điều trị"
            ],
            "confidence": 0.8
        }
    
    # Nếu tìm thấy sản phẩm, tiếp tục xử lý
    logger.info(f"Tìm thấy {len(all_products)} sản phẩm trong cơ sở dữ liệu")
    
    # Bước 2: Sử dụng AI để tìm sản phẩm phù hợp
    products_list = "\n".join([f"ID: {p['id_cosmetic']}, Tên: {p['name']}, Danh mục: {p['category_name']}, Giá: {p['price']}đ, Đánh giá: {p['rating']}/5" for p in all_products])
    
    prompt = f"""
    Dựa trên yêu cầu của người dùng: "{user_question}"
    
    Hãy phân tích và tìm sản phẩm phù hợp nhất từ danh sách sau:
    
    {products_list}
    
    Nhiệm vụ của bạn là tìm sản phẩm phù hợp nhất dựa trên yêu cầu của người dùng. 
    Nếu người dùng hỏi về một loại sản phẩm cụ thể như "bọt vệ sinh", "kem dưỡng", "serum",... hãy tìm những sản phẩm có tên chứa các từ khóa đó.
    
    QUAN TRỌNG: Trả về kết quả dưới dạng CHUỖI JSON HOÀN CHỈNH có định dạng sau:
    {{
        "found_products": [
            {{
                "id": ID_SẢN_PHẨM,
                "name": "TÊN_SẢN_PHẨM",
                "reason": "LÝ_DO"
            }}
        ],
        "explanation": "GIẢI_THÍCH"
    }}
    
    Nếu không tìm thấy sản phẩm phù hợp, hãy trả về "found_products" là mảng rỗng.
    
    Đảm bảo chuỗi JSON đầy đủ và hợp lệ. KHÔNG THÊM bất kỳ giải thích hoặc định dạng Markdown bên ngoài JSON.
    """
    
    messages = [
        {"role": "system", "content": "Bạn là chuyên gia phân tích sản phẩm. Bạn chỉ trả về JSON hợp lệ dạng chuỗi, không có markdown hay giải thích thêm."},
        {"role": "user", "content": prompt}
    ]
    
    try:
        response = client.chat.completions.create(
            model="gpt-4o-mini",
            messages=messages,
            max_tokens=1000,
            response_format={"type": "json_object"}
        )
        
        response_text = response.choices[0].message.content
        logger.info(f"Kết quả phân tích sản phẩm: {response_text}")
        
        # Kiểm tra JSON trước khi parse
        try:
            json_data = json.loads(response_text)
            if 'found_products' not in json_data:
                logger.warning("JSON không chứa khóa 'found_products', sửa lại định dạng")
                json_data = {"found_products": [], "explanation": "Không tìm thấy sản phẩm phù hợp"}
                
            found_products = json_data.get('found_products', [])
        except json.JSONDecodeError:
            # Nếu không thể parse JSON, sử dụng phương pháp parse đặc biệt
            logger.warning("Không thể parse JSON, sử dụng phương pháp trích xuất thông tin sản phẩm")
            product_ids = re.findall(r'"id"\s*:\s*(\d+)', response_text)
            product_names = re.findall(r'"name"\s*:\s*"([^"]+)"', response_text)
            
            found_products = []
            for i in range(min(len(product_ids), len(product_names))):
                found_products.append({
                    "id": int(product_ids[i]),
                    "name": product_names[i],
                    "reason": "Sản phẩm phù hợp với yêu cầu của bạn"
                })
            
            json_data = {
                "found_products": found_products,
                "explanation": "Trích xuất thông tin từ phản hồi không đầy đủ"
            }
    except Exception as e:
        logger.error(f"Lỗi khi gọi API OpenAI: {str(e)}")
        # Thử tìm kiếm sản phẩm theo từ khóa đơn giản nếu API gặp lỗi
        keywords = user_question.lower().split()
        found_products = []
        
        for product in all_products:
            product_name = product['name'].lower()
            if any(keyword in product_name for keyword in keywords if len(keyword) > 2):
                found_products.append({
                    "id": product['id_cosmetic'],
                    "name": product['name'],
                    "reason": "Tìm thấy từ khóa trong tên sản phẩm"
                })
        
        json_data = {
            "found_products": found_products[:3],  # Giới hạn 3 sản phẩm
            "explanation": "Tìm kiếm dựa trên từ khóa đơn giản"
        }
    
    # Xử lý kết quả và tạo phản hồi
    found_products = json_data.get('found_products', [])
    
    if not found_products:
        # Trong trường hợp câu hỏi chung về sản phẩm, trả về top 5 sản phẩm có rating cao nhất
        if any(keyword in user_question.lower() for keyword in ["sản phẩm", "mỹ phẩm", "gợi ý", "đưa tôi", "những sản phẩm", "các sản phẩm"]):
            logger.info("Câu hỏi chung về sản phẩm, trả về top sản phẩm có rating cao")
            # Sắp xếp theo rating giảm dần và lấy 5 sản phẩm đầu tiên
            top_products = sorted(all_products, key=lambda x: float(x['rating']), reverse=True)[:5]
            
            if top_products:
                # Chỉ hiển thị tên sản phẩm và danh mục, không hiển thị giá và đánh giá
                products_info = "\n".join([f"- {p['name']} (Danh mục: {p['category_name']})" for p in top_products])
                return {
                    "response": f"""
Đây là một số sản phẩm nổi bật của chúng tôi:

{products_info}

Bạn muốn biết thêm thông tin chi tiết về sản phẩm nào?
                    """,
                    "suggested_follow_up": [
                        f"Tôi muốn biết thêm về {top_products[0]['name']}",
                        f"Giá của {top_products[0]['name']} là bao nhiêu?",
                        "Có sản phẩm nào cho da nhạy cảm không?"
                    ],
                    "confidence": 0.9
                }
        
        # Nếu không phải câu hỏi chung hoặc không có sản phẩm
        return {
            "response": f"Xin lỗi, tôi không tìm thấy sản phẩm nào phù hợp với yêu cầu '{user_question}' của bạn. Bạn có thể thử mô tả cụ thể hơn hoặc sử dụng từ khóa khác.",
            "suggested_follow_up": [
                "Bạn có thể giới thiệu cho tôi một số sản phẩm bán chạy nhất không?",
                "Tôi muốn biết về các sản phẩm chăm sóc da",
                "Có sản phẩm nào dành cho da nhạy cảm không?"
            ],
            "confidence": 0.7
        }
    
    # Lấy thông tin chi tiết của các sản phẩm được tìm thấy
    detailed_products = []
    for product in found_products:
        product_id = product.get("id")
        if product_id:
            # Tìm sản phẩm trong danh sách đã lấy
            for p in all_products:
                if p['id_cosmetic'] == product_id:
                    detailed_products.append({
                        **p,
                        "reason": product.get("reason", "")
                    })
                    break
    
    # Nếu không tìm thấy thông tin chi tiết, vẫn sử dụng kết quả từ AI
    if not detailed_products and found_products:
        return {
            "response": f"Tôi tìm thấy sản phẩm '{found_products[0].get('name')}' có thể phù hợp với yêu cầu của bạn, nhưng không thể lấy thông tin chi tiết. {found_products[0].get('reason', '')}",
            "suggested_follow_up": [
                "Bạn có thể giới thiệu cho tôi sản phẩm tương tự không?",
                "Tôi muốn biết thêm về các sản phẩm khác",
                "Có sản phẩm nào khác dành cho nhu cầu của tôi không?"
            ],
            "confidence": 0.6
        }
    
    # Tạo phản hồi với thông tin chi tiết
    if len(detailed_products) == 1:
        product = detailed_products[0]
        response = f"""
【{product['name']}】

▸ Danh mục: {product['category_name']}
▸ Giá bán: {product['price']}đ
▸ Đánh giá: {product['rating']}/5 ⭐

✅ Lý do phù hợp: {product['reason']}

Đây là sản phẩm phù hợp nhất với yêu cầu của bạn. Bạn có muốn biết thêm thông tin chi tiết không?
        """
    else:
        # Câu hỏi chung chung về nhiều sản phẩm, chỉ hiển thị tên và danh mục
        if any(keyword in user_question.lower() for keyword in ["sản phẩm", "mỹ phẩm", "gợi ý", "đưa tôi", "những sản phẩm", "các sản phẩm"]):
            products_info = "\n".join([f"- {p['name']} (Danh mục: {p['category_name']})" for p in detailed_products])
            response = f"""
Tôi đã tìm thấy {len(detailed_products)} sản phẩm phù hợp với yêu cầu của bạn:

{products_info}

Bạn muốn biết thêm thông tin chi tiết về sản phẩm nào?
            """
        else:
            # Câu hỏi cụ thể, hiển thị thông tin chi tiết hơn
            products_info = "\n".join([f"- {p['name']} ({p['category_name']}): {p['price']}đ - {p['reason']}" for p in detailed_products])
            response = f"""
Tôi đã tìm thấy {len(detailed_products)} sản phẩm phù hợp với yêu cầu của bạn:

{products_info}

Bạn muốn biết thêm thông tin chi tiết về sản phẩm nào?
            """
    
    return {
        "response": response,
        "suggested_follow_up": [
            f"Tôi muốn biết thêm về {detailed_products[0]['name']}",
            "Có sản phẩm nào tương tự không?",
            "Sản phẩm này có phù hợp với da nhạy cảm không?"
        ],
        "confidence": 0.9
    }

# Cập nhật hàm processQuery để thêm xử lý đặc biệt cho câu hỏi về sản phẩm
def processQuery(user_question: str, client: OpenAI) -> Dict:
    """
    Xử lý câu hỏi của người dùng theo quy trình RAG
    
    Args:
        user_question (str): Câu hỏi của người dùng
        client (OpenAI): Client OpenAI
        
    Returns:
        Dict: Kết quả xử lý câu hỏi
    """
    try:
        # Kiểm tra các câu hỏi đặc biệt trước
        special_response = process_special_questions(user_question)
        if special_response:
            return special_response
        
        # Xử lý đặc biệt cho câu hỏi về sản phẩm
        product_keywords = [
            "sản phẩm", "mỹ phẩm", "kem", "serum", "sữa rửa mặt", 
            "dung dịch", "bọt", "gel", "lotion", "toner", "mặt nạ"
        ]
        
        if any(keyword in user_question.lower() for keyword in product_keywords):
            # Sử dụng tìm kiếm sản phẩm thông minh
            logger.info("Phát hiện câu hỏi về sản phẩm, sử dụng tìm kiếm thông minh")
            return search_products_with_ai(client, user_question)
        
        # Bước 1: Phân loại câu hỏi
        triage_result = triage_question(client, user_question)
        
        # Nếu không cần truy cập database
        if not triage_result.get("requires_database", True):
            return generate_general_response(client, user_question)
        
        # Lấy loại câu hỏi (tổng quát, lọc, hay chi tiết)
        question_category = triage_result.get("question_category", "GENERAL")
        filter_keywords = triage_result.get("filter_keywords", [])
        logger.info(f"Loại câu hỏi: {question_category}, Từ khóa lọc: {filter_keywords}")
        
        # Bước 2: Sinh truy vấn SQL và thực thi (với tối đa 3 lần thử)
        max_attempts = 3
        results = []
        error = None
        
        for attempt in range(max_attempts):
            logger.info(f"Attempt {attempt + 1}/{max_attempts} to generate SQL query")
            
            try:
                # Sinh truy vấn SQL, sử dụng feedback lỗi cho các lần thử sau
                sql_result = generate_sql_query(client, user_question, question_category, filter_keywords, error)
                
                # Thực thi truy vấn
                query = sql_result.get("query", "")
                params = sql_result.get("params", [])
                
                if not query:
                    error = "Không thể tạo truy vấn SQL"
                    continue
                
                results, error = execute_sql_query(query, params)
                
                # Nếu thành công, dừng vòng lặp
                if not error:
                    break
            except Exception as e:
                error = str(e)
                logger.error(f"Lỗi trong lần thử {attempt + 1}: {error}")
        
        # Bước 3: Định dạng kết quả
        if error:
            # Nếu vẫn có lỗi sau tất cả các lần thử
            return format_response(client, user_question, f"Không thể truy vấn cơ sở dữ liệu: {error}", question_category)
        else:
            return format_response(client, user_question, results, question_category)
    
    except Exception as e:
        logger.error(f"Lỗi không xử lý được: {str(e)}")
        logger.exception("Exception traceback:")
        
        # Tạo phản hồi lỗi đầy đủ
        error_response = {
            "response": f"Xin lỗi, đã xảy ra lỗi khi xử lý câu hỏi của bạn. Chi tiết lỗi: {str(e)}",
            "suggested_follow_up": [
                "Bạn có thể thử hỏi với cách diễn đạt khác không?",
                "Tôi muốn biết về các sản phẩm chăm sóc da",
                "Tôi muốn biết về dịch vụ của phòng khám"
            ],
            "confidence": 0.0
        }
        
        return error_response
