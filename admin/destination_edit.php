<?php
// 1. Kiểm tra quyền truy cập Admin
include 'check_admin.php';

// 2. Nhúng kết nối cơ sở dữ liệu
include __DIR__ . '/../database.php';

$error = '';
$success = '';
$song = null;

// 3. Kiểm tra ID hợp lệ để lấy thông tin cũ của bài hát lên Form
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $conn->prepare("SELECT * FROM songs WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $song = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$song) {
            die("Không tìm thấy bài hát với ID này trên hệ thống!");
        }
    } catch (PDOException $e) {
        die("Lỗi hệ thống: " . $e->getMessage());
    }
} else {
    header("Location: dashboard.php");
    exit();
}

// 4. Xử lý khi Admin nhấn nút "Cập nhật thay đổi" (Gửi Form POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $artist = trim($_POST['artist'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $release_date = trim($_POST['release_date'] ?? '');
    $story = trim($_POST['story'] ?? '');
    $expression = trim($_POST['expression'] ?? '');

    // Giữ lại đường dẫn file cũ mặc định nếu Admin không chọn file mới để thay thế
    $img_path = $song['img'];
    $music_path = $song['music'];

    // Xử lý nếu có upload ẢNH ĐẠI DIỆN mới
    if (isset($_FILES['img_file']) && $_FILES['img_file']['error'] === UPLOAD_ERR_OK) {
        $img_name = time() . '_' . $_FILES['img_file']['name'];
        $img_target = __DIR__ . '/../images/' . $img_name;
        if (move_uploaded_file($_FILES['img_file']['tmp_name'], $img_target)) {
            $img_path = 'images/' . $img_name;
        }
    }

    // Xử lý nếu có upload FILE NHẠC MP3 mới
    if (isset($_FILES['music_file']) && $_FILES['music_file']['error'] === UPLOAD_ERR_OK) {
        $music_name = time() . '_' . $_FILES['music_file']['name'];
        $music_target = __DIR__ . '/../mp3/' . $music_name;
        if (move_uploaded_file($_FILES['music_file']['tmp_name'], $music_target)) {
            $music_path = 'mp3/' . $music_name;
        }
    }

    if (empty($title) || empty($artist)) {
        $error = "Tên bài hát và tên Ca sĩ không được để trống!";
    } else {
        try {
            // Câu lệnh SQL cập nhật toàn bộ các trường dữ liệu hồ sơ tác phẩm nâng cao
            $sql = "UPDATE songs SET 
                        title = :title, 
                        artist = :artist, 
                        img = :img, 
                        music = :music, 
                        genre = :genre, 
                        release_date = :release_date, 
                        story = :story, 
                        expression = :expression 
                    WHERE id = :id";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':artist' => $artist,
                ':img' => $img_path,
                ':music' => $music_path,
                ':genre' => $genre,
                ':release_date' => $release_date,
                ':story' => $story,
                ':expression' => $expression,
                ':id' => $id
            ]);

            $success = "Cập nhật thông tin tác phẩm thành công!";
            
            // Cập nhật lại biến hiển thị dữ liệu tức thời trên form
            $song['title'] = $title;
            $song['artist'] = $artist;
            $song['genre'] = $genre;
            $song['release_date'] = $release_date;
            $song['story'] = $story;
            $song['expression'] = $expression;
            $song['img'] = $img_path;
            $song['music'] = $music_path;

            // Chuyển hướng tự động về dashboard sau 1.5 giây
            header("refresh:1.5;url=dashboard.php");
        } catch (PDOException $e) {
            $error = "Lỗi hệ thống, cập nhật thất bại: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh Sửa Bài Hát - Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #1e1e2e; color: #fff; margin: 0; padding: 0; }
        /* Thanh Menu Điều hướng Quản trị */
        .admin-nav { background: #13131e; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; }
        .nav-links a { color: #aaa; text-decoration: none; margin-left: 20px; font-weight: bold; transition: 0.3s; }
        .nav-links a:hover, .nav-links a.active { color: #00f2fe; text-shadow: 0 0 8px #00f2fe; }
        .btn-to-home { color: #ff007f !important; border: 1px solid #ff007f; padding: 5px 12px; border-radius: 4px; }
        .btn-to-home:hover { background: #ff007f; color: #fff !important; box-shadow: 0 0 10px #ff007f; }
        
        /* Giao diện khối form nhập liệu */
        .form-container { max-width: 800px; margin: 40px auto; padding: 30px; background: rgba(37, 37, 56, 0.9); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
        h2 { color: #00f2fe; border-bottom: 1px solid #333; padding-bottom: 10px; margin-top:0; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #bbb; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #444; border-radius: 6px; background-color: #13131e; color: #fff; box-sizing: border-box; font-size: 15px; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #00f2fe; outline: none; box-shadow: 0 0 8px rgba(0, 242, 254, 0.4); }
        .current-file-hint { font-size: 12px; color: #888; margin-top: 5px; word-break: break-all; }
        .btn-group { display: flex; gap: 15px; margin-top: 25px; }
        .btn-submit { background: #00f2fe; color: #000; border: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 16px; transition: 0.3s; }
        .btn-submit:hover { box-shadow: 0 0 15px #00f2fe; }
        .btn-back { background: #444; color: #fff; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; display: inline-block; text-align: center; font-size: 16px; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-weight: bold; }
        .alert-danger { background-color: rgba(255, 0, 127, 0.2); border: 1px solid #ff007f; color: #ff007f; }
        .alert-success { background-color: rgba(0, 242, 254, 0.2); border: 1px solid #00f2fe; color: #00f2fe; }
    </style>
</head>
<body>

<!-- THANH MENU QUẢN TRỊ ĐIỀU HƯỚNG VÀ NÚT QUAY LẠI TRANG CHỦ -->
<nav class="admin-nav">
    <div style="font-weight: bold; font-size: 18px; color: #00f2fe;">MP3 MUSIC - SYSTEM ADMIN</div>
    <div class="nav-links">
        <a href="dashboard.php" class="active">🎵 Quản lý bài hát</a>
        <a href="../index.php" class="btn-to-home">⌂ Quay lại trang chủ User</a>
        <a href="logout.php" style="color: #ff007f;">🚪 Đăng xuất</a>
    </div>
</nav>

<div class="form-container">
    <h2>Chỉnh Sửa Thông Tin Tác Phẩm (ID: <?= htmlspecialchars($id) ?>)</h2>
    
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <!-- Thuộc tính enctype="multipart/form-data" bắt buộc phải có để upload file -->
    <form action="destination_edit.php?id=<?= htmlspecialchars($id) ?>" method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label>Tên bài hát / Tiêu đề:</label>
                <input type="text" name="title" value="<?= htmlspecialchars($song['title'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Ca sĩ / Người thể hiện:</label>
                <input type="text" name="artist" value="<?= htmlspecialchars($song['artist'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Loại nhạc / Thể loại:</label>
                <select name="genre">
                    <option value="V-Pop (Trong nước)" <?= ($song['genre'] === 'V-Pop (Trong nước)') ? 'selected' : '' ?>>V-Pop (Trong nước)</option>
                    <option value="K-Pop (Nước ngoài)" <?= ($song['genre'] === 'K-Pop (Nước ngoài)') ? 'selected' : '' ?>>K-Pop (Nước ngoài)</option>
                    
                    <!-- ĐÃ SỬA CHUẨN: Giá trị ngầm gửi đi chứa chữ (Nước ngoài) để JS lọc, chữ hiển thị bên ngoài là Pop (Nước ngoài) để bạn dễ nhìn -->
                    <option value="Pop (Nước ngoài)" <?= ($song['genre'] === 'Pop (Nước ngoài)' || $song['genre'] === 'Pop') ? 'selected' : '' ?>>Pop (Nước ngoài)</option>
                    
                    <option value="Lofi / Không lời" <?= ($song['genre'] === 'Lofi / Không lời') ? 'selected' : '' ?>>Lofi / Không lời</option>
                </select>
            </div>
            <div class="form-group">
                <label>Năm / Ngày sáng tác phát hành:</label>
                <input type="text" name="release_date" value="<?= htmlspecialchars($song['release_date'] ?? 'Năm 2026') ?>">
            </div>
        </div>



        <div class="form-row">
            <div class="form-group">
                <label>Thay đổi ảnh bìa mới (Bỏ trống nếu giữ cũ):</label>
                <input type="file" name="img_file" accept="image/*">
                <div class="current-file-hint">📁 Đường dẫn hiện tại: <?= htmlspecialchars($song['img']) ?></div>
            </div>
            <div class="form-group">
                <label>Thay đổi file nhạc MP3 mới (Bỏ trống nếu giữ cũ):</label>
                <input type="file" name="music_file" accept="audio/mp3">
                <div class="current-file-hint">📁 Đường dẫn hiện tại: <?= htmlspecialchars($song['music']) ?></div>
            </div>
            <div class="form-group">
                <label>Thay đổi file nhạc MP3 mới (Bỏ trống nếu giữ cũ):</label>
                <input type="file" name="music_file" accept="audio/mp3">
                <div class="current-file-hint">📁 Đường dẫn hiện tại: <?= htmlspecialchars($song['music']) ?></div>
            </div>
        </div> <!-- Đóng thẻ form-row chứa ảnh và nhạc -->

        <div class="form-group">
            <label>Tiểu sử & Ý nghĩa / Hoàn cảnh sáng tác bài hát:</label>
            <textarea name="story" rows="4" placeholder="Nhập câu chuyện, hoàn cảnh ra đời tác phẩm..."><?= htmlspecialchars($song['story'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Phong cách nghệ thuật thể hiện / Xử lý nốt của tác giả:</label>
            <textarea name="expression" rows="4" placeholder="Nhập phong cách ca sĩ nhả chữ, xử lý âm nhạc..."><?= htmlspecialchars($song['expression'] ?? '') ?></textarea>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-submit">Cập nhật thay đổi</button>
            <a href="dashboard.php" class="btn-back">Hủy bỏ</a>
        </div>
    </form>
</div> <!-- Đóng thẻ form-container -->

</body>
</html>
