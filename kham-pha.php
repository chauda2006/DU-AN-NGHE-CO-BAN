<?php
// BẮT BUỘC NẰM Ở DÒNG SỐ 1 - KHÔNG CÓ KHOẢNG TRẮNG PHÍA TRÊN
include 'header.php';
include 'database.php';

// Truy vấn lấy dữ liệu bài hát từ database cho JavaScript đọc
try {
    $stmt = $conn->query("SELECT id, title, artist, img, genre, release_date, story, expression FROM songs ORDER BY id DESC");
    $songs_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $songs_db = [];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khám Phá Âm Nhạc - MP3 Music Neon</title>
    
    <!-- Nhúng file CSS giao diện cấu trúc chính và nền lưới Cyberpunk -->
    <link rel="stylesheet" href="css/index.css">
</head>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khám Phá Âm Nhạc - MP3 Music Neon</title>
    
    <!-- Nhúng file CSS giao diện cấu trúc chính và nền lưới Cyberpunk -->
    <link rel="stylesheet" href="css/index.css">
    
    <style>
        /* ==========================================================================
           CSS BỘ LỌC VÀ KHÔNG GIAN TÌM KIẾM TRANG KHÁM PHÁ NEON
           ========================================================================== */
        
        /* Khung tìm kiếm High-tech */
        .search-box-wrapper {
            margin: 30px 0;
            position: relative;
            max-width: 500px;
        }

        .search-input {
            width: 100%;
            padding: 14px 20px 14px 45px;
            background: rgba(13, 13, 30, 0.6);
            border: 1px solid rgba(0, 242, 254, 0.3);
            border-radius: 30px;
            color: #ffffff;
            font-size: 16px;
            outline: none;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(0, 242, 254, 0.05);
        }

        .search-input:focus {
            border-color: var(--neon-cyan);
            box-shadow: 0 0 20px rgba(0, 242, 254, 0.25), inset 0 0 10px rgba(0, 242, 254, 0.1);
        }

        .search-icon-fixed {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--neon-cyan);
            font-size: 18px;
        }

        /* Khối các bộ lọc nhạc (Filter Tags) */
        .filter-container {
            margin-bottom: 35px;
            background: rgba(13, 13, 30, 0.4);
            border: 1px solid rgba(0, 242, 254, 0.1);
            padding: 25px;
            border-radius: 16px;
            backdrop-filter: blur(10px);
        }

        .filter-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .filter-label {
            font-size: 13px;
            text-transform: uppercase;
            color: #606080;
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* Nút bấm bộ lọc phát sáng Neon */
        .filter-btn {
            background: rgba(20, 20, 45, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #a0a0c0;
            padding: 8px 18px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .filter-btn:hover {
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
        }

        /* Trạng thái nút lọc đang được chọn (Active) */
        .filter-btn.active {
            background: rgba(0, 242, 254, 0.1);
            color: var(--neon-cyan);
            border-color: var(--neon-cyan);
            text-shadow: 0 0 5px rgba(0, 242, 254, 0.5);
            box-shadow: 0 0 15px rgba(0, 242, 254, 0.2);
        }

        /* Đổi màu riêng cho bộ lọc Cảm xúc (Vui/Buồn/Tình cảm) */
        .filter-group-mood .filter-btn.active {
            background: rgba(255, 0, 127, 0.1);
            color: var(--neon-pink);
            border-color: var(--neon-pink);
            text-shadow: 0 0 5px rgba(255, 0, 127, 0.5);
            box-shadow: 0 0 15px rgba(255, 0, 127, 0.2);
        }

        .no-results {
            text-align: center;
            padding: 50px;
            color: #606080;
            font-size: 16px;
            border: 1px dashed rgba(255,255,255,0.05);
            border-radius: 12px;
            grid-column: 1 / -1;
            background: rgba(10, 10, 26, 0.5);
        }
    </style>
</head>
<body>

    <!-- 1. GỌI THANH MENU NGANG NEON VÀ VIDEO NỀN DÙNG CHUNG TRÊN ĐỈNH ĐẦU -->
    <?php include 'header.php'; ?>

    <!-- 2. KHỐI NỘI DUNG CHÍNH TRANG KHÁM PHÁ -->
    <div class="main-content" style="padding-top: 100px;">
        <h1 class="welcome-title">KHÁM PHÁ KHO NHẠC</h1>
        <p style="color: #a0a0c0; margin-top: 5px;">Tìm kiếm bài hát theo sở thích, tâm trạng và phong cách âm nhạc của riêng bạn.</p>

        <!-- THANH TÌM KIẾM LIVE KÝ TỰ BIỂU TƯỢNG -->
        <div class="search-box-wrapper">
            <span class="search-icon-fixed">⌕</span>
            <input type="text" id="searchInput" class="search-input" placeholder="Tìm tên bài hát hoặc ca sĩ...">
        </div>

        <!-- KHU VỰC CÁC BỘ LỌC PHÂN LOẠI THÔNG MINH -->
        <div class="filter-container">
            <!-- Nhóm 1: Địa lý / Nguồn gốc -->
            <div class="filter-label">🌐 Phạm vi địa lý</div>
            <div class="filter-group" id="filterGeo">
                <button class="filter-btn active" data-geo="all">Tất cả quốc gia</button>
                <button class="filter-btn" data-geo="Trong nước">Nhạc Trong nước</button>
                <button class="filter-btn" data-geo="Nước ngoài">Nhạc Quốc tế</button>
            </div>

            <!-- Nhóm 2: Thể loại / Dòng nhạc -->
            <div class="filter-label">🎸 Thể loại & Phong cách</div>
            <div class="filter-group" id="filterStyle">
                <button class="filter-btn active" data-style="all">Tất cả dòng nhạc</button>
                <button class="filter-btn" data-style="Pop">Pop</button>
                <button class="filter-btn" data-style="V-Pop">V-Pop</button>
                <button class="filter-btn" data-style="K-Pop">K-Pop</button>
                <button class="filter-btn" data-style="Ballad">Ballad</button>
            </div>

            <!-- Nhóm 3: Tâm trạng / Cảm xúc -->
            <div class="filter-label">❤️ Cảm xúc & Tâm trạng</div>
            <div class="filter-group filter-group-mood" id="filterMood">
                <button class="filter-btn active" data-mood="all">Mọi tâm trạng</button>
                <button class="filter-btn" data-mood="Tình cảm">Tình cảm</button>
                <button class="filter-btn" data-mood="Vui vẻ">Vui vẻ bùng nổ</button>
                <button class="filter-btn" data-mood="Buồn">Sâu lắng / Buồn</button>
            </div>
        </div>

        <!-- LƯỚI KẾT QUẢ BÀI HÁT SAU KHI LỌC -->
        <h2 class="section-title">Kết quả tìm kiếm</h2>
        <div class="song-grid" id="exploreGridView" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 25px;">
            <!-- JavaScript tự động đổ dữ liệu bài hát thật vào đây -->
        </div>

    </div>


       <!-- ==========================================================================
         3. BỘ NÃO JAVASCRIPT ĐIỀU KHIỂN BỘ LỌC TÌM KIẾM ĐỘNG (FULL CHỨC NĂNG)
         ========================================================================== -->
        <script>
        // 1. NHẬN DỮ LIỆU ĐỘNG TỪ MYSQL SANG CHO MẢNG CHÍNH ĐỂ XỬ LÝ TỐC ĐỘ CAO
        const musicDatabase = <?php echo json_encode($songs_db); ?>;

        // 2. BIẾN LƯU TRỮ TRẠNG THÁI TOÀN BỘ CÁC BỘ LỌC ĐANG CHỌN HIỆN TẠI
        let currentFilters = { 
            search: '', 
            geo: 'all', 
            style: 'all', 
            mood: 'all' 
        };

        // 3. ĐỊNH VỊ CÁC THÀNH PHẦN ĐIỀU KHIỂN GIAO DIỆN CHÍNH
        const exploreGridView = document.getElementById('exploreGridView');
        const searchInput = document.getElementById('searchInput');

        // 4. HÀM TỰ ĐỘNG PHÂN TÍCH LỌC VÀ KẾT XUẤT RA GIAO DIỆN CHUẨN ĐỒNG BỘ ID
        function filterAndRenderMusic() {
            // Thực hiện quét và lọc mảng bài hát gốc theo tất cả các điều kiện đang chọn cùng lúc
            let filteredSongs = musicDatabase.filter(song => {
                
                // Điều kiện A: Kiểm tra từ khóa ô tìm kiếm live (Theo tên bài hát hoặc tên ca sĩ)
                const keyword = currentFilters.search.toLowerCase().trim();
                const matchesSearch = song.title.toLowerCase().includes(keyword) ||
                                      song.artist.toLowerCase().includes(keyword);

                // Điều kiện B: Kiểm tra bộ lọc phạm vi địa lý (Trong nước / Nước ngoài)
                let matchesGeo = true;
                if (currentFilters.geo !== 'all') {
                    matchesGeo = song.genre.includes(currentFilters.geo);
                }

                // Điều kiện C: Kiểm tra bộ lọc thể loại dòng nhạc chuẩn (Phân tách Pop khỏi K-Pop/V-Pop)
                let matchesStyle = true;
                if (currentFilters.style !== 'all') {
                    if (currentFilters.style === 'Pop') {
                        // Nếu tìm nhạc Pop, bắt buộc chuỗi genre phải bắt đầu bằng chữ Pop (không chứa chữ K- hay V- phía trước)
                        matchesStyle = song.genre.startsWith('Pop');
                    } else {
                        // Các dòng nhạc khác như V-Pop, K-Pop, Ballad thì giữ nguyên kiểm tra bình thường
                        matchesStyle = song.genre.toLowerCase().includes(currentFilters.style.toLowerCase());
                    }
                }

                // Điều kiện D: Kiểm tra bộ lọc cảm xúc tâm trạng dựa theo phân tích từ khóa văn bản tiểu sử
                let matchesMood = true;
                if (currentFilters.mood !== 'all') {
                    // Gộp toàn bộ chuỗi văn bản hoàn cảnh sáng tác và phong cách thể hiện để rà soát từ khóa tâm trạng
                    const contentLog = ((song.story || "") + " " + (song.expression || "")).toLowerCase();
                    
                    if (currentFilters.mood === 'Buồn') {
                        matchesMood = contentLog.includes('buồn') || contentLog.includes('ballad') || contentLog.includes('tiếc nuối') || contentLog.includes('day dứt') || contentLog.includes('sâu lắng') || contentLog.includes('nhẹ nhàng');
                    } else if (currentFilters.mood === 'Vui vẻ') {
                        matchesMood = contentLog.includes('sôi động') || contentLog.includes('bùng nổ') || contentLog.includes('vui') || contentLog.includes('thời thượng') || contentLog.includes('điện tử');
                    } else if (currentFilters.mood === 'Tình cảm') {
                        matchesMood = contentLog.includes('yêu') || contentLog.includes('tình yêu') || contentLog.includes('ngọt ngào') || contentLog.includes('chinh phục') || contentLog.includes('tim');
                    }
                }

                // Trả về kết quả giao nhau của cả 4 bộ lọc
                return matchesSearch && matchesGeo && matchesStyle && matchesMood;
            });

            // 5. TIẾN HÀNH IN KẾT QUẢ RA GIAO DIỆN MÀN HÌNH LƯỚI
            if (filteredSongs.length === 0) {
                exploreGridView.innerHTML = `
                    <div style="color: #606080; grid-column: 1/-1; text-align: center; padding: 40px 0; font-size: 15px; border: 1px dashed rgba(255,255,255,0.05); border-radius: 12px;">
                        🔍 Không tìm thấy bài hát nào phù hợp với từ khóa hoặc bộ lọc đã chọn.
                    </div>`;
                return;
            }

            let html = '';
            filteredSongs.forEach(song => {
                // ĐỒNG BỘ ĐƯỜNG DẪN ID AN TOÀN: Khi click mở đúng bài hát bám chuẩn theo cơ sở dữ liệu động MySQL
                html += `
                    <div class="song-card" onclick="window.location.href='player.php?id=${song.id}'" style="cursor: pointer;">
                        <div class="song-thumb-wrapper">
                            <img src="${song.img}" class="song-img" alt="${song.title}">
                        </div>
                        <div class="song-name">${song.title}</div>
                        <div class="song-artist">${song.artist}</div>
                    </div>
                `;
            });
            exploreGridView.innerHTML = html;
        }

        // ==========================================================================
        // 6. KHỐI LẮNG NGHE CÁC SỰ KIỆN TƯƠNG TÁC TỪ NGƯỜI DÙNG
        // ==========================================================================

        // Hành động A: Gõ phím trên thanh tìm kiếm Live-Search
        searchInput.addEventListener('input', (e) => {
            currentFilters.search = e.target.value;
            filterAndRenderMusic();
        });

        // Hành động B: Bấm chọn bộ lọc Phạm vi địa lý
        document.querySelectorAll('#filterGeo .filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelector('#filterGeo .filter-btn.active').classList.remove('active');
                this.classList.add('active');
                currentFilters.geo = this.getAttribute('data-geo');
                filterAndRenderMusic();
            });
        });

        // Hành động C: Bấm chọn bộ lọc Thể loại dòng nhạc
        document.querySelectorAll('#filterStyle .filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelector('#filterStyle .filter-btn.active').classList.remove('active');
                this.classList.add('active');
                currentFilters.style = this.getAttribute('data-style');
                filterAndRenderMusic();
            });
        });

        // Hành động D: Bấm chọn bộ lọc Cảm xúc tâm trạng
        document.querySelectorAll('#filterMood .filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelector('#filterMood .filter-btn.active').classList.remove('active');
                this.classList.add('active');
                currentFilters.mood = this.getAttribute('data-mood');
                filterAndRenderMusic();
            });
        });

        // 7. TỰ ĐỘNG KHỞI CHẠY QUÉT VÀ HIỂN THỊ KHO NHẠC NGAY KHI VỪA TẢI XONG TRANG
        document.addEventListener('DOMContentLoaded', () => {
            filterAndRenderMusic();
        });
    </script>
</body>
</html>
