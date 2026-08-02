<?php
// BẮT BUỘC ĐẶT DÒNG ĐẦU TIÊN CỦA FILE - TRÁNH LỖI WARNING SESSION
include 'header.php';
include 'database.php';

// Nếu người dùng thường đã đăng nhập rồi, tự động chuyển hướng về trang chủ
if (isset($_SESSION['user_regular'])) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';
$active_tab = 'login'; // Mặc định mở tab đăng nhập trước

// XỬ LÝ KHI NGƯỜI DÙNG GỬI FORM (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // A. XỬ LÝ LOGIC ĐĂNG KÝ TÀI KHOẢN MỚI
    if ($action === 'register') {
        $active_tab = 'register'; // Nếu lỗi, giữ nguyên giao diện ở tab đăng ký
        $username = trim($_POST['reg_username'] ?? '');
        $email = trim($_POST['reg_email'] ?? '');
        $full_name = trim($_POST['reg_full_name'] ?? '');
        $birthday = trim($_POST['reg_birthday'] ?? '');
        $password = trim($_POST['reg_password'] ?? '');

        if (empty($username) || empty($email) || empty($full_name) || empty($password)) {
            $error = "Vui lòng điền đầy đủ các thông tin bắt buộc!";
        } else {
            try {
                // Kiểm tra xem tên đăng nhập đã bị trùng lặp chưa
                $check = $conn->prepare("SELECT id FROM users WHERE username = :username");
                $check->execute([':username' => $username]);
                
                if ($check->fetch()) {
                    $error = "Tên tài khoản này đã tồn tại trên hệ thống, vui lòng chọn tên khác!";
                } else {
                    // Chèn thông tin tài khoản thành viên mới vào MySQL (mặc định trạng thái ban đầu là tài khoản 'Thường')
                    $sql = "INSERT INTO users (username, password, full_name, email, birthday, status) 
                            VALUES (:username, :password, :full_name, :email, :birthday, 'Thường')";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':username' => $username,
                        ':password' => $password,
                        ':full_name' => $full_name,
                        ':email' => $email,
                        ':birthday' => $birthday
                    ]);
                    $success = "🎉 Đăng ký tài khoản thành công! Vui lòng chuyển sang tab Đăng nhập để vào hệ thống.";
                    $error = "";
                    $active_tab = 'login'; // Đăng ký xong tự động chuyển giao diện về tab đăng nhập
                }
            } catch (PDOException $e) {
                $error = "Lỗi hệ thống không thể đăng ký: " . $e->getMessage();
            }
        }
    }

    // B. XỬ LÝ LOGIC ĐĂNG NHẬP
    if ($action === 'login') {
        $active_tab = 'login';
        $username = trim($_POST['login_username'] ?? '');
        $password = trim($_POST['login_password'] ?? '');

        if (empty($username) || empty($password)) {
            $error = "Vui lòng nhập tài khoản và mật khẩu đăng nhập!";
        } else {
            try {
                $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
                $stmt->execute([':username' => $username, ':password' => $password]);
                $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user_data) {
                    // Thiết lập phiên làm việc lưu trữ cục bộ cho người dùng thường
                    $_SESSION['user_regular'] = $user_data['username'];
                    $_SESSION['user_name_display'] = $user_data['full_name'];
                    $_SESSION['user_status'] = $user_data['status'] ?? 'Thường';

                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Tài khoản hoặc Mật khẩu thành viên không chính xác!";
                }
            } catch (PDOException $e) {
                $error = "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thành Viên - MP3 MUSIC</title>
    <link rel="stylesheet" href="css/index.css">
    <style>
        .auth-container { max-width: 450px; margin: 60px auto; padding: 35px; background: rgba(20, 20, 35, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(0, 242, 254, 0.2); border-radius: 16px; box-shadow: 0 0 25px rgba(0, 242, 254, 0.15); }
        
        /* Thiết kế thanh chuyển đổi Tab Đăng nhập / Đăng ký */
        .auth-tabs { display: flex; border-bottom: 2px solid #222; margin-bottom: 25px; gap: 10px; }
        .auth-tab-btn { flex: 1; padding: 12px; background: none; border: none; color: #888; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s; text-transform: uppercase; text-align: center; }
        .auth-tab-btn.active { color: #00f2fe; border-bottom: 2px solid #00f2fe; text-shadow: 0 0 8px rgba(0, 242, 254, 0.5); }
        
        .form-group-auth { margin-bottom: 18px; display: flex; flex-direction: column; }
        .form-group-auth label { margin-bottom: 8px; color: #bbb; font-size: 14px; font-weight: bold; text-align: left; }
        .form-group-auth input { width: 100%; padding: 12px; border: 1px solid #333; border-radius: 6px; background: #13131e; color: #fff; box-sizing: border-box; font-size: 15px; }
        .form-group-auth input:focus { border-color: #00f2fe; outline: none; box-shadow: 0 0 8px rgba(0, 242, 254, 0.3); }
        
        .btn-auth-submit { width: 100%; padding: 13px; background: linear-gradient(45deg, #00f2fe, #4facfe); color: #000; font-weight: bold; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; transition: 0.3s; margin-top: 15px; text-transform: uppercase; }
        .btn-auth-submit:hover { box-shadow: 0 0 15px #00f2fe; transform: scale(1.01); }
        
        .alert-box { padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-size: 14px; font-weight: bold; }
        .alert-box-danger { background: rgba(255, 0, 127, 0.15); border: 1px solid #ff007f; color: #ff007f; }
        .alert-box-success { background: rgba(0, 242, 254, 0.15); border: 1px solid #00f2fe; color: #00f2fe; }
    </style>
</head>
<body>

<div class="auth-container">
    <!-- Nút điều khiển chuyển đổi ẩn hiện qua lại giữa Đăng nhập và Đăng ký -->
    <div class="auth-tabs">
        <button type="button" class="auth-tab-btn <?= $active_tab === 'login' ? 'active' : '' ?>" onclick="switchTab('login')">Đăng Nhập</button>
        <button type="button" class="auth-tab-btn <?= $active_tab === 'register' ? 'active' : '' ?>" onclick="switchTab('register')">Đăng Ký</button>
    </div>

    <!-- Hiển thị thông báo trạng thái lỗi hoặc thành công -->
    <?php if (!empty($error)): ?><div class="alert-box alert-box-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="alert-box alert-box-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <!-- KHỐI FORM 1: ĐĂNG NHẬP -->
    <div id="loginFormSection" style="display: <?= $active_tab === 'login' ? 'block' : 'none' ?>;">
        <form action="login_user.php" method="POST">
            <input type="hidden" name="action" value="login">
            <div class="form-group-auth">
                <label>Tài khoản thành viên:</label>
                <input type="text" name="login_username" placeholder="Nhập tên đăng nhập của bạn..." required>
            </div>
            <div class="form-group-auth">
                <label>Mật khẩu khẩu:</label>
                <input type="password" name="login_password" placeholder="Nhập mật khẩu truy cập..." required>
            </div>
            <button type="submit" class="btn-auth-submit">Vào Hệ Thống</button>
        </form>
    </div>

    <!-- KHỐI FORM 2: ĐĂNG KÝ TÀI KHOẢN MỚI NÂNG CAO -->
    <div id="registerFormSection" style="display: <?= $active_tab === 'register' ? 'block' : 'none' ?>;">
        <form action="login_user.php" method="POST">
            <input type="hidden" name="action" value="register">
            <div class="form-group-auth">
                <label>Tên tài khoản (Viết liền không dấu):</label>
                <input type="text" name="reg_username" placeholder="Ví dụ: regularuser" required>
            </div>
            <div class="form-group-auth">
                <label>Địa chỉ Email hệ thống:</label>
                <input type="email" name="reg_email" placeholder="Ví dụ: user@gmail.com" required>
            </div>
            <div class="form-group-auth">
                <label>Họ và Tên đầy đủ:</label>
                <input type="text" name="reg_full_name" placeholder="Ví dụ: Nguyễn Văn A" required>
            </div>
            <div class="form-group-auth">
                <label>Ngày tháng năm sinh:</label>
                <input type="date" name="reg_birthday" required style="color-scheme: dark;">
            </div>
            <div class="form-group-auth">
                <label>Mật khẩu bảo mật:</label>
                <input type="password" name="reg_password" placeholder="Tạo mật khẩu đăng nhập bảo mật..." required>
            </div>
            <button type="submit" class="btn-auth-submit">Tạo Tài Khoản</button>
        </form>
    </div>
</div>

<script>
    // Hàm JavaScript điều khiển ẩn hiện tab mượt mà
    function switchTab(tabName) {
        const loginSection = document.getElementById('loginFormSection');
        const registerSection = document.getElementById('registerFormSection');
        const tabs = document.querySelectorAll('.auth-tab-btn');

        tabs.forEach(btn => btn.classList.remove('active'));

        if (tabName === 'login') {
            loginSection.style.display = 'block';
            registerSection.style.display = 'none';
            tabs[0].classList.add('active');
        } else {
            loginSection.style.display = 'none';
            registerSection.style.display = 'block';
            tabs[1].classList.add('active');
        }
    }
    </script>
</body>
</html>

