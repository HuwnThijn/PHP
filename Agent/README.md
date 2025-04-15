# Ứng dụng Trợ lý Nhà Thuốc sử dụng OpenAI GPT

Ứng dụng web cung cấp trợ lý AI giúp tra cứu thông tin về thuốc và đơn thuốc từ database MongoDB.

## Tính năng

- Giao diện trò chuyện đơn giản để hỏi đáp về thông tin nhà thuốc
- Tích hợp với OpenAI GPT để xử lý câu hỏi và trả lời tự nhiên
- Truy vấn thông tin từ MongoDB để cung cấp dữ liệu chính xác
- Tự động đề xuất câu hỏi liên quan cho người dùng
- Đảm bảo kết nối an toàn với cơ sở dữ liệu

## Cấu trúc database

Database MongoDB chứa các collection:
- **medicines**: Thông tin về các loại thuốc
- **prescriptions**: Thông tin về đơn thuốc
- **patients**: Thông tin về bệnh nhân
- **doctors**: Thông tin về bác sĩ
- **inventory**: Thông tin về kho thuốc

## Cài đặt

1. Cài đặt các thư viện cần thiết:

```bash
pip install -r requirements.txt
```

2. Cấu hình API key OpenAI:

Mở file `.env` và thay thế `your-api-key-here` bằng API key của bạn từ OpenAI.

3. Đảm bảo MongoDB đang chạy:

Ứng dụng kết nối đến MongoDB tại địa chỉ `mongodb://127.0.0.1:27017/DermatologyClinic`

## Chạy ứng dụng

```bash
python flaskapp.py
```

Sau đó, mở trình duyệt web và truy cập địa chỉ: `http://localhost:2001`

## Các file trong dự án

- **flaskapp.py**: Chương trình chính, xử lý các request và giao tiếp với OpenAI API
- **connection.py**: Xử lý kết nối đến MongoDB
- **prompt.py**: Chứa các mẫu prompt sử dụng cho trợ lý AI
- **templates/index.html**: Giao diện người dùng

## Ví dụ câu hỏi

- "Liệt kê các loại thuốc đang có trong nhà thuốc"
- "Hiển thị đơn thuốc gần đây nhất"
- "Kiểm tra số lượng tồn kho của thuốc Paracetamol"
- "Thuốc nào sắp hết hạn sử dụng?"
- "Hiển thị giá của thuốc Amoxicillin" 