<?php
// 1. Khởi động hệ thống Session để nhận diện phiên làm việc hiện tại
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Xóa sạch tất cả các biến đã lưu trong Session
$_SESSION = array();

// 3. Xóa Cookie của Session trên trình duyệt để tăng tính bảo mật
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Hủy hoàn toàn phiên làm việc (Session) trên máy chủ
session_destroy();

// 5. Chuyển hướng người dùng thoát thẳng về trang chủ chính của website
header("Location: ../index.php");
exit(); // Đảm bảo dừng mọi xử lý mã phía sau
?>
