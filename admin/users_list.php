<?php
// 1. Ép buộc phải qua kiểm tra quyền quản trị admin mới được xem trang này
include 'check_admin.php'; 

// 2. Nhúng file kết nối database bằng đường dẫn tuyệt đối an toàn
include __DIR__ . '/../database.php'; 

// 3. Truy vấn lấy toàn bộ thông tin thành viên từ bảng users trong database MySQL
try {
    $stmt = $conn->query("SELECT id, username, full_name, email, birthday, status FROM users ORDER BY id DESC");
    $all_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu hệ thống: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Thành Viên - Admin Panel</title>
    <!-- Nhúng file CSS từ thư mục bên ngoài -->
    <link rel="stylesheet" href="../css/index.css">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #1e1e2e; 
            color: #fff; 
            margin: 0; 
            padding: 20px;
            padding-top: 130px; /* Tránh bị thanh menu đỉnh đầu đè lên */
        }
        .admin-container { 
            max-width: 1100px; 
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
            border-bottom: 1px solid #333;
            padding-bottom: 15px;
        }
        .top-bar-admin h2 {
            margin: 0;
            color: #00f2fe;
            text-shadow: 0 0 8px rgba(0, 242, 254, 0.3);
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            background: rgba(13,13,30,0.6); 
            border-radius: 12px;
            overflow: hidden;
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
        /* Định dạng nhãn cấp độ thành viên phát sáng */
        .badge-status { 
            padding: 6px 12px; 
            border-radius: 20px; 
            font-size: 12px; 
            font-weight: bold; 
            display: inline-block;
        }
        .badge-regular { 
            background: rgba(255,255,255,0.08); 
            color: #bbb; 
            border: 1px solid #444;
        }
        .badge-vip { 
            background: rgba(255, 0, 127, 0.15); 
            color: #ff007f; 
            border: 1px solid #ff007f; 
            text-shadow: 0 0 8px #ff007f; 
            box-shadow: 0 0 8px rgba(255, 0, 127, 0.2);
        }
    </style>
</head>
<body>

<!-- 4. GỌI THANH MENU HOÀN CHỈNH CHO TRANG QUẢN TRỊ -->
<?php include __DIR__ . '/../header.php'; ?>

<div class="admin-container">
    <div class="top-bar-admin">
        <h2>Danh Sách Người Dùng Đăng Ký Hệ Thống</h2>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tài khoản</th>
                <th>Họ và Tên</th>
                <th>Email liên hệ</th>
                <th>Ngày sinh</th>
                <th>Cấp độ tài khoản</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($all_users)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #606080; padding: 30px;">Chưa có người dùng nào đăng ký tài khoản trên hệ thống.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($all_users as $user): ?>
                <tr>
                    <td><strong><?= $user['id'] ?></strong></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['full_name']) ?></td>
                    <td><?= htmlspecialchars($user['email'] ?? 'Chưa cập nhật') ?></td>
                    <td>
                        <?php 
                            if (!empty($user['birthday'])) {
                                // Định dạng ngày sinh về dạng Ngày-Tháng-Năm cho dễ đọc
                                echo htmlspecialchars(date("d/m/Y", strtotime($user['birthday']))); 
                            } else {
                                echo 'Chưa cập nhật';
                            }
                        ?>
                    </td>
                    <td>
                        <?php if (($user['status'] ?? 'Thường') === 'VIP'): ?>
                            <span class="badge-status badge-vip">👑 THÀNH VIÊN VIP</span>
                        <?php else: ?>
                            <span class="badge-status badge-regular">👤 TÀI KHOẢN THƯỜNG</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
