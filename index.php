<?php
// 1. NẠP HEADER VÀ DATABASE Ở ĐÂY LÀ ĐỦ (DÒNG 1 TRÊN CÙNG)
include 'header.php';
include 'database.php';

try {
    $stmt = $conn->query("SELECT * FROM songs ORDER BY id DESC");
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Lỗi: " . $e->getMessage());
}
?>
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
    <div class="main-content" style="padding-top: 100px;">
        
        <h1 class="welcome-title">Chào mừng bạn đến với thế giới âm nhạc</h1>
        <p style="color: #a0a0c0; margin-top: 5px;">Khám phá những giai điệu bùng nổ năng lượng Cyberpunk đêm nay.</p>

        <!-- KHU VỰC 1: BÀI HÁT MỚI PHÁT HÀNH -->
        <h2 class="section-title">Bài hát mới phát hành</h2>
        
        <div class="song-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 25px;">
            <?php if (empty($songs)): ?>
                <p style="color: #606080; grid-column: 1/-1; text-align: center;">Chưa có bài hát nào hệ thống. Thêm nhạc ở trang quản trị ngay!</p>
            <?php else: ?>
                <?php foreach ($songs as $song): ?>
                    <!-- ĐÃ SỬA: Gọi hàm truyền chính xác ID lấy từ cơ sở dữ liệu động MySQL -->
                    <div class="song-card" onclick="trackViewAndPlay(<?= $song['id']; ?>, '<?php echo addslashes($song['title']); ?>', '<?php echo addslashes($song['artist']); ?>', '<?php echo $song['img']; ?>')">
                        <div class="song-thumb-wrapper">
                            <img src="<?php echo htmlspecialchars($song['img']); ?>" alt="<?php echo htmlspecialchars($song['title']); ?>" class="song-img">
                        </div>
                        <div class="song-name"><?php echo htmlspecialchars($song['title']); ?></div>
                        <div class="song-artist"><?php echo htmlspecialchars($song['artist']); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div> <!-- Đóng thẻ song-grid -->

        <!-- KHU VỰC 2: KHỐI BẢNG XẾP HẠNG TOP 5 XU HƯỚNG -->
        <div class="home-bxh-section">
            <h2 class="section-title" style="margin: 0; color: var(--neon-cyan); text-shadow: 0 0 8px rgba(0, 242, 254, 0.5);">TOP 5 XU HƯỚNG NGHE NHIỀU</h2>
            <p style="color: #606080; font-size: 13px; margin-top: 3px;">Danh sách 5 ca khúc bùng nổ được bạn mở nghe nhiều nhất.</p>
            
            <div class="home-bxh-list" id="homeBxhView">
                <!-- Dữ liệu BXH thu nhỏ tự động chèn bằng JavaScript -->
            </div>
        </div>

    </div> <!-- Đóng thẻ main-content -->

    <!-- JAVASCRIPT ĐẾM LƯỢT NGHE LIVE VÀ TỰ ĐỘNG SẮP XẾP TOP 5 -->
    <script>
        // ĐÃ SỬA: Hàm tăng view nhận thêm biến ID để điều hướng chuẩn sang cơ sở dữ liệu nâng cao
        function trackViewAndPlay(id, title, artist, img) {
            let viewsData = JSON.parse(localStorage.getItem('music_views')) || {};
            let key = title + " - " + artist;
            
            if (!viewsData[key]) {
                viewsData[key] = { id: id, title: title, artist: artist, img: img, count: 0 };
            }
            viewsData[key].count += 1;
            localStorage.setItem('music_views', JSON.stringify(viewsData));
            
            // Chuyển hướng sang trang phát nhạc với tham số ID thực tế để chạy lệnh kết nối database
            window.location.href = `player.php?id=${id}`;
        }

        // Tạo bảng xếp hạng trực tiếp từ dữ liệu máy khách
        function renderHomeBXH() {
            const homeBxhView = document.getElementById('homeBxhView');
            const viewsData = JSON.parse(localStorage.getItem('music_views')) || {};
            let songsArray = Object.values(viewsData);

            if (songsArray.length === 0) {
                homeBxhView.innerHTML = `<div class="home-bxh-empty">Chưa có dữ liệu xu hướng. Bài hát bạn nghe nhiều nhất sẽ xuất hiện tại đây!</div>`;
                return;
            }

            // Sắp xếp giảm dần theo lượt nghe và lấy Top 5
            songsArray.sort((a, b) => b.count - a.count);
            let top5 = songsArray.slice(0, 5);

            let html = '';
            top5.forEach((song, index) => {
                let rankClass = '';
                if(index === 0) rankClass = 'home-rank-1';
                else if(index === 1) rankClass = 'home-rank-2';
                else if(index === 2) rankClass = 'home-rank-3';

                // ĐG SỬA: Khối BXH thu nhỏ cũng được cấp tham số song.id để khi bấm trực tiếp từ bảng xếp hạng vẫn nhảy vào bài hát đúng
                html += `
                    <div class="home-bxh-item" onclick="trackViewAndPlay(${song.id}, '${song.title.replace(/'/g, "\\'")}', '${song.artist.replace(/'/g, "\\'")}', '${song.img}')">
                        <div class="home-bxh-rank ${rankClass}">0${index + 1}</div>
                        <img src="${song.img}" class="home-bxh-img" alt="${song.title}">
                        <div class="home-bxh-name">${song.title}</div>
                        <div class="home-bxh-artist">${song.artist}</div>
                        <div class="home-bxh-count">🎧 ${song.count} lượt</div>
                    </div>
                `;
            });
            homeBxhView.innerHTML = html;
        }

        // Tải BXH khi khởi động trang
        document.addEventListener('DOMContentLoaded', renderHomeBXH);
    </script>
</body>
</html>
