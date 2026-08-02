<?php
// 1. Khởi động Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Nếu đã đăng nhập Admin từ trước, tự động chuyển thẳng vào Dashboard
if (isset($_SESSION['user']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

// 3. Xử lý khi người dùng nhấn nút Đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Cấu hình tài khoản đăng nhập cứng khớp với database phpMyAdmin của bạn
    $admin_username = 'admin';
    $admin_password = '123456'; 

    if (empty($username) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ Tài khoản và Mật khẩu!";
    } elseif ($username === $admin_username && $password === $admin_password) {
        // Đăng nhập đúng: Thiết lập thông tin phiên làm việc (Session)
        $_SESSION['user'] = $username;
        $_SESSION['is_admin'] = true;

        // Chuyển hướng ngay lập tức vào trang quản trị hệ thống
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Tài khoản hoặc Mật khẩu không chính xác!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Hệ Thống - Admin Login</title>
    <link rel="stylesheet" href="../css/index.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e2e;
            color: #ffffff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            background: rgba(37, 37, 56, 0.9);
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
            border: 1px solid #333;
        }
        h2 {
            text-align: center;
            color: #00f2fe;
            margin-top: 0;
            margin-bottom: 30px;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #bbb;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #444;
            border-radius: 6px;
            background-color: #13131e;
            color: #fff;
            box-sizing: border-box;
            font-size: 16px;
        }
        .form-group input:focus {
            border-color: #00f2fe;
            outline: none;
            box-shadow: 0 0 8px rgba(0, 242, 254, 0.4);
        }
        .btn-login {
            width: 100%;
            background: #00f2fe;
            color: #000;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
            transition: 0.3s;
        }
        .btn-login:hover {
            box-shadow: 0 0 15px #00f2fe;
        }
        .alert-danger {
            background-color: rgba(255, 0, 127, 0.2);
            border: 1px solid #ff007f;
            color: #ff007f;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
            font-size: 14px;
        }
        .hint {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>ĐĂNG NHẬP ADMIN</h2>

    <!-- Hiển thị lỗi nếu nhập sai -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">Tài khoản quản trị:</label>
            <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập..." required>
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu..." required>
        </div>

        <button type="submit" class="btn-login">Đăng Nhập</button>
    </form>
    
    <div class="hint">Tài khoản mặc định: admin / Mật khẩu: 123456</div>
</div>

</body>
</html>
