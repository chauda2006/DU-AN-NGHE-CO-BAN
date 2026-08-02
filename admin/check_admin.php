<?php
// Khởi động phiên làm việc (Session) nếu chưa được khởi động trước đó
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Kiểm tra xem người dùng đã đăng nhập hệ thống và có quyền Quản trị viên hay chưa.
 * Ở đây sử dụng biến session 'is_admin' để nhận diện vai trò.
 */
if (!isset($_SESSION['user']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    
    // Nếu không phải admin, hủy phiên làm việc để đảm bảo bảo mật
    // session_destroy(); 
    
    // Chuyển hướng người dùng quay trở lại trang đăng nhập của admin
    header("Location: login.php");
    exit(); // Dừng ngay việc thực thi các đoạn mã PHP bên dưới
}
?>
