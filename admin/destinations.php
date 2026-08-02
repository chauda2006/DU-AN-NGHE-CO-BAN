<?php
// Nhúng kết nối cơ sở dữ liệu (lùi 1 cấp ra thư mục gốc)
include __DIR__ . '/../database.php';

// Khởi tạo từ khóa tìm kiếm nếu có
$search = trim($_GET['search'] ?? '');

try {
    // Nếu có từ khóa tìm kiếm, thực hiện tìm kiếm an toàn qua Prepared Statement
    if (!empty($search)) {
        $sql = "SELECT * FROM songs WHERE title LIKE :search OR artist LIKE :search ORDER BY id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':search' => '%' . $search . '%']);
    } else {
        // Nếu không tìm kiếm, hiển thị toàn bộ danh sách
        $stmt = $conn->query("SELECT * FROM songs ORDER BY id DESC");
    }
    $all_destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi kết nối hoặc truy vấn dữ liệu: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Điểm Đến / Bài Hát</title>
    <!-- Nhúng file CSS dùng chung -->
    <link rel="stylesheet" href="../css/index.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e2e;
            color: #ffffff;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background: rgba(37, 37, 56, 0.9);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        h2 {
            color: #00f2fe;
            margin: 0;
        }
        .search-box {
            display: flex;
            gap: 10px;
        }
        .search-box input {
            padding: 8px 15px;
            border: 1px solid #444;
            border-radius: 6px;
            background: #13131e;
            color: #fff;
            font-size: 14px;
        }
        .btn-search {
            background: #00f2fe;
            color: #000;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background: #13131e;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 20px;
            transition: 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            border-color: #00f2fe;
            box-shadow: 0 5px 15px rgba(0, 242, 254, 0.2);
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #00f2fe;
            margin-bottom: 10px;
        }
        .card-artist {
            color: #bbb;
            font-size: 14px;
        }
        .no-data {
            text-align: center;
            color: #aaa;
            padding: 30px;
            grid-column: 1 / -1;
        }
        .btn-panel {
            background: #ff007f;
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-section">
        <h2>Khám Phá Danh Sách</h2>
        <a href="dashboard.php" class="btn-panel">Vào trang Admin</a>
    </div>

    <!-- Thanh tìm kiếm dữ liệu -->
    <form action="destinations.php" method="GET" class="search-box">
        <input type="text" name="search" placeholder="Nhập tên bài hát hoặc ca sĩ..." 
               value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn-search">Tìm kiếm</button>
    </form>

    <!-- Hiển thị danh sách dạng Card lưới trực quan -->
    <div class="grid-container">
        <?php if (empty($all_destinations)): ?>
            <div class="no-data">Không tìm thấy kết quả phù hợp hoặc danh sách trống.</div>
        <?php else: ?>
            <?php foreach ($all_destinations as $item): ?>
                <div class="card">
                    <div class="card-title"><?php echo htmlspecialchars($item['title']); ?></div>
                    <div class="card-artist">Người đăng/Ca sĩ: <?php echo htmlspecialchars($item['artist']); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
