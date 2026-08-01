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
    <!-- Bài hát 1: Đã đổi tên bài hát thành Hate That I Made You Love Me -->
<div class="song-card" onclick="window.location.href='player.php?title=Hate That I Made You Love Me&artist=Ariana Grande&img=images/ariana grande.png&music=mp3/ariana grande.mp3'">
    <div class="song-thumb-wrapper">
        <img src="images/ariana grande.png" alt="Ariana Grande" class="song-img">
    </div>
    <div class="song-name">Hate That I Made You Love Me</div>
    <div class="song-artist">Ariana Grande</div>
</div>

            
                        <!-- BÀI 2: Sơn Tùng M-TP - ĐÃ SỬA ĐUÔI FILE THÀNH .JPG -->
            <div class="song-card" onclick="window.location.href='player.php?title=Come My Way&artist=Sơn Tùng M-TP&img=images/sontung.jpg&music=mp3/sontung.mp3&type=V-Pop (Trong nước)&date=Năm 2026&story=Bài hát mang giai điệu bắt tai sôi động, thể hiện thông điệp mạnh mẽ về tình yêu, sự kiên trì và khát khao chinh phục trái tim đối phương.&express=Sơn Tùng thể hiện bằng phong cách Pop/R&B trẻ trung, cách nhả chữ đặc trưng kết hợp với bản phối điện tử thời thượng.'">
                <div class="song-thumb-wrapper">
                    <!-- Gọi đúng file ảnh sontung.jpg trong thư mục images của bạn -->
                    <img src="images/sontung.jpg" alt="Sơn Tùng M-TP" class="song-img">
                </div>
                <div class="song-name">Come My Way</div>
                <div class="song-artist">Sơn Tùng M-TP</div>
            </div>

                      <!-- BÀI 3: BLACKPINK - ĐÃ ĐỔI TÊN CHUẨN THÀNH BÀI "GO" -->
            <div class="song-card" onclick="window.location.href='player.php?title=Go&artist=BLACKPINK&img=images/blackpink.png&music=mp3/blackpink.mp3&type=K-Pop (Nước ngoài)&date=Năm 2026&story=Bài hát mang giai điệu cực kỳ bùng nổ, thể hiện tinh thần phóng khoáng, tự do bước tiếp về phía trước và không ngần ngại phá vỡ mọi giới hạn.&express=BLACKPINK thể hiện bằng những đoạn rap cá tính kết hợp giai điệu điện tử dồn dập, đẩy nhịp điệu trống bass lên cao trào khiến đĩa nhạc giật neon vô cùng đẹp mắt.'">
                <div class="song-thumb-wrapper">
                    <!-- Gọi đúng file ảnh blackpink.jpg trong thư mục images của bạn -->
                    <img src="images/blackpink.png" alt="BLACKPINK" class="song-img">
                </div>
                <div class="song-name">Go</div>
                <div class="song-artist">BLACKPINK</div>
            </div>

                        <!-- BÀI 4: Hngle ft. Bảo Anh - CHẠY ẢNH VÀ NHẠC THẬT CỦA BẠN -->
            <div class="song-card" onclick="window.location.href='player.php?title=Tìm em&artist=Hngle ft. Bảo Anh&img=images/timem.jpg&music=mp3/timem.mp3&type=V-Pop (Trong nước)&date=Năm 2026&story=Bài hát là những giai điệu ballad nhẹ nhàng sâu lắng, kể về hành trình tìm kiếm và níu giữ những ký ức tình yêu đã qua đầy tiếc nuối.&express=Sự kết hợp giữa chất giọng trầm ấm của Hngle và giọng hát ngọt ngào truyền cảm của Bảo Anh trên nền phối nhẹ nhàng tạo nên sự đồng điệu cảm xúc.'">
                <div class="song-thumb-wrapper">
                    <!-- Gọi đúng file ảnh timem.jpg trong thư mục images của bạn -->
                    <img src="images/timem.jpg" alt="Tìm em" class="song-img">
                </div>
                <div class="song-name">Tìm em</div>
                <div class="song-artist">Hngle ft. Bảo Anh</div>
            </div>


</body>
</html>
