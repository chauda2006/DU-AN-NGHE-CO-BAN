<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Nghe Nhạc - MP3 Music Neon</title>
    
    <!-- Nhúng file CSS giao diện cấu trúc chính và nền lưới Cyberpunk -->
    <link rel="stylesheet" href="css/index.css">
    
    <style>
        /* ==========================================================================
           DANH SÁCH LỊCH SỬ NGHE NHẠC - ĐỊNH DẠNG DÒNG CHẠY NEON CYBERPUNK
           ========================================================================== */
        .history-list {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* Từng dòng bài hát trong lịch sử */
        .history-item {
            display: flex;
            align-items: center;
            background: rgba(13, 13, 30, 0.7);
            border: 1px solid rgba(0, 242, 254, 0.15);
            padding: 15px 25px;
            border-radius: 12px;
            cursor: pointer;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* Hiệu ứng bừng sáng viền và đẩy nhẹ sang phải khi di chuột qua */
        .history-item:hover {
            border-color: var(--neon-cyan);
            box-shadow: 0 0 15px rgba(0, 242, 254, 0.25), inset 0 0 10px rgba(0, 242, 254, 0.05);
            transform: translateX(6px);
        }

        /* Khung ảnh đại diện bài hát vuông vắn */
        .item-img {
            width: 55px;
            height: 55px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid rgba(157, 0, 255, 0.4);
            margin-right: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
        }

        .history-item:hover .item-img {
            border-color: var(--neon-pink);
            box-shadow: 0 0 10px rgba(255, 0, 127, 0.4);
        }

        /* Khối chữ chứa tên bài và ca sĩ */
        .item-details {
            flex: 2;
        }

        .item-title {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            transition: color 0.3s ease;
        }

        .history-item:hover .item-title {
            color: var(--neon-cyan);
            text-shadow: 0 0 5px rgba(0, 242, 254, 0.5);
        }

        .item-artist {
            font-size: 14px;
            color: #a0a0c0;
            margin-top: 3px;
        }

        /* Mốc thời gian nghe nhạc phát sáng bên góc phải dòng */
        .item-time {
            flex: 1;
            text-align: right;
            font-size: 13px;
            font-weight: 600;
            color: var(--neon-pink);
            text-shadow: 0 0 5px rgba(255, 0, 127, 0.4);
        }

        /* Giao diện thông báo khi lịch sử trống rỗng */
        .no-data {
            text-align: center;
            padding: 50px;
            color: #a0a0c0;
            border: 1px dashed rgba(0, 242, 254, 0.2);
            border-radius: 16px;
            background: rgba(10, 10, 26, 0.5);
            font-size: 16px;
        }
    </style>
</head>
<body>

    <!-- 1. GỌI THANH MENU NGANG NEON DÙNG CHUNG TRÊN ĐỈNH ĐẦU MÀN HÌNH -->
    <?php include 'header.php'; ?>

    <!-- 2. KHỐI NỘI DUNG CHÍNH CỦA TRANG LỊCH SỬ -->
    <div class="main-content">
        
        <!-- Tiêu đề chữ chuyển sắc Gradient hoành tráng -->
        <h1 class="welcome-title">Lịch Sử Nghe Nhạc</h1>
        <p style="color: #a0a0c0; margin-top: 5px;">Xem lại những giai điệu bùng nổ năng lượng Cyberpunk bạn đã thưởng thức gần đây.</p>
        
        <!-- Mục phụ có sóng nhạc Equalizer chuyển động tự động bên cạnh -->
        <h2 class="section-title">Vừa nghe xong</h2>

        <!-- Khung danh sách trống để JavaScript tự động quét bộ nhớ máy và chèn các dòng nhạc vào -->
        <div class="history-list" id="historyView"></div>

    </div>

    <!-- 3. BỘ NÃO JAVASCRIPT TỰ ĐỘNG KẾT XUẤT LỊCH SỬ THẬT TỪ LOCALSTORAGE -->
    <script>
        const historyView = document.getElementById('historyView');
        // Đọc mảng lịch sử từ bộ nhớ máy, nếu chưa có bài nào thì mặc định là mảng rỗng []
        const historyData = JSON.parse(localStorage.getItem('music_history')) || [];

        if (historyData.length === 0) {
            // Hiển thị thông báo hướng dẫn nếu người dùng chưa bấm phát nhạc bài nào
            historyView.innerHTML = `
                <div class="no-data">
                    📻 Bạn chưa thưởng thức giai điệu nào gần đây.<br>
                    <span style="font-size: 14px; color: #606080; display: inline-block; margin-top: 8px;">
                        Hãy quay lại trang chủ, bấm chọn một bài hát để hệ thống ghi nhận lịch sử tự động!
                    </span>
                </div>`;
        } else {
            let htmlCode = '';
            // Duyệt qua từng bài hát có trong bộ nhớ lịch sử và dựng giao diện HTML
            historyData.forEach(song => {
                // Đóng gói chuỗi tham số URL mã hóa an toàn để khi click vào dòng lịch sử sẽ nghe lại được ngay
                const listenAgainUrl = `player.php?title=${encodeURIComponent(song.title)}&artist=${encodeURIComponent(song.artist)}&img=${encodeURIComponent(song.img)}&music=${encodeURIComponent(song.music)}`;
                
                htmlCode += `
                    <div class="history-item" onclick="window.location.href='${listenAgainUrl}'" title="Bấm để nghe lại bài này">
                        <img src="${song.img}" class="item-img" alt="${song.title}">
                        <div class="item-details">
                            <div class="item-title">${song.title}</div>
                            <div class="item-artist">🎤 Ca sĩ: ${song.artist}</div>
                        </div>
                        <div class="item-time">⏳ Nghe lúc: ${song.time}</div>
                    </div>
                `;
            });
            // Bơm toàn bộ danh sách nhạc vào khung HTML
            historyView.innerHTML = htmlCode;
        }
    </script>
</body>
</html>
