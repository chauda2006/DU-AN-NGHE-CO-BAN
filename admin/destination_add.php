<?php
// 1. Kiểm tra quyền Admin (Chặn truy cập trái phép)
include 'check_admin.php';

// 2. Nhúng kết nối cơ sở dữ liệu nâng cao
include __DIR__ . '/../database.php';

$error = '';
$success = '';

// 3. Xử lý dữ liệu khi người dùng gửi Form (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $artist = trim($_POST['artist'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $release_date = trim($_POST['release_date'] ?? '');
    $story = trim($_POST['story'] ?? '');
    $expression = trim($_POST['expression'] ?? '');

    // Thiết lập đường dẫn mặc định trong thư mục hệ thống phòng trường hợp không upload file
    $img_path = 'images/sontung.jpg';
    $music_path = 'mp3/music.mp3';

    // Xử lý tải lên file ảnh đại diện (JPG/PNG)
    if (isset($_FILES['img_file']) && $_FILES['img_file']['error'] === UPLOAD_ERR_OK) {
        $img_name = time() . '_' . $_FILES['img_file']['name'];
        $img_target = __DIR__ . '/../images/' . $img_name;
        if (move_uploaded_file($_FILES['img_file']['tmp_name'], $img_target)) {
            $img_path = 'images/' . $img_name;
        }
    }

    // Xử lý tải lên file âm thanh bài hát (MP3)
    if (isset($_FILES['music_file']) && $_FILES['music_file']['error'] === UPLOAD_ERR_OK) {
        $music_name = time() . '_' . $_FILES['music_file']['name'];
        $music_target = __DIR__ . '/../mp3/' . $music_name;
        if (move_uploaded_file($_FILES['music_file']['tmp_name'], $music_target)) {
            $music_path = 'mp3/' . $music_name;
        }
    }

    if (empty($title) || empty($artist)) {
        $error = "Vui lòng nhập đầy đủ thông tin Tên bài hát và Ca sĩ thể hiện!";
    } else {
        try {
            // Câu lệnh SQL chuẩn kết nối Prepared Statement chống tấn công SQL Injection
            $sql = "INSERT INTO songs (title, artist, img, music, genre, release_date, story, expression) 
                    VALUES (:title, :artist, :img, :music, :genre, :release_date, :story, :expression)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':artist' => $artist,
                ':img' => $img_path,
                ':music' => $music_path,
                ':genre' => $genre,
                ':release_date' => $release_date,
                ':story' => $story,
                ':expression' => $expression
            ]);

            $success = "Thêm bài hát mới vào hệ thống thành công!";
            // Tự động điều hướng quay trở lại trang danh sách quản trị sau 1.5 giây
            header("refresh:1.5;url=dashboard.php");
        } catch (PDOException $e) {
            $error = "Lỗi hệ thống: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Bài Hát Mới - Admin Panel</title>
    <!-- Đồng bộ thiết kế Cyberpunk với hệ thống trang quản trị của bạn -->
    <style>
        body { font-family: Arial, sans-serif; background-color: #1e1e2e; color: #fff; margin: 0; padding: 0; }
        .admin-nav { background: #13131e; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; }
        .nav-links a { color: #aaa; text-decoration: none; margin-left: 20px; font-weight: bold; transition: 0.3s; }
        .nav-links a:hover { color: #00f2fe; text-shadow: 0 0 8px #00f2fe; }
        .form-container { max-width: 800px; margin: 40px auto; padding: 30px; background: rgba(30, 30, 46, 0.8); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); border: 1px solid rgba(0, 242, 254, 0.1); }
        h2 { color: #00f2fe; border-bottom: 1px solid #333; padding-bottom: 10px; margin-top:0; text-shadow: 0 0 8px rgba(0,242,254,0.3); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; display: flex; flex-direction: column; }
        .form-group label { margin-bottom: 8px; font-weight: bold; color: #00f2fe; font-size: 14px; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #333; border-radius: 6px; background-color: #13131e; color: #fff; box-sizing: border-box; font-size: 15px; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #00f2fe; outline: none; box-shadow: 0 0 8px rgba(0, 242, 254, 0.4); }
        .btn-group { display: flex; gap: 15px; margin-top: 25px; }
        .btn-submit { background: #00f2fe; color: #000; border: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 16px; transition: 0.3s; }
        .btn-submit:hover { box-shadow: 0 0 15px #00f2fe; }
        .btn-back { background: #444; color: #fff; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; text-align: center; font-size: 16px; }
        .btn-back:hover { background: #555; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-weight: bold; text-align: center; }
        .alert-danger { background-color: rgba(255, 0, 127, 0.2); border: 1px solid #ff007f; color: #ff007f; }
        .alert-success { background-color: rgba(0, 242, 254, 0.2); border: 1px solid #00f2fe; color: #00f2fe; }
    </style>
</head>
<body>

<nav class="admin-nav">
    <div style="font-weight: bold; font-size: 18px; color: #00f2fe;">MP3 MUSIC - MANAGEMENT</div>
    <div class="nav-links">
        <a href="dashboard.php">🎵 Quản lý bài hát</a>
        <a href="../index.php" style="color: #ff007f;">⌂ Xem trang chủ User</a>
    </div>
</nav>

<div class="form-container">
    <h2>Thêm Bài Hát Mới Vào Hệ Thống</h2>

    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <!-- Thuộc tính enctype="multipart/form-data" là bắt buộc để PHP có quyền nhận dữ liệu tải file -->
    <form action="destination_add.php" method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label>Tên bài hát / Tiêu đề:</label>
                <input type="text" name="title" placeholder="Nhập tên ca khúc..." required>
            </div>
            <div class="form-group">
                <label>Ca sĩ / Người thực hiện:</label>
                <input type="text" name="artist" placeholder="Nhập tên nghệ sĩ..." required>
            </div>
        </div>

 <div class="form-group">
    <label>Loại nhạc / Phân loại:</label>
    <select name="genre">
        <option value="V-Pop (Trong nước)">V-Pop (Trong nước)</option>
        <option value="K-Pop (Nước ngoài)">K-Pop (Nước ngoài)</option>
        
        <!-- ĐÃ SỬA: Đóng gói thuộc tính địa lý vào giá trị ngầm của Pop -->
        <option value="Pop (Nước ngoài)">Pop</option>
        
        <option value="Lofi / Không lời">Lofi / Không lời</option>
    </select>
</div>


            <div class="form-group">
                <label>Năm / Ngày sáng tác phát hành:</label>
                <input type="text" name="release_date" placeholder="Ví dụ: Năm 2026">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Tải lên ảnh bìa đại diện (JPG/PNG):</label>
                <input type="file" name="img_file" accept="image/*">
            </div>
            <div class="form-group">
                <label>Tải lên tệp âm thanh bài hát (MP3):</label>
                <input type="file" name="music_file" accept="audio/mp3">
            </div>
        </div>

        <div class="form-group">
            <label>Tiểu sử & Ý nghĩa / Hoàn cảnh sáng tác bài hát:</label>
            <textarea name="story" rows="4" placeholder="Nhập câu chuyện cốt truyện, hoàn cảnh ra đời của bài hát..."></textarea>
        </div>

        <div class="form-group">
            <label>Phong cách nghệ thuật thể hiện / Xử lý nốt của tác giả:</label>
            <textarea name="expression" rows="4" placeholder="Nhập phong cách ca sĩ thể hiện bài hát..."></textarea>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-submit">Lưu Hệ Thống</button>
            <a href="dashboard.php" class="btn-back">Hủy bỏ</a>
        </div>
    </form>
</div>

</body>
</html>
