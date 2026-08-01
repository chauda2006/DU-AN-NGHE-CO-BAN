<!-- index.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - MP3 Music Neon</title>
    <!-- Nhúng file CSS giao diện cấu trúc chính của trang chủ vào -->
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

    <!-- 1. GỌI THANH MENU NEON DÙNG CHUNG -->
    <?php include 'header.php'; ?>

    <!-- 2. KHỐI NỘI DUNG CHÍNH CỦA TRANG CHỦ -->
    <div class="main-content">
        
        <h1 class="welcome-title">Chào mừng bạn đến với thế giới âm nhạc</h1>
        <p style="color: #a0a0c0; margin-top: 5px;">Khám phá những giai điệu bùng nổ năng lượng Cyberpunk đêm nay.</p>

        <!-- Khu vực hiển thị danh sách bài hát mẫu -->
        <h2 class="section-title">Bài hát mới phát hành</h2>
        
        <div class="song-grid">
            <!-- Bài hát 1 -->
            <div class="song-card">
                <div class="song-thumb"></div>
                <div class="song-name">Bài Hát Demo 1</div>
                <div class="song-artist">Ca Sĩ A</div>
            </div>
            
            <!-- Bài hát 2 -->
            <div class="song-card">
                <div class="song-thumb"></div>
                <div class="song-name">Bài Hát Demo 2</div>
                <div class="song-artist">Ca Sĩ B</div>
            </div>

            <!-- Bài hát 3 -->
            <div class="song-card">
                <div class="song-thumb"></div>
                <div class="song-name">Bài Hát Demo 3</div>
                <div class="song-artist">Artist Name</div>
            </div>

            <!-- Bài hát 4 -->
            <div class="song-card">
                <div class="song-thumb"></div>
                <div class="song-name">Bài Hát Demo 4</div>
                <div class="song-artist">Artist Name</div>
            </div>
        </div>

    </div>

</body>
</html>
