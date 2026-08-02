<?php
include 'header.php';
include 'database.php';

$success_msg = "";
$error_msg = "";

// Ép buộc người dùng phải đăng nhập tài khoản thường trước mới được đăng ký gói
if (!isset($_SESSION['user_regular'])) {
    $error_msg = "⚠️ Vui lòng Đăng nhập tài khoản thành viên trước khi tiến hành nâng cấp gói VIP!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_regular'])) {
    $package_name = trim($_POST['package_name'] ?? '');
    $price = intval($_POST['price'] ?? 0);
    $username = $_SESSION['user_regular'];

    try {
        // Kiểm tra xem tài khoản này đã gửi yêu cầu chờ duyệt trước đó chưa
        $check = $conn->prepare("SELECT id FROM vip_requests WHERE username = :username AND status = 'Chờ phê duyệt'");
        $check->execute([':username' => $username]);
        
        if ($check->fetch()) {
            $error_msg = "Bản đăng ký gói VIP trước đó của bạn đang trong trạng thái chờ Admin phê duyệt, vui lòng không gửi lại!";
        } else {
            // Chèn yêu cầu đăng ký mới vào cơ sở dữ liệu
            $sql = "INSERT INTO vip_requests (username, package_name, price, status) VALUES (:username, :package_name, :price, 'Chờ phê duyệt')";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':username' => $username,
                ':package_name' => $package_name,
                ':price' => $price
            ]);
            $success_msg = "🚀 Gửi yêu cầu nâng cấp gói " . htmlspecialchars($package_name) . " thành công! Vui lòng chờ Admin kiểm tra và phê duyệt hệ thống.";
        }
    } catch (PDOException $e) {
        $error_msg = "Lỗi xử lý: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Nâng Cấp Gói VIP - MP3 MUSIC</title>
    <link rel="stylesheet" href="css/index.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; color: #fff; margin: 0; padding-top: 100px; }
        .vip-container { max-width: 1000px; margin: 40px auto; padding: 20px; text-align: center; }
        .vip-title { font-size: 36px; font-weight: 900; color: #ff007f; text-shadow: 0 0 15px #ff007f; text-transform: uppercase; margin-bottom: 10px; }
        .vip-subtitle { color: #a0a0c0; font-size: 16px; margin-bottom: 40px; }
        .vip-grid { display: flex; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; justify-content: center; max-width: 800px; margin: 0 auto 40px; }
        .vip-card { background: rgba(20, 20, 45, 0.6); backdrop-filter: blur(10px); border: 2px solid rgba(0, 242, 254, 0.2); border-radius: 20px; padding: 35px 25px; transition: all 0.3s ease; width: 300px; }
        .vip-card.popular { border-color: #ff007f; box-shadow: 0 0 20px rgba(255, 0, 127, 0.2); }
        .card-name { font-size: 22px; font-weight: bold; margin-bottom: 15px; }
        .card-price { font-size: 32px; font-weight: 800; color: #00f2fe; margin-bottom: 25px; }
        .features-list { list-style: none; padding: 0; margin: 0 0 30px 0; text-align: left; }
        .features-list li { margin-bottom: 12px; font-size: 14px; color: #ccc; }
        .features-list li::before { content: "✓ "; color: #00f2fe; font-weight: bold; }
        .btn-buy-vip { width: 100%; background: transparent; border: 2px solid #00f2fe; color: #00f2fe; padding: 12px; border-radius: 25px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-buy-vip:hover { background: #00f2fe; color: #000; box-shadow: 0 0 15px #00f2fe; }
        .vip-card.popular .btn-buy-vip { border-color: #ff007f; color: #ff007f; }
        .vip-card.popular .btn-buy-vip:hover { background: #ff007f; color: #fff; box-shadow: 0 0 15px #ff007f; }
        .vip-alert { max-width: 600px; margin: 0 auto 30px; padding: 15px; border-radius: 10px; font-weight: bold; text-align: center; }
        .vip-alert-success { background: rgba(0, 242, 254, 0.1); border: 1px solid #00f2fe; color: #00f2fe; }
        .vip-alert-danger { background: rgba(255, 0, 127, 0.1); border: 1px solid #ff007f; color: #ff007f; }
    </style>
</head>
<body>
<div class="vip-container">
    <h1 class="vip-title">Nâng Cấp Tài Khoản VIP</h1>
    <p class="vip-subtitle">Mở khóa đặc quyền tối cao - Bứt phá mọi giới hạn âm nhạc Cyberpunk</p>

    <?php if (!empty($success_msg)): ?><div class="vip-alert vip-alert-success"><?= $success_msg; ?></div><?php endif; ?>
    <?php if (!empty($error_msg)): ?><div class="vip-alert vip-alert-danger"><?= $error_msg; ?></div><?php endif; ?>

    <div class="vip-grid">
        <div class="vip-card">
            <div class="card-name">VIP THÁNG</div>
            <div class="card-price">19.000đ</div>
            <ul class="features-list">
                <li>Mở khóa 100% nhạc bản quyền</li>
                <li>Nghe nhạc Lossless chất lượng cao</li>
                <li>Tắt hoàn toàn quảng cáo hệ thống</li>
            </ul>
            <form action="dang-ky-vip.php" method="POST">
                <input type="hidden" name="package_name" value="VIP Tháng">
                <input type="hidden" name="price" value="19000">
                <button type="submit" class="btn-buy-vip" <?= !isset($_SESSION['user_regular']) ? 'disabled' : '' ?>>Đăng Ký Ngay</button>
            </form>
        </div>

        <div class="vip-card popular">
            <div class="card-name">VIP NĂM</div>
            <div class="card-price">149.000đ</div>
            <ul class="features-list">
                <li>Bao gồm mọi đặc quyền VIP tháng</li>
                <li>Sử dụng trọn gói 12 tháng liên tục</li>
                <li>Tiết kiệm chi phí lên đến 40%</li>
            </ul>
            <form action="dang-ky-vip.php" method="POST">
                <input type="hidden" name="package_name" value="VIP Năm">
                <input type="hidden" name="price" value="149000">
                <button type="submit" class="btn-buy-vip" <?= !isset($_SESSION['user_regular']) ? 'disabled' : '' ?>>Đăng Ký Ngay</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
