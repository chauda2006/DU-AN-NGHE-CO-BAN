<?php
// 1. Nhúng thanh header dùng chung và kết nối cơ sở dữ liệu
include 'header.php';
include 'database.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch Sử Nghe Nhạc - MP3 MUSIC</title>
    <link rel="stylesheet" href="css/index.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; color: #fff; margin: 0; padding-top: 100px; }
        .history-container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .section-box { background: rgba(20, 20, 35, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(0, 242, 254, 0.1); padding: 30px; border-radius: 16px; }
        .box-title { font-size: 22px; font-weight: bold; color: #00f2fe; text-shadow: 0 0 10px rgba(0, 242, 254, 0.4); margin-top: 0; margin-bottom: 5px; text-transform: uppercase; }
        .box-subtitle { color: #606080; font-size: 13px; margin-bottom: 25px; }
        
        /* Danh sách lịch sử dạng dòng thời gian timeline */
        .history-list { display: flex; flex-direction: column; gap: 15px; position: relative; }
        .history-item { display: flex; align-items: center; background: rgba(13, 13, 30, 0.6); padding: 15px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: 0.3s; }
        .history-item:hover { border-color: #00f2fe; box-shadow: 0 0 12px rgba(0, 242, 254, 0.2); transform: translateX(3px); }
        .hist-img { width: 55px; height: 55px; object-fit: cover; border-radius: 8px; margin-right: 20px; }
        .hist-info { flex: 1; }
        .hist-title { font-weight: bold; font-size: 17px; color: #fff; }
        .hist-artist { color: #aaa; font-size: 14px; margin-top: 4px; }
        
        /* Cột hiển thị mốc thời gian và hành động */
        .hist-meta { text-align: right; margin-left: 20px; }
        .hist-time { font-size: 12px; color: #ff007f; text-shadow: 0 0 5px rgba(255,0,127,0.3); font-weight: bold; margin-bottom: 8px; }
        .btn-re-listen { display: inline-block; background: #00f2fe; color: #000; padding: 5px 15px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 13px; transition: 0.3s; }
        .btn-re-listen:hover { box-shadow: 0 0 10px #00f2fe; }
        
        /* Nút xóa lịch sử */
        .top-action-bar { display: flex; justify-content: space-between; align-items: center; }
        .btn-clear-history { background: none; border: 1px solid #ff007f; color: #ff007f; padding: 6px 15px; border-radius: 20px; font-weight: bold; cursor: pointer; font-size: 13px; transition: 0.3s; }
        .btn-clear-history:hover { background: #ff007f; color: #fff; box-shadow: 0 0 10px #ff007f; }
        .empty-hint { text-align: center; color: #606080; padding: 40px 0; font-size: 15px; }
    </style>
</head>
<body>

<div class="history-container">
    <div class="section-box">
        <div class="top-action-bar">
            <div>
                <h2 class="box-title">⏳ Lịch sử nghe nhạc gần đây</h2>
                <div class="box-subtitle">Danh sách các bài hát bạn đã thưởng thức theo trục thời gian thực.</div>
            </div>
            <button class="btn-clear-history" onclick="clearAllHistory()">Xóa toàn bộ lịch sử</button>
        </div>

        <div class="history-list" id="historyArea">
            <!-- Dữ liệu danh sách lịch sử nghe nhạc chèn tự động bằng JavaScript -->
        </div>
    </div>
</div>

<!-- NẠP MẢNG DỮ LIỆU TỪ MYSQL SANG JAVASCRIPT ĐỂ SO KHỚP ID HỒ SƠ BÀI HÁT -->
<?php
try {
    $stmt = $conn->query("SELECT id, title, artist, img FROM songs");
    $db_songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $db_songs = [];
}
?>
<script>
    const allDbSongs = <?php echo json_encode($db_songs); ?>;

    // Hàm render giao diện lịch sử nghe nhạc
    function renderHistoryPage() {
        const historyArea = document.getElementById('historyArea');
        // Đọc danh sách lịch sử dạng mảng object: [{id: 1, time: '14:32 - 02/08'}, ...]
        const listenHistory = JSON.parse(localStorage.getItem('listen_history')) || [];

        if (listenHistory.length === 0) {
            historyArea.innerHTML = `<div class="empty-hint">Bạn chưa nghe ca khúc nào gần đây. Hãy chọn một giai điệu tại trang chủ để bắt đầu!</div>`;
            return;
        }

        let html = '';
        // Duyệt qua từng bản ghi lịch sử (Bài mới nghe xếp lên đầu)
        listenHistory.forEach(item => {
            // So khớp tìm kiếm thông tin chi tiết bài hát trong mảng database bằng ID
            const songInfo = allDbSongs.find(song => parseInt(song.id) === parseInt(item.id));
            
            if (songInfo) {
                html += `
                    <div class="history-item">
                        <img src="${songInfo.img}" class="hist-img" alt="${songInfo.title}">
                        <div class="hist-info">
                            <div class="hist-title">${songInfo.title}</div>
                            <div class="hist-artist">${songInfo.artist}</div>
                        </div>
                        <div class="hist-meta">
                            <div class="hist-time">⏱️ ${item.time}</div>
                            <a href="player.php?id=${songInfo.id}" class="btn-re-listen">Nghe lại</a>
                        </div>
                    </div>
                `;
            }
        });

        historyArea.innerHTML = html || `<div class="empty-hint">Dữ liệu các bài hát cũ đã bị Admin xóa khỏi cơ sở dữ liệu hệ thống.</div>`;
    }

    // Hàm xóa sạch vết lịch sử nghe nhạc công cộng
    function clearAllHistory() {
        if (confirm("Bạn có chắc chắn muốn xóa toàn bộ danh sách lịch sử nghe nhạc gần đây không?")) {
            localStorage.removeItem('listen_history');
            renderHistoryPage();
        }
    }

    // Khởi chạy đồng bộ trang khi tải xong DOM
    document.addEventListener('DOMContentLoaded', renderHistoryPage);
</script>
</body>
</html>
