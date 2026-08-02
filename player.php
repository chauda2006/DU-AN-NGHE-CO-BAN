<?php
// BẮT BUỘC NẰM Ở DÒNG SỐ 1 - TRÊN CÙNG CỦA FILE PLAYER.PHP
include 'header.php';
include 'database.php';

// Nhận ID bài hát từ đường dẫn URL (Ví dụ: player.php?id=1)
$id = intval($_GET['id'] ?? 0);

try {
    // Truy vấn lấy dữ liệu chi tiết của bài hát dựa theo ID
    $stmt = $conn->prepare("SELECT * FROM songs WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $song = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Nếu không tìm thấy, báo lỗi tránh làm vỡ giao diện
    if (!$song) {
        die("<div style='color:#fff; text-align:center; margin-top:150px; font-family:sans-serif;'><h3>Không tìm thấy bài hát này trên hệ thống!</h3><a href='index.php' style='color:#00f2fe;'>Quay lại trang chủ</a></div>");
    }
} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đang phát: <?php echo htmlspecialchars($song['title']); ?></title>

    <link rel="stylesheet" href="css/index.css">
    <style>
     /* ==========================================================================
           GIAO DIỆN PLAYER MỚI - HIỆU ỨNG CHIẾU SÁNG ĐĨA CD VÀ GIẬT NEON THEO NHẠC
           ========================================================================== */
        
        .player-master {
            max-width: 1200px;
            margin: 20px auto;
            background: rgba(10, 10, 26, 0.85);
            border: 2px solid #00f2fe;
            box-shadow: 0 0 35px rgba(0, 242, 254, 0.35);
            border-radius: 24px;
            padding: 40px;
            backdrop-filter: blur(15px);
            display: flex;
            align-items: center;
            gap: 50px;
            position: relative;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            color: #ff007f;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            border: 1px solid #ff007f;
            padding: 8px 16px;
            border-radius: 20px;
            position: absolute;
            top: -60px;
            left: 0;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #ff007f;
            color: #ffffff;
            box-shadow: 0 0 20px #ff007f;
        }

        .player-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-right: 1px solid rgba(0, 242, 254, 0.2);
            padding-right: 30px;
        }

        /* KHUNG ĐĨA CD - TÍCH HỢP HIỆU ỨNG PHẢN QUANG CẦU VỒNG VÀ BIẾN PHÁT SÁNG GIẬT THEO NHẠC */
        .cd-disk-wrapper {
            position: relative;
            width: 290px;
            height: 290px;
            margin-bottom: 25px;
            border-radius: 50%;
            /* --glow-intensity sẽ được JavaScript thay đổi liên tục theo tần số Bass của nhạc */
            box-shadow: 
                0 0 calc(10px * var(--glow-intensity, 1)) rgba(0, 242, 254, calc(0.3 * var(--glow-intensity, 1))),
                0 0 calc(30px * var(--glow-intensity, 1)) rgba(157, 0, 255, calc(0.5 * var(--glow-intensity, 1)));
            transition: box-shadow 0.05s linear;
        }

        .cd-disk {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-image: url('<?php echo htmlspecialchars($song['img']); ?>');

            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            background-color: #080814;
            border: 12px solid #0d0d1a;
            position: relative;
            animation: cdRotate 15s linear infinite;
            overflow: hidden;
        }

        /* HIỆU ỨNG CHIẾU SÁNG ĐĨA CD (Phản quang cầu vồng chuyển sắc của đĩa CD thực tế) */
        .cd-disk::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            /* Lớp phủ dải quang phổ cầu vồng đối lưu góc chéo */
            background: conic-gradient(
                from 0deg,
                rgba(255, 0, 0, 0.1) 0deg,
                rgba(255, 154, 0, 0.1) 45deg,
                rgba(208, 222, 33, 0.12) 90deg,
                rgba(79, 220, 74, 0.1) 135deg,
                rgba(63, 218, 216, 0.15) 180deg,
                rgba(47, 201, 226, 0.1) 225deg,
                rgba(28, 127, 238, 0.12) 270deg,
                rgba(95, 21, 242, 0.1) 315deg,
                rgba(255, 0, 0, 0.1) 360deg
            );
            pointer-events: none;
            z-index: 2;
            mix-blend-mode: color-dodge; /* Giúp dải màu hòa trộn lung linh vào ảnh gốc */
        }

        /* Thêm vệt sáng phản chiếu (Specular Highlight) hình rẻ quạt cố định */
        .cd-disk-wrapper::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            border-radius: 50%;
            background: conic-gradient(
                from 45deg,
                transparent 0deg,
                rgba(255, 255, 255, 0.15) 20deg,
                transparent 40deg,
                transparent 180deg,
                rgba(255, 255, 255, 0.15) 200deg,
                transparent 220deg
            );
            z-index: 3;
            pointer-events: none;
            mix-blend-mode: overlay;
        }

        .cd-disk::after {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            width: 45px; height: 45px;
            background: #05050e;
            border: 4px solid #00f2fe;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            z-index: 4;
            box-shadow: 0 0 10px #00f2fe;
        }

        .interactive-controls { display: flex; gap: 30px; margin-bottom: 20px; }
        .control-btn {
            background: transparent; border: 1px solid rgba(255, 255, 255, 0.2); color: #a0a0c0;
            font-size: 22px; cursor: pointer; width: 50px; height: 50px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;
        }
        .control-btn.active-heart { color: #ff007f !important; border-color: #ff007f !important; text-shadow: 0 0 10px #ff007f; box-shadow: 0 0 15px rgba(255, 0, 127, 0.3); }
        .control-btn.active-repeat { color: #00f2fe !important; border-color: #00f2fe !important; text-shadow: 0 0 10px #00f2fe; box-shadow: 0 0 15px rgba(0, 242, 254, 0.3); }
        .control-btn:hover { transform: scale(1.1); border-color: #fff; color: #fff; }
        
        /* Bọc thanh audio nguyên bản */
        .audio-wrapper { width: 100%; position: relative; }
        audio { width: 100%; filter: hue-rotate(180deg) invert(90%); border-radius: 30px; }

        .player-right { flex: 1.5; display: flex; flex-direction: column; }
        .song-title-main { font-size: 34px; font-weight: 800; text-shadow: 0 0 15px var(--neon-cyan); margin-bottom: 5px;}
        .song-artist-main { font-size: 18px; color: #a0a0c0; margin-bottom: 25px; }
        .meta-tags { display: flex; gap: 12px; margin-bottom: 30px; }
        .tag { padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; text-transform: uppercase; }
        .tag-type { background: rgba(0, 242, 254, 0.1); color: #00f2fe; border: 1px solid rgba(0, 242, 254, 0.4); }
        .tag-date { background: rgba(255, 0, 127, 0.1); color: #ff007f; border: 1px solid rgba(255, 0, 127, 0.4); }
        .info-section { margin-bottom: 25px; }
        .info-heading { font-size: 15px; text-transform: uppercase; color: #ff007f; margin-bottom: 8px; font-weight: 700; text-shadow: 0 0 5px rgba(255, 0, 127, 0.4); }
        .info-body { font-size: 15px; color: #e0e0f0; line-height: 1.6; text-align: justify; }

        @keyframes cdRotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <!-- 1. GỌI THANH MENU NGANG DÙNG CHUNG TRÊN ĐỈNH ĐẦU -->
    <?php include 'header.php'; ?>

    <!-- 2. KHỐI NỘI DUNG CHÍNH CỦA TRÌNH PHÁT NHẠC -->
    <div class="main-content" style="padding-top: 130px;">
        <div style="position: relative; max-width: 1100px; margin: 0 auto;">
            <!-- Nút quay lại trang chủ -->
            <a href="index.php" class="btn-back">⬅ Trở về trang chủ</a>
            
            <div class="player-master">
                <!-- VÙNG BÊN TRÁI: ĐĨA CD XOAY VÀ TRÌNH PHÁT AUDIO -->
                <div class="player-left">
                    <!-- Khung bọc đĩa để kích hoạt hiệu ứng giật neon theo nhịp trống bass -->
                    <!-- Tự động nạp hình ảnh từ database làm nền cho đĩa CD -->
                    <div class="cd-disk-wrapper" id="cdWrapper">
                        <div class="cd-disk" id="cdDisk" style="background-image: url('<?php echo htmlspecialchars($song['img']); ?>'); background-size: cover; background-position: center;"></div>
                    </div>
                    
                    <!-- KHỐI NÚT TƯƠNG TÁC THẢ TIM & REPEAT -->
                    <div class="interactive-controls">
                        <button class="control-btn" id="repeatBtn" title="Lặp lại bài này">🔁</button>
                        <button class="control-btn" id="heartBtn" title="Thêm vào danh sách yêu thích">♥</button>
                    </div>

                    <!-- TRÌNH PHÁT NHẠC AUDIO NEON -->
                    <div class="audio-wrapper">
                        <audio id="musicPlayer" controls autoplay crossorigin="anonymous">
                            <source src="<?php echo htmlspecialchars($song['music']); ?>" type="audio/mpeg">
                            Trình duyệt không hỗ trợ phát âm thanh này.
                        </audio>
                    </div>
                </div>

                <!-- VÙNG BÊN PHẢI: HIỂN THỊ THÔNG TIN CHI TIẾT BÀI HÁT -->
                <div class="player-right">
                    <h1 class="song-title-main"><?php echo htmlspecialchars($song['title']); ?></h1>
                    <div class="song-artist-main">Ca sĩ: <?php echo htmlspecialchars($song['artist']); ?></div>

                    <!-- Các thẻ phân loại nhạc lấy trực tiếp từ database nâng cao -->
                    <div class="meta-tags">
                        <span class="tag tag-type">Loại nhạc: <?php echo htmlspecialchars($song['genre'] ?? 'V-Pop'); ?></span>
                        <span class="tag tag-date">Sáng tác: <?php echo htmlspecialchars($song['release_date'] ?? 'Năm 2026'); ?></span>
                    </div>

                    <!-- Ý nghĩa bài hát -->
                    <div class="info-section">
                        <div class="info-heading">Tiểu sử & Ý nghĩa bài hát</div>
                        <div class="info-body"><?php echo nl2br(htmlspecialchars($song['story'] ?? 'Chưa cập nhật câu chuyện ý nghĩa.')); ?></div>
                    </div>

                    <!-- Phương thức biểu đạt -->
                    <div class="info-section">
                        <div class="info-heading">Phong cách thể hiện của tác giả</div>
                        <div class="info-body"><?php echo nl2br(htmlspecialchars($song['expression'] ?? 'Chưa cập nhật phong cách thể hiện.')); ?></div>
                    </div>
                </div>

            </div>
        </div>
    </div>


       <!-- 3. BỘ NÃO JAVASCRIPT ĐIỀU KHIỂN ĐỒ HỌA SÓNG NHẠC LIVE VÀ LƯU TRỮ -->
    <script>
        const player = document.getElementById('musicPlayer');
        const cdDisk = document.getElementById('cdDisk');
        const cdWrapper = document.getElementById('cdWrapper');
        const heartBtn = document.getElementById('heartBtn');
        const repeatBtn = document.getElementById('repeatBtn');

        // Đồng bộ dữ liệu PHP sang đối tượng JavaScript động để nạp bộ nhớ máy
        const currentSong = {
            id: <?= $id ?>,
            title: "<?php echo addslashes($song['title']); ?>",
            artist: "<?php echo addslashes($song['artist']); ?>",
            img: "<?php echo addslashes($song['img']); ?>",
            music: "<?php echo addslashes($song['music']); ?>",
            genre: "<?php echo addslashes($song['genre'] ?? 'V-Pop'); ?>",
            time: new Date().toLocaleString('vi-VN')
        };

        // ==========================================================================
        // BỘ PHÂN TÍCH ÂM THANH REALTIME (WEB AUDIO API CHIẾU SÁNG GIẬT THEO NHẠC)
        // ==========================================================================
        let audioContext, analyser, source, dataArray;
        let isAudioSetup = false;

        function setupAudioContext() {
            if (isAudioSetup) return;
            
            // Khởi tạo bộ dựng âm thanh xử lý tần số của trình duyệt
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            analyser = audioContext.createAnalyser();
            
            // Kết nối nguồn nhạc từ thẻ audio vào bộ phân tích dữ liệu
            source = audioContext.createMediaElementSource(player);
            source.connect(analyser);
            analyser.connect(audioContext.destination);
            
            // Cài đặt độ nhạy phân tích dải tần bài hát
            analyser.fftSize = 64;
            const bufferLength = analyser.frequencyBinCount;
            dataArray = new Uint8Array(bufferLength);
            
            isAudioSetup = true;
            animateGlow(); // Bắt đầu chu kỳ quét tần số liên tục
        }

        // Vòng lặp quét biên độ tần số Bass để giật đèn Neon viền đĩa CD
        function animateGlow() {
            requestAnimationFrame(animateGlow);
            if (!analyser || player.paused) return;

            analyser.getByteFrequencyData(dataArray);
            
            // Trích xuất dải tần số thấp (Bass - tiếng trống dập)
            let bassValue = 0;
            let sampleCount = 6;
            for(let i = 0; i < sampleCount; i++) {
                bassValue += dataArray[i];
            }
            bassValue = bassValue / sampleCount; // Biên độ bass trung bình (0 đến 255)

            // Quy đổi sang tỉ lệ cường độ phát sáng (từ 1.0 đến tối đa 3.5 lần lực đập)
            let intensity = 1 + (bassValue / 255) * 2.5;

            // Bơm trực tiếp giá trị vào CSS-Variable để viền đĩa co giãn giật nhịp nhàng theo nhạc
            cdWrapper.style.setProperty('--glow-intensity', intensity);
        }

        // XỬ LÝ SỰ KIỆN PHÁT NHẠC (PLAY) - ĐÃ GỘP HOÀN CHỈNH BỘ ĐẾM VÀ LỊCH SỬ KHÔNG BỊ TRÙNG LẶP
        player.addEventListener('play', () => {
            cdDisk.style.animationPlayState = 'running';
            
            // Trình duyệt bảo mật yêu cầu người dùng tương tác trước khi kích hoạt AudioContext
            if (!audioContext) {
                setupAudioContext();
            } else if (audioContext.state === 'suspended') {
                audioContext.resume();
            }

            // 1. GHI NHẬN LỊCH SỬ NGHE NHẠC ĐỒNG BỘ TRANG LICH-SU.PHP (DÙNG ID)
            let historyList = JSON.parse(localStorage.getItem('listen_history')) || [];
            // Lọc bỏ bản ghi cũ của bài hát này để đẩy lượt nghe mới lên trên đầu trục thời gian
            historyList = historyList.filter(item => parseInt(item.id) !== currentSong.id);
            
            // Tạo cấu trúc mốc thời gian thực: Giờ:Phút - Ngày/Tháng
            const now = new Date();
            const timestamp = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} - ${String(now.getDate()).padStart(2, '0')}/${String(now.getMonth() + 1).padStart(2, '0')}`;
            
            historyList.unshift({ id: currentSong.id, time: timestamp });
            if (historyList.length > 15) { historyList = historyList.slice(0, 15); }
            localStorage.setItem('listen_history', JSON.stringify(historyList));

            // 2. BỘ ĐẾM LƯỢT NGHE TỰ ĐỘNG ĐỂ LÀM BẢNG XẾP HẠNG TRÊN TRANG CHỦ
            let views = JSON.parse(localStorage.getItem('music_views')) || {};
            let key = currentSong.title + " - " + currentSong.artist;
            if (views[key]) {
                views[key].count += 1;
            } else {
                views[key] = {
                    id: currentSong.id,
                    title: currentSong.title,
                    artist: currentSong.artist,
                    img: currentSong.img,
                    music: currentSong.music,
                    count: 1
                };
            }
            localStorage.setItem('music_views', JSON.stringify(views));
        });

        player.addEventListener('pause', () => {
            cdDisk.style.animationPlayState = 'paused';
            cdWrapper.style.setProperty('--glow-intensity', 1); // Trả đèn về trạng thái tĩnh khi nhấn dừng nhạc
        });

        // TỰ ĐỘNG CHẠY KHI VỪA VÀO TRANG (Giải quyết cơ chế chặn tự động phát của Chrome)
        window.addEventListener('click', () => {
            if (audioContext && audioContext.state === 'suspended') {
                audioContext.resume();
            }
        }, { once: true });

        // LOGIC TÙY CHỌN VÒNG LẶP BÀI HÁT (LOOP)
        repeatBtn.onclick = function() {
            player.loop = !player.loop;
            this.classList.toggle('active-repeat', player.loop);
        };

        // LOGIC THẢ TIM YÊU THÍCH ĐỒNG BỘ VỚI TRANG SO-THICH.PHP (LƯU MẢNG ID)
        let favorites = JSON.parse(localStorage.getItem('my_favorites')) || [];
        if (favorites.includes(currentSong.id)) {
            heartBtn.classList.add('active-heart');
        }

        heartBtn.onclick = function() {
            let favs = JSON.parse(localStorage.getItem('my_favorites')) || [];
            if (this.classList.contains('active-heart')) {
                favs = favs.filter(favId => favId !== currentSong.id);
                this.classList.remove('active-heart');
            } else {
                favs.unshift(currentSong.id);
                this.classList.add('active-heart');
            }
            localStorage.setItem('my_favorites', JSON.stringify(favs));
        };
    </script>
</body>
</html>
