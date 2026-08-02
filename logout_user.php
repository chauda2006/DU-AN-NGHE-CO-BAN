<?php
// 1. Khởi động hệ thống Session để nhận diện phiên làm việc hiện tại
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Hủy bỏ và xóa sạch các phiên làm việc riêng của tài khoản người dùng thường
unset($_SESSION['user_regular']);
unset($_SESSION['user_name_display']);
unset($_SESSION['user_status']);

// 3. ĐẢM BẢO QUAN TRỌNG: Điều hướng trình duyệt lập tức quay trở về trang chủ index.php
header("Location: index.php");
exit(); // Dừng mọi xử lý mã phía sau ngay lập tức
?>
