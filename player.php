<?php
// HẤP THỤ DỮ LIỆU ĐƯỢC TRUYỀN TỪ TRANG CHỦ SANG
$song_title   = isset($_GET['title']) ? htmlspecialchars($_GET['title']) : "Hate That I Made You Love Me";
$song_artist  = isset($_GET['artist']) ? htmlspecialchars($_GET['artist']) : "Ariana Grande";
$song_img     = isset($_GET['img']) ? htmlspecialchars($_GET['img']) : "images/ariana grande.png";
$song_music   = isset($_GET['music']) ? htmlspecialchars($_GET['music']) : "mp3/ariana grande.mp3";

$song_type    = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : "Pop (Nước ngoài)";
$song_date    = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : "31/07/2026";
$song_story   = isset($_GET['story']) ? htmlspecialchars($_GET['story']) : "Bài hát là lời tự sự đầy mâu thuẫn về một tình yêu sâu sắc, nơi nhân vật chính vừa oán giận vừa biết ơn vì đối phương đã khiến mình yêu điên cuồng đến đánh mất lý trí.";
$song_express = isset($_GET['express']) ? htmlspecialchars($_GET['express']) : "Tác giả thể hiện ca khúc bằng những nốt cao nghẹn ngào kết hợp bản phối R&B đương đại, tạo nên sự giằng xé nội tâm mãnh liệt giữa lý trí và con tim.";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đang phát: <?php echo $song_title; ?></title>
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
            background-image: url('<?php echo $song_img; ?>'); 
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
                    <div class="cd-disk-wrapper" id="cdWrapper">
                        <div class="cd-disk" id="cdDisk"></div>
                    </div>
                    
                    <!-- KHỐI NÚT TƯƠNG TÁC THẢ TIM & REPEAT -->
                    <div class="interactive-controls">
                        <button class="control-btn" id="repeatBtn" title="Lặp lại bài này">🔁</button>
                        <button class="control-btn" id="heartBtn" title="Thêm vào danh sách yêu thích">♥</button>
                    </div>

                    <!-- TRÌNH PHÁT NHẠC AUDIO NEON -->
                    <div class="audio-wrapper">
                        <audio id="musicPlayer" controls autoplay crossorigin="anonymous">
                            <source src="<?php echo $song_music; ?>" type="audio/mpeg">
                        </audio>
                    </div>
                </div>

                <!-- VÙNG BÊN PHẢI: HIỂN THỊ THÔNG TIN CHI TIẾT BÀI HÁT -->
                <div class="player-right">
                    <h1 class="song-title-main"><?php echo $song_title; ?></h1>
                    <div class="song-artist-main">Ca sĩ: <?php echo $song_artist; ?></div>

                    <!-- Các thẻ phân loại nhạc -->
                    <div class="meta-tags">
                        <span class="tag tag-type">Loại nhạc: <?php echo $song_type; ?></span>
                        <span class="tag tag-date">Sáng tác: <?php echo $song_date; ?></span>
                    </div>

                    <!-- Ý nghĩa bài hát -->
                    <div class="info-section">
                        <div class="info-heading">Tiểu sử & Ý nghĩa bài hát</div>
                        <div class="info-body"><?php echo $song_story; ?></div>
                    </div>

                    <!-- Phương thức biểu đạt -->
                    <div class="info-section">
                        <div class="info-heading">Phong cách thể hiện của tác giả</div>
                        <div class="info-body"><?php echo $song_express; ?></div>
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
            title: "<?php echo $song_title; ?>",
            artist: "<?php echo $song_artist; ?>",
            img: "<?php echo $song_img; ?>",
            music: "<?php echo $song_music; ?>",
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

        // Chạy cài đặt âm thanh ngay khi bài nhạc bắt đầu cất lời ca
        player.addEventListener('play', () => {
            cdDisk.style.animationPlayState = 'running';
            
            // Trình duyệt bảo mật yêu cầu người dùng tương tác trước khi kích hoạt AudioContext
            if (!audioContext) {
                setupAudioContext();
            } else if (audioContext.state === 'suspended') {
                audioContext.resume();
            }

            // Ghi nhận lịch sử nghe nhạc tự động đưa vào trang lich-su.php
            let history = JSON.parse(localStorage.getItem('music_history')) || [];
            history = history.filter(item => item.title !== currentSong.title);
            history.unshift(currentSong);
            localStorage.setItem('music_history', JSON.stringify(history));
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

        // LOGIC THẢ TIM YÊU THÍCH (LƯU VÀO THƯ VIỆN SO-THICH.PHP)
        let favorites = JSON.parse(localStorage.getItem('music_favorites')) || [];
        if (favorites.some(item => item.title === currentSong.title)) {
            heartBtn.classList.add('active-heart');
        }

        heartBtn.onclick = function() {
            let favs = JSON.parse(localStorage.getItem('music_favorites')) || [];
            if (this.classList.contains('active-heart')) {
                favs = favs.filter(item => item.title !== currentSong.title);
                this.classList.remove('active-heart');
            } else {
                favs.unshift(currentSong);
                this.classList.add('active-heart');
            }
            localStorage.setItem('music_favorites', JSON.stringify(favs));
        };
                // Tìm đoạn player.addEventListener('play', ...) trong file player.php và sửa lại như sau:
        player.addEventListener('play', () => {
            cdDisk.style.animationPlayState = 'running';
            
            if (!audioContext) { setupAudioContext(); } 
            else if (audioContext.state === 'suspended') { audioContext.resume(); }

            // 1. GHI NHẬN LỊCH SỬ NGHE NHẠC (Đã làm ở bước trước)
            let history = JSON.parse(localStorage.getItem('music_history')) || [];
            history = history.filter(item => item.title !== currentSong.title);
            history.unshift(currentSong);
            localStorage.setItem('music_history', JSON.stringify(history));

            // 2. BỘ ĐẾM LƯỢT NGHE TỰ ĐỘNG ĐỂ LÀM BẢNG XẾP HẠNG (THÊM MỚI DÒNG NÀY)
            let views = JSON.parse(localStorage.getItem('music_views')) || {};
            // Nếu bài hát đã có lượt nghe thì cộng thêm 1, nếu chưa có thì đặt bằng 1
            if (views[currentSong.title]) {
                views[currentSong.title].count += 1;
            } else {
                views[currentSong.title] = {
                    title: currentSong.title,
                    artist: currentSong.artist,
                    img: currentSong.img,
                    music: currentSong.music,
                    count: 1
                };
            }
            localStorage.setItem('music_views', JSON.stringify(views));
        });

    </script>
</body>
</html>
