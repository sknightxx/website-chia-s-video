1. Cấu trúc thư mục:
- Thư mục admin: chứa các file php về giao diện admin
- Thư mục api: chứa các file chức năng php
- Thư mục images:
    + Thư mục con user_profile: chứa hình ảnh của user
    + Thư mục con thumbnail: chứa ảnh bìa video
- Thư mục js: chứa các file javascript
- Thư mục styles: chứa các file css
- Thư mục vendor:
    + Thư mục con bootstrap-5.0.2: chứa file css của bootstrap 5
    + Thư mục con jquery: chứa file javascript của jquery
- Thư mục videos: chứa các videos của người dùng upload
- File database.sql: là file script này để import trên phpMyAdmin để tạo cơ sở dữ liệu và các dữ liệu đã crawl trước đó
2. Các chạy trang web:
- Truy cập trang 'http://localhost/phpmyadmin/index.php' (hoặc http://localhost:8080/phpmyadmin/index.php) và import file database.sql
- Sau đó bỏ thư mục source vào thư mục '../xampp/htdocs'
- Cuối cùng truy cập đường link 'http://localhost/source/' (hoặc http://localhost:8080/source/) để chạy trang web
- Truy cập vào trang 'http://localhost/source/login.php' (hoặc http://localhost:8080/source/login.php) và đăng nhập với tài khoản admin, mật khẩu: 123456