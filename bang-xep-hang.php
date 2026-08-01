<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Xếp Hạng - MP3 Music Neon</title>
    
    <!-- Nhúng file CSS giao diện cấu trúc chính và nền lưới Cyberpunk -->
    <link rel="stylesheet" href="css/index.css">
    
    <style>
        /* ==========================================================================
           GIAO DIỆN BẢNG XẾP HẠNG HIGH-TECH NEON RANKING (FULL ĐỒ HỌA CHUẨN)
           ========================================================================== */
        
        /* Khung bọc danh sách các dòng xếp hạng */
        .bxh-list {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* Định dạng từng dòng bài hát lọt top */
        .bxh-item {
            display: flex;
            align-items: center;
            background: rgba(13, 13, 30, 0.7);
            border: 1px solid rgba(0, 242, 254, 0.15);
            padding: 15px 25px;
            border-radius: 16px;
            cursor: pointer;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* Hiệu ứng bừng sáng viền và lao nhẹ về phía trước khi hover chuột */
        .bxh-item:hover {
            border-color: var(--neon-cyan);
            box-shadow: 0 0 25px rgba(0, 242, 254, 0.3), inset 0 0 15px rgba(0, 242, 254, 0.1);
            transform: translateX(8px);
        }

        /* Khối hiển thị số thứ tự vị trí xếp hạng (Rank Number) */
        .bxh-rank {
            font-size: 34px;
            font-weight: 900;
            width: 70px;
            text-align: center;
            font-style: italic;
            margin-right: 15px;
            color: #606080;
            transition: all 0.3s ease;
        }

        /* --- TRAO HUY CHƯƠNG LẤP LÁNH RIÊNG CHO TOP 3 BÀI ĐẦU BẢNG --- */
        .rank-1 { 
            color: #ffd700 !important; /* Màu Vàng */
            text-shadow: 0 0 15px #ffd700, 0 0 30px rgba(255, 215, 0, 0.4); 
            font-size: 42px; 
        } 
        .rank-2 { 
            color: #c0c0c0 !important; /* Màu Bạc */
            text-shadow: 0 0 15px #c0c0c0, 0 0 30px rgba(192, 192, 192, 0.4); 
            font-size: 36px; 
        } 
        .rank-3 { 
            color: #cd7f32 !important; /* Màu Đồng */
            text-shadow: 0 0 15px #cd7f32, 0 0 30px rgba(205, 127, 50, 0.4); 
            font-size: 34px; 
        }

        /* Khung ảnh đại diện bài hát */
        .bxh-img {
            width: 65px;
            height: 65px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid rgba(157, 0, 255, 0.4);
            margin-right: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.6);
            transition: all 0.3s ease;
        }

        .bxh-item:hover .bxh-img {
            border-color: var(--neon-cyan);
            box-shadow: 0 0 15px rgba(0, 242, 254, 0.4);
            transform: scale(1.05);
        }

        /* Khối chữ chứa thông tin bài */
        .bxh-details {
            flex: 2;
        }

        .bxh-title {
            font-size: 19px;
            font-weight: 700;
            color: #ffffff;
            transition: color 0.3s ease;
        }

        .bxh-item:hover .bxh-title {
            color: var(--neon-cyan);
            text-shadow: 0 0 6px rgba(0, 242, 254, 0.5);
        }

        .bxh-artist {
            font-size: 14px;
            color: #a0a0c0;
            margin-top: 4px;
        }

        /* Tổng số lượt nghe hiển thị rực rỡ góc phải dòng */
        .bxh-views {
            flex: 1;
            text-align: right;
            font-size: 15px;
            font-weight: 700;
            color: var(--neon-pink);
            text-shadow: 0 0 6px rgba(255, 0, 127, 0.4);
        }

        /* Giao diện thông báo khi bảng xếp hạng trống */
        .no-data {
            text-align: center;
            padding: 60px;
            color: #a0a0c0;
            border: 1px dashed rgba(0, 242, 254, 0.2);
            border-radius: 16px;
            background: rgba(10, 10, 26, 0.5);
            font-size: 16px;
        }
    </style>
</head>
<body>

    <!-- 1. GỌI THANH MENU NGANG NEON VÀ VIDEO NỀN DÙNG CHUNG TRÊN ĐỈNH ĐẦU -->
    <?php include 'header.php'; ?>

    <!-- 2. KHỐI NỘI DUNG CHÍNH CỦA TRANG BẢNG XẾP HẠNG -->
    <div class="main-content">
        
        <!-- Tiêu đề lớn có chữ chuyển sắc Gradient Cyberpunk độc quyền -->
        <h1 class="welcome-title">BẢNG XẾP HẠNG ÂM NHẠC</h1>
        <p style="color: #a0a0c0; margin-top: 5px;">Bảng vinh danh những giai điệu thịnh hành dựa trên lượt nghe thực tế của bạn.</p>
        
        <!-- Mục phụ tích hợp biểu tượng sóng âm lượng Equalizer tự nhảy -->
        <h2 class="section-title">Xu hướng hôm nay</h2>

        <!-- Khung danh sách trống chờ bộ não JavaScript tự động đổ dữ liệu xếp hạng -->
        <div class="bxh-list" id="bxhView"></div>

    </div>

    <!-- 3. BỘ NÃO JAVASCRIPT TỰ ĐỘNG TÍNH TOÁN XU HƯỚNG THEO LƯỢT NGHE REALTIME -->
    <script>
        const bxhView = document.getElementById('bxhView');
        // Đọc dữ liệu số lượt nghe (được tích lũy mỗi khi bấm Play ở player.php) từ bộ nhớ máy
        const viewsData = JSON.parse(localStorage.getItem('music_views')) || {};

        // Chuyển đổi cấu trúc Object thành dạng mảng Array để xử lý thuật toán sắp xếp
        let songsArray = Object.values(viewsData);

        if (songsArray.length === 0) {
            // Thông báo hướng dẫn nếu người dùng chưa mở nghe bài nhạc nào
            bxhView.innerHTML = `
                <div class="no-data">
                    📊 Hiện tại chưa có dữ liệu xu hướng.<br>
                    <span style="font-size: 14px; color: #606080; display: inline-block; margin-top: 10px;">
                        Hãy quay lại trang chủ và thưởng thức thật nhiều bài hát để tạo nên bảng xếp hạng riêng của bạn!
                    </span>
                </div>`;
        } else {
            // THUẬT TOÁN ĐỊNH TUYẾN XU HƯỚNG: Sắp xếp bài hát theo số lượt nghe giảm dần (b.count - a.count)
            songsArray.sort((a, b) => b.count - a.count);

            let htmlCode = '';
            songsArray.forEach((song, index) => {
                const rankNumber = index + 1; // Số thứ tự Top tự động tăng tiến (1, 2, 3...)
                let rankClass = '';

                // Phân bổ danh hiệu bừng sáng độc quyền cho Top 3
                if (rankNumber === 1) rankClass = 'rank-1';
                else if (rankNumber === 2) rankClass = 'rank-2';
                else if (rankNumber === 3) rankClass = 'rank-3';

                // Đóng gói chuỗi tham số an toàn để khi click vào dòng BXH là nghe lại được luôn bài đó
                const bxhUrl = `player.php?title=${encodeURIComponent(song.title)}&artist=${encodeURIComponent(song.artist)}&img=${encodeURIComponent(song.img)}&music=${encodeURIComponent(song.music)}`;

                htmlCode += `
                    <div class="bxh-item" onclick="window.location.href='${bxhUrl}'" title="Bấm để phát ngay bài hát Top #${rankNumber}">
                        <!-- Thứ hạng phát sáng huy chương -->
                        <div class="bxh-rank ${rankClass}">#${rankNumber}</div>
                        
                        <img src="${song.img}" class="bxh-img" alt="${song.title}">
                        
                        <div class="bxh-details">
                            <div class="bxh-title">${song.title}</div>
                            <div class="bxh-artist">🎤 Ca sĩ: ${song.artist}</div>
                        </div>
                        
                        <!-- Hiển thị bộ đếm số lượt nghe thật -->
                        <div class="bxh-views">🎧 ${song.count} lượt nghe</div>
                    </div>
                `;
            });
            // Bơm toàn bộ danh sách BXH thông minh vào khung HTML
            bxhView.innerHTML = htmlCode;
        }
    </script>
</body>
</html>
