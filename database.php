<?php
// Cấu hình kết nối Database
$servername = "localhost";
$username = "root"; // Thay bằng tên tài khoản bạn vừa tạo thành công ở phpMyAdmin
$password = "";     // Thay bằng mật khẩu của tài khoản đó
$dbname = "do_an_cuoi_ky";

try {
    // Tạo kết nối qua PDO hỗ trợ UTF-8 tránh lỗi font tiếng Việt
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Bật chế độ báo lỗi ngoại lệ để dễ debug
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Kết nối cơ sở dữ liệu thất bại: " . $e->getMessage());
}
?>
