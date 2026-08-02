<?php
// Khởi động session để kiểm tra trạng thái đăng nhập admin và người dùng
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// BỘ NÃO TỰ ĐỘNG SỬA ĐƯỜNG DẪN THÔNG MINH CHO CẢ ADMIN VÀ USER
// Nếu địa chỉ trình duyệt đang truy cập chứa chữ "/admin" thì cấp số lùi thư mục bằng ../ ngược lại giữ nguyên trống
$rootPath = (strpos($_SERVER['REQUEST_URI'], '/admin') !== false) ? '../' : '';
?>
<!-- Đường liên kết bắt buộc nạp hiệu ứng Neon và Video Nền -->
<link rel="stylesheet" href="<?php echo $rootPath; ?>css/header.css">

<!-- KHỐI CHÈN VIDEO NỀN ĐỘNG DÙNG CHUNG CHO TOÀN WEBSITE (BẮT BUỘC PHẢI CÓ) -->
<div class="video-bg-container">
    <video autoplay loop muted playsinline class="video-bg-content">
        <!-- Gọi chính xác file nen01.mp4 nằm trong thư mục videos của bạn -->
        <source src="<?php echo $rootPath; ?>videos/nen01.mp4" type="video/mp4">
        Trình duyệt của bạn không hỗ trợ video nền này.
    </video>
    <!-- Lớp phủ mờ giúp các khối bài hát nổi bật hơn -->
    <div class="video-bg-overlay"></div>
</div>

<header class="top-header">
    <div class="logo">
        <h2>MP3 MUSIC</h2>
    </div>
    <nav class="navigation">
        <ul>
            <li><a href="<?php echo $rootPath; ?>index.php"><span class="icon">⌂</span> Trang chủ</a></li>
            <li><a href="<?php echo $rootPath; ?>kham-pha.php"><span class="icon">⌕</span> Khám phá</a></li>
            <li><a href="<?php echo $rootPath; ?>bang-xep-hang.php"><span class="icon">📊</span> BXH</a></li>
            <li><a href="<?php echo $rootPath; ?>lich-su.php"><span class="icon">⏳</span> Lịch sử</a></li>
            <li><a href="<?php echo $rootPath; ?>so-thich.php"><span class="icon">♥</span> Sở thích</a></li>
            <li><a href="<?php echo $rootPath; ?>dang-ky-vip.php" class="btn-vip">Đăng ký gói VIP</a></li>
            
            <!-- KHỐI TÀI KHOẢN NGƯỜI DÙNG THƯỜNG (HIỂN THỊ ĐỘC LẬP) -->
            <?php if (isset($_SESSION['user_regular'])): ?>
                <li>
                    <span style="color: #fff; font-size: 13px; background: rgba(255,255,255,0.05); padding: 5px 10px; border-radius: 4px; display: inline-block;">
                        👤 <?= htmlspecialchars($_SESSION['user_name_display']); ?> 
                        <span style="color: #00f2fe; font-size: 11px; font-weight: bold;">(<?= $_SESSION['user_status'] ?>)</span>
                    </span>
                    <a href="<?php echo $rootPath; ?>logout_user.php" style="color: #ff007f; margin-left: 5px; font-size: 12px; text-decoration: none;">[Thoát]</a>
                </li>
            <?php else: ?>
                <li>
                    <a href="<?php echo $rootPath; ?>login_user.php" class="btn-admin" style="background: rgba(0, 242, 254, 0.1); border: 1px solid #00f2fe; color: #00f2fe; padding: 5px 10px; border-radius: 4px;">
                        👤 Đăng nhập
                    </a>
                </li>
            <?php endif; ?>

            <!-- KHỐI ĐĂNG NHẬP / QUẢN TRỊ ADMIN (HIỂN THỊ ĐỘC LẬP HOÀN TOÀN SONG SONG) -->
            <?php if (isset($_SESSION['user']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                <li>
                    <a href="<?php echo $rootPath; ?>admin/dashboard.php" class="btn-admin" style="border: 1px solid #ff007f; color: #ff007f; padding: 5px 10px; border-radius: 4px; background: rgba(255, 0, 127, 0.05); box-shadow: 0 0 8px rgba(255, 0, 127, 0.2);">
                        ⚙️ Quản trị
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="<?php echo $rootPath; ?>admin/login.php" class="btn-admin" style="background: rgba(255,255,255,0.05); border: 1px solid #666; color: #aaa; padding: 5px 10px; border-radius: 4px;">
                        🔑 Admin
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
