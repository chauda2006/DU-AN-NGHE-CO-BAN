<!-- index.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - MP3 Music Neon</title>
    <!-- Nhúng file CSS giao diện cấu trúc chính của trang chủ vào -->
    <link rel="stylesheet" href="css/index.css">
    <style>
        /* ==========================================================================
           CSS BỔ SUNG RIÊNG CHO KHỐI BẢNG XẾP HẠNG NGAY TRÊN TRANG CHỦ
           ========================================================================== */
        .home-bxh-section {
            margin-top: 50px;
            background: rgba(13, 13, 30, 0.4);
            border: 1px solid rgba(0, 242, 254, 0.1);
            padding: 30px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .home-bxh-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 15px;
        }

        .home-bxh-item {
            display: flex;
            align-items: center;
            background: rgba(20, 20, 45, 0.6);
            border: 1px solid rgba(255, 0, 127, 0.1);
            padding: 12px 20px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .home-bxh-item:hover {
            border-color: var(--neon-pink);
            box-shadow: 0 0 15px rgba(255, 0, 127, 0.2);
            transform: translateX(5px);
        }

        .home-bxh-rank {
            font-size: 26px;
            font-weight: 900;
            width: 45px;
            font-style: italic;
            color: #606080;
        }

        /* Đổi màu phát sáng cho Top 1, 2, 3 trang chủ */
        .home-rank-1 { color: #ffd700 !important; text-shadow: 0 0 10px #ffd700; }
        .home-rank-2 { color: #c0c0c0 !important; text-shadow: 0 0 10px #c0c0c0; }
        .home-rank-3 { color: #cd7f32 !important; text-shadow: 0 0 10px #cd7f32; }

        .home-bxh-img {
            width: 45px;
            height: 45px;
            border-radius: 6px;
            object-fit: cover;
            margin-right: 15px;
            border: 1px solid rgba(157, 0, 255, 0.3);
        }

        .home-bxh-name {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            flex: 2;
        }

        .home-bxh-item:hover .home-bxh-name {
            color: var(--neon-pink);
        }

        .home-bxh-artist {
            font-size: 13px;
            color: #a0a0c0;
            flex: 1;
        }

        .home-bxh-count {
            font-size: 13px;
            color: var(--neon-cyan);
            text-shadow: 0 0 5px rgba(0, 242, 254, 0.4);
            font-weight: 700;
            text-align: right;
        }

        .home-bxh-empty {
            text-align: center;
            padding: 20px;
            color: #606080;
            font-size: 14px;
            border: 1px dashed rgba(255,255,255,0.05);
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <!-- 1. GỌI THANH MENU NEON DÙNG CHUNG -->
    <?php include 'header.php'; ?>

    <!-- 2. KHỐI NỘI DUNG CHÍNH CỦA TRANG CHỦ -->
    <div class="main-content">
        
        <h1 class="welcome-title">Chào mừng bạn đến với thế giới âm nhạc</h1>
        <p style="color: #a0a0c0; margin-top: 5px;">Khám phá những giai điệu bùng nổ năng lượng Cyberpunk đêm nay.</p>

        <!-- KHU VỰC 1: BÀI HÁT MỚI PHÁT HÀNH -->
        <h2 class="section-title">Bài hát mới phát hành</h2>
        
        <div class="song-grid">
            <!-- Bài hát 1: Ariana Grande -->
            <div class="song-card" onclick="window.location.href='player.php?title=Hate That I Made You Love Me&artist=Ariana Grande&img=images/ariana grande.png&music=mp3/ariana grande.mp3&type=Âu Mỹ (Nước ngoài)&date=Năm 2007&story=Bài hát kể về sự bất lực khi yêu một người quá sâu sắc, lý trí không thắng nổi con tim.&express=Ariana thể hiện bằng chất giọng R&B sâu lắng kết hợp những nốt cao nghẹn ngào.'">
                <div class="song-thumb-wrapper">
                    <img src="images/ariana grande.png" alt="Ariana Grande" class="song-img">
                </div>
                <div class="song-name">Hate That I Made You Love Me</div>
                <div class="song-artist">Ariana Grande</div>
            </div>

            <!-- Bài hát 2: Sơn Tùng M-TP -->
            <div class="song-card" onclick="window.location.href='player.php?title=Come My Way&artist=Sơn Tùng M-TP&img=images/sontung.jpg&music=mp3/sontung.mp3&type=V-Pop (Trong nước)&date=Năm 2026&story=Bài hát mang giai điệu bắt tai sôi động, thể hiện thông điệp mạnh mẽ về tình yêu, sự kiên trì và khát khao chinh phục trái tim đối phương.&express=Sơn Tùng thể hiện bằng phong cách Pop/R&B trẻ trung, cách nhả chữ đặc trưng kết hợp với bản phối điện tử thời thượng.'">
                <div class="song-thumb-wrapper">
                    <img src="images/sontung.jpg" alt="Sơn Tùng M-TP" class="song-img">
                </div>
                <div class="song-name">Come My Way</div>
                <div class="song-artist">Sơn Tùng M-TP</div>
            </div>

            <!-- Bài hát 3: BLACKPINK -->
            <div class="song-card" onclick="window.location.href='player.php?title=Go&artist=BLACKPINK&img=images/blackpink.png&music=mp3/blackpink.mp3&type=K-Pop (Nước ngoài)&date=Năm 2026&story=Bài hát mang giai điệu cực kỳ bùng nổ, thể hiện tinh thần phóng khoáng, tự do bước tiếp về phía trước và không ngần ngại phá vỡ mọi giới hạn.&express=BLACKPINK thể hiện bằng những đoạn rap cá tính kết hợp giai điệu điện tử dồn dập, đẩy nhịp điệu trống bass lên cao trào khiến đĩa nhạc giật neon vô cùng đẹp mắt.'">
                <div class="song-thumb-wrapper">
                    <img src="images/blackpink.png" alt="BLACKPINK" class="song-img">
                </div>
                <div class="song-name">Go</div>
                <div class="song-artist">BLACKPINK</div>
            </div>

            <!-- Bài hát 4: Hngle ft. Bảo Anh -->
            <div class="song-card" onclick="window.location.href='player.php?title=Tìm em&artist=Hngle ft. Bảo Anh&img=images/timem.jpg&music=mp3/timem.mp3&type=V-Pop (Trong nước)&date=Năm 2026&story=Bài hát là những giai điệu ballad nhẹ nhàng sâu lắng, kể về hành trình tìm kiếm và níu giữ những ký ức tình yêu đã qua đầy tiếc nuối.&express=Sự kết hợp giữa chất giọng trầm ấm của Hngle và giọng hát ngọt ngào truyền cảm của Bảo Anh trên nền phối nhẹ nhàng tạo nên sự đồng điệu cảm xúc.'">
                <div class="song-thumb-wrapper">
                    <img src="images/timem.jpg" alt="Tìm em" class="song-img">
                </div>
                <div class="song-name">Tìm em</div>
                <div class="song-artist">Hngle ft. Bảo Anh</div>
            </div>
        </div> <!-- Đóng thẻ song-grid -->

        <!-- KHU VỰC 2: KHỐI BẢNG XẾP HẠNG TOP 5 XU HƯỚNG -->
        <div class="home-bxh-section">
            <h2 class="section-title" style="margin: 0; color: var(--neon-cyan); text-shadow: 0 0 8px rgba(0, 242, 254, 0.5);">📊 TOP 5 XU HƯỚNG NGHE NHIỀU</h2>
            <p style="color: #606080; font-size: 13px; margin-top: 3px;">Danh sách 5 ca khúc bùng nổ được bạn mở nghe nhiều nhất.</p>
            
            <div class="home-bxh-list" id="homeBxhView">
                <!-- Dữ liệu BXH thu nhỏ tự động chèn bằng JavaScript -->
            </div>
        </div>

    </div> <!-- Đóng thẻ main-content -->

    <!-- 3. JAVASCRIPT ĐẾM LƯỢT NGHE LIVE VÀ TỰ ĐỘNG SẮP XẾP TOP 5 -->
    <script>
        const homeBxhView = document.getElementById('homeBxhView');
        // Đọc dữ liệu số lượt nghe tích lũy trong máy
        const viewsData = JSON.parse(localStorage.getItem('music_views')) || {};
        let songsArray = Object.values(viewsData);

        if (songsArray.length === 0) {
            homeBxhView.innerHTML = `<div class="home-bxh-empty">📊 Chưa có dữ liệu xu hướng. Bài hát bạn nghe nhiều nhất sẽ xuất hiện tại đây!</div>`;
        } else {
            // Sắp xếp bài nghe nhiều nhất lên đầu bảng
            songsArray.sort((a, b) => b.count - a.count);
            
            // Cắt mảng để lấy chính xác tối đa TOP 5 bài hát thịnh hành nhất
            let top5Songs = songsArray.slice(0, 5);

            let htmlCode = '';
            top5Songs.forEach((song, index) => {
                const rankNum = index + 1;
                let rankClass = '';
                if (rankNum === 1) rankClass = 'home-rank-1';
                else if (rankNum === 2) rankClass = 'home-rank-2';
                else if (rankNum === 3) rankClass = 'home-rank-3';

                const targetUrl = `player.php?title=${encodeURIComponent(song.title)}&artist=${encodeURIComponent(song.artist)}&img=${encodeURIComponent(song.img)}&music=${encodeURIComponent(song.music)}`;

                htmlCode += `
                    <div class="home-bxh-item" onclick="window.location.href='${targetUrl}'" title="Bấm để phát ngay bài hát Top #${rankNum}">
                        <div class="home-bxh-rank ${rankClass}">#${rankNum}</div>
                        <img src="${song.img}" class="home-bxh-img" alt="${song.title}">
                        <div class="home-bxh-name">${song.title}</div>
                        <div class="home-bxh-artist">🎤 ${song.artist}</div>
                        <div class="home-bxh-count">🎧 ${song.count} lượt nghe</div>
                    </div>
                `;
            });
            homeBxhView.innerHTML = htmlCode;
        }
    </script>
</body>
</html>
