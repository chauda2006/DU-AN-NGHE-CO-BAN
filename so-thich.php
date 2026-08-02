<?php
// 1. Nhúng thanh header dùng chung và kết nối cơ sở dữ liệu
include 'header.php';
include 'database.php';

// 2. Lấy danh sách các bài hát đã được thả tim từ LocalStorage thông qua mảng ID
// (Vì dự án cơ bản chưa có bảng users riêng biệt, lưu danh sách yêu thích bằng LocalStorage là tối ưu nhất)
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thư Viện Sở Thích - MP3 MUSIC</title>
    <link rel="stylesheet" href="css/index.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; color: #fff; margin: 0; padding-top: 100px; }
        .favorite-container { max-width: 1100px; margin: 0 auto; padding: 20px; display: grid; grid-template-columns: 1.8fr 1.2fr; gap: 40px; }
        .section-box { background: rgba(20, 20, 35, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(0, 242, 254, 0.1); padding: 25px; border-radius: 16px; }
        .box-title { font-size: 20px; font-weight: bold; color: #ff007f; text-shadow: 0 0 10px rgba(255, 0, 127, 0.4); margin-top: 0; margin-bottom: 20px; text-transform: uppercase; }
        .recommend-title { color: #00f2fe !important; text-shadow: 0 0 10px rgba(0, 242, 254, 0.4) !important; }
        
        /* Danh sách bài hát yêu thích dạng dòng phẳng */
        .fav-list { display: flex; flex-direction: column; gap: 12px; }
        .fav-item { display: flex; align-items: center; background: rgba(13, 13, 30, 0.6); padding: 12px 15px; border-radius: 10px; border: 1px solid #222; }
        .fav-img { width: 50px; height: 50px; object-fit: cover; border-radius: 6px; margin-right: 15px; }
        .fav-info { flex: 1; }
        .fav-title { font-weight: bold; font-size: 16px; }
        .fav-artist { color: #aaa; font-size: 13px; margin-top: 3px; }
        
        /* Các nút tương tác nhanh */
        .btn-listen { background: #00f2fe; color: #000; padding: 6px 14px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 13px; margin-right: 10px; }
        .btn-remove-fav { background: none; border: none; color: #ff007f; font-size: 18px; cursor: pointer; }
        
        /* Gợi ý bài hát bên cột phải */
        .rec-item { display: flex; align-items: center; padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.05); margin-bottom: 5px; }
        .rec-info { flex: 1; margin-left: 12px; }
        .rec-tag { font-size: 10px; background: rgba(0, 242, 254, 0.1); color: #00f2fe; border: 1px solid #00f2fe; padding: 1px 5px; border-radius: 3px; }
        .empty-hint { text-align: center; color: #666; padding: 30px 0; font-size: 14px; }
    </style>
</head>
<body>

<div class="favorite-container">
    <!-- CỘT BÊN TRÁI: DANH SÁCH BÀI HÁT BẠN ĐÃ THẢ TIM -->
    <div class="section-box">
        <h2 class="box-title">❤️ Danh sách bài hát yêu thích</h2>
        <div class="fav-list" id="favListArea">
            <!-- Dữ liệu bài hát thả tim sẽ tự động chèn bằng JavaScript -->
        </div>
    </div>

    <!-- CỘT BÊN PHẢI: KHỐI TỰ ĐỘNG ĐỀ XUẤT THỂ LOẠI NHẠC PHÙ HỢP -->
    <div class="section-box">
        <h2 class="box-title recommend-title">✨ Đề xuất riêng cho gu nhạc của bạn</h2>
        <div id="recommendArea">
            <!-- Hệ thống tự phân tích thể loại nhạc bạn thích và gợi ý tại đây -->
        </div>
    </div>
</div>

<!-- ĐOẠN TRUYỀN DỮ LIỆU TẤT CẢ BÀI HÁT TỪ DATABASE SANG JAVASCRIPT ĐỂ PHÂN TÍCH LIVE -->
<?php
try {
    $stmt = $conn->query("SELECT id, title, artist, img, genre FROM songs");
    $db_songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $db_songs = [];
}
?>
<script>
    // Nạp mảng danh sách bài hát từ hệ thống MySQL sang cho JS xử lý tốc độ cao
    const allDbSongs = <?php echo json_encode($db_songs); ?>;

    // Hàm load và render danh sách yêu thích + đề xuất thể loại nhạc
    function refreshFavoritePage() {
        const favIds = JSON.parse(localStorage.getItem('my_favorites')) || [];
        const favListArea = document.getElementById('favListArea');
        const recommendArea = document.getElementById('recommendArea');

        // Lọc ra danh sách các đối tượng bài hát bạn đã thả tim dựa theo mảng ID
        const myFavSongs = allDbSongs.filter(song => favIds.includes(parseInt(song.id)));

        // 1. HIỂN THỊ DANH SÁCH BÀI HÁT YÊU THÍCH
        if (myFavSongs.length === 0) {
            favListArea.innerHTML = `<div class="empty-hint">Danh sách trống. Hãy ra trang chủ và bấm nghe bài hát để trải nghiệm tính năng!</div>`;
            recommendArea.innerHTML = `<div class="empty-hint">Thả tim tối thiểu 1 bài hát để hệ thống phân tích và đề xuất thể loại nhạc phù hợp nhé!</div>`;
            return;
        }

        let favHtml = '';
        myFavSongs.forEach(song => {
            favHtml += `
                <div class="fav-item">
                    <img src="${song.img}" class="fav-img">
                    <div class="fav-info">
                        <div class="fav-title">${song.title}</div>
                        <div class="fav-artist">${song.artist} <span style="color:#666; font-size:11px;">(${song.genre})</span></div>
                    </div>
                    <a href="player.php?id=${song.id}" class="btn-listen">▶ Nghe</a>
                    <button class="btn-remove-fav" onclick="removeFavorite(${song.id})">♥</button>
                </div>
            `;
        });
        favListArea.innerHTML = favHtml;

        // 2. PHÂN TÍCH GU ÂM NHẠC ĐỂ ĐỀ XUẤT THỂ LOẠI
        // Đếm xem bạn đang thích thể loại nào nhiều nhất
        let genreCounts = {};
        myFavSongs.forEach(song => {
            genreCounts[song.genre] = (genreCounts[song.genre] || 0) + 1;
        });

        // Tìm thể loại nhạc được thả tim nhiều nhất (Gu chính)
        let favoriteGenre = '';
        let maxCount = 0;
        for (let genre in genreCounts) {
            if (genreCounts[genre] > maxCount) {
                maxCount = genreCounts[genre];
                favoriteGenre = genre;
            }
        }

        // Lọc ra các bài hát có CÙNG THỂ LOẠI đó trong database nhưng bạn CHƯA thả tim
        const recommendedSongs = allDbSongs.filter(song => song.genre === favoriteGenre && !favIds.includes(parseInt(song.id)));

        if (recommendedSongs.length === 0) {
            recommendArea.innerHTML = `<div class="empty-hint">Tuyệt vời! Bạn đã nghe và thả tim hết toàn bộ các ca khúc thuộc nhóm thể loại [${favoriteGenre}] của chúng tôi rồi!</div>`;
        } else {
            let recHtml = `<p style="font-size:13px; color:#aaa; margin-top:0; margin-bottom:15px;">Hệ thống nhận thấy bạn thích nhóm nhạc <b>${favoriteGenre}</b>. Thử nghe thêm các bài này xem sao:</p>`;
            
            // Lấy tối đa 4 bài gợi ý để giao diện gọn gàng
            recommendedSongs.slice(0, 4).forEach(song => {
                recHtml += `
                    <div class="rec-item">
                        <img src="${song.img}" style="width:40px; height:40px; object-fit:cover; border-radius:4px;">
                        <div class="rec-info">
                            <div style="font-weight:bold; font-size:14px;">${song.title}</div>
                            <div style="color:#aaa; font-size:12px; margin-top:2px;">${song.artist}</div>
                        </div>
                        <span class="rec-tag">Cùng thể loại</span>
                        <a href="player.php?id=${song.id}" class="btn-listen" style="padding:4px 10px; margin-left:10px; font-size:12px;">▶</a>
                    </div>
                `;
            });
            recommendArea.innerHTML = recHtml;
        }
    }

    // Hàm xử lý bỏ thả tim ngay tại trang Sở thích
    function removeFavorite(id) {
        let favIds = JSON.parse(localStorage.getItem('my_favorites')) || [];
        favIds = favIds.filter(favId => favId !== id);
        localStorage.setItem('my_favorites', JSON.stringify(favIds));
        refreshFavoritePage(); // Nạp lại trang ngay lập tức không cần tải lại trình duyệt
    }

    // Khởi chạy nạp giao diện khi trang web tải xong
    document.addEventListener('DOMContentLoaded', refreshFavoritePage);
</script>
</body>
</html>
