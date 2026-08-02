<?php
// 1. Ép buộc phải qua kiểm tra quyền admin mới được xem trang này
include 'check_admin.php'; 

// 2. Nhúng file kết nối database bằng đường dẫn tuyệt đối an toàn
include __DIR__ . '/../database.php'; 

// 3. Lấy toàn bộ danh sách nhạc từ cơ sở dữ liệu MySQL
try {
    $stmt = $conn->query("SELECT * FROM songs ORDER BY id DESC");
    $all_songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Lỗi truy vấn danh sách nhạc: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Quản Trị Hệ Thống - Admin Dashboard</title>
    <!-- Nhúng file CSS từ thư mục bên ngoài -->
    <link rel="stylesheet" href="../css/index.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e2e;
            color: #ffffff;
            margin: 0;
            padding: 20px;
            padding-top: 130px; /* Tránh bị thanh menu che khuất */
        }
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
            background: rgba(30, 30, 46, 0.8);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            border: 1px solid rgba(0, 242, 254, 0.1);
        }
        .top-bar-admin {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .admin-actions-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .btn-action {
            background: #00f2fe;
            color: #000;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
            font-size: 14px;
        }
        .btn-action:hover {
            box-shadow: 0 0 15px #00f2fe;
        }
        /* Nút phụ quản lý thành viên và duyệt VIP */
        .btn-secondary-admin {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid #ff007f;
            color: #ff007f;
            text-shadow: 0 0 5px rgba(255, 0, 127, 0.3);
        }
        .btn-secondary-admin:hover {
            background: #ff007f;
            color: #fff;
            box-shadow: 0 0 15px #ff007f;
        }
        .btn-logout {
            background: #ff007f;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }
        .btn-logout:hover {
            box-shadow: 0 0 15px #ff007f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(13,13,30,0.6);
            border-radius: 12px;
            overflow: hidden;
            margin-top: 20px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #333;
        }
        th {
            background-color: #252538;
            color: #00f2fe;
        }
        tr:hover {
            background: rgba(255,255,255,0.05);
        }
        .btn-edit { color: #00f2fe; text-decoration: none; margin-right: 10px; font-weight: bold; }
        .btn-delete { color: #ff007f; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<!-- 4. GỌI THANH MENU HOÀN CHỈNH CHO TRANG QUẢN TRỊ -->
<?php include __DIR__ . '/../header.php'; ?>

<div class="admin-container">
    <div class="top-bar-admin">
        <h1 style="margin: 0; color: #fff; font-size: 28px;">Quản Lý Bài Hát</h1>
        <div class="admin-actions-group">
            <!-- ĐÃ BỔ SUNG CÁC NÚT LIÊN KẾT ĐIỀU HƯỚNG QUẢN LÝ MỚI -->
            <a href="users_list.php" class="btn-action btn-secondary-admin">👤 Danh sách Thành viên</a>
            <a href="vip_manage.php" class="btn-action btn-secondary-admin">👑 Duyệt đơn VIP</a>
            <a href="destination_add.php" class="btn-action">Thêm bài hát mới</a>
            <a href="logout.php" class="btn-logout">Đăng xuất</a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên bài hát</th>
                <th>Ca sĩ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($all_songs)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: #606080; padding: 20px;">Chưa có bài hát nào trong cơ sở dữ liệu.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($all_songs as $song): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($song['id']); ?></strong></td>
                    <td><?php echo htmlspecialchars($song['title'] ?? 'Chưa rõ'); ?></td>
                    <td><?php echo htmlspecialchars($song['artist'] ?? 'Chưa rõ'); ?></td>
                    <td>
                        <a href="destination_edit.php?id=<?php echo $song['id']; ?>" class="btn-edit">Sửa</a>
                        <a href="destination_delete.php?id=<?php echo $song['id']; ?>" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa bài hát này?')">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
