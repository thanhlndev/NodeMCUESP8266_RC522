# NodeMCU ESP8266 RFID RC522 System

Hệ thống quản lý RFID sử dụng NodeMCU ESP8266 và module RC522, tích hợp với MySQL database.

## Mô tả

Dự án này là một hệ thống quản lý RFID cơ bản sử dụng NodeMCU ESP8266 và module RC522 để đọc thẻ RFID. Hệ thống được tích hợp với MySQL database để lưu trữ và quản lý thông tin người dùng.

## Tính năng chính

- Đọc và xác thực thẻ RFID
- Quản lý thông tin người dùng (thêm, sửa, xóa)
- Giao diện web thân thiện với người dùng
- Lưu trữ dữ liệu trong MySQL database
- Hệ thống đăng ký thẻ mới
- Xem lịch sử quét thẻ

## Yêu cầu hệ thống

- PHP 7.0 trở lên
- MySQL Server
- Web server (Apache/Nginx)
- NodeMCU ESP8266
- Module RFID RC522

## Cài đặt

1. Clone repository này về máy local
2. Import database schema vào MySQL server
3. Cấu hình thông tin database trong file `database.php`:
   ```php
   private static $dbName = 'nodemcu_rfidrc522_mysql';
   private static $dbHost = 'localhost:3306';
   private static $dbUsername = 'root';
   private static $dbUserPassword = 'root';
   ```
4. Upload code lên NodeMCU ESP8266
5. Kết nối module RC522 với NodeMCU theo sơ đồ chân

## Cấu trúc thư mục

- `database.php`: Cấu hình kết nối database
- `index.php`: Trang chủ hệ thống
- `registration.php`: Trang đăng ký thẻ mới
- `read_tag.php`: Trang đọc thẻ RFID
- `user_data.php`: Quản lý thông tin người dùng
- `getUID.php`: API lấy UID của thẻ
- `insertDB.php`: API thêm dữ liệu vào database
- `post_log.txt`: File log hệ thống

## Sử dụng

1. Truy cập trang chủ qua web browser
2. Sử dụng menu để điều hướng giữa các chức năng:
   - Home: Trang chủ
   - User Data: Quản lý thông tin người dùng
   - Registration: Đăng ký thẻ mới
   - Read Tag ID: Đọc thẻ RFID

## Bảo mật

- Sử dụng PDO để tránh SQL injection
- Mã hóa mật khẩu database
- Validate dữ liệu đầu vào
- Xác thực người dùng

## Đóng góp

Mọi đóng góp đều được hoan nghênh. Vui lòng tạo issue hoặc pull request để đóng góp.

## Giấy phép

Dự án này được phát hành dưới giấy phép MIT.
