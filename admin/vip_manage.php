<?php
include 'check_admin.php';
include __DIR__ . '/../database.php';

// Xử lý khi Admin nhấn nút Duyệt hoặc Từ chối
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action']; // 'approve' hoặc 'reject'

    try {
        // Lấy thông tin yêu cầu để tìm tên tài khoản người dùng tương ứng
        $stmt = $conn->prepare("SELECT username FROM vip_requests WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($request) {
            $username = $request['username'];
            
            if ($action === 'approve') {
                // 1. Cập nhật trạng thái yêu cầu thành Đã duyệt
                $u1 = $conn->prepare("UPDATE vip_requests SET status = 'Đã duyệt' WHERE id = :id");
                $u1->execute([':id' => $id]);
                
                // 2. Nâng cấp trạng thái tài khoản người dùng lên hạng VIP
                $u2 = $conn->prepare("UPDATE users SET status = 'VIP' WHERE username = :username");
                $u2->execute([':username' => $username]);
            } elseif ($action === 'reject') {
                // Cập nhật trạng thái yêu cầu thành Từ chối
                $u1 = $conn->prepare("UPDATE vip_requests SET status = 'Từ chối' WHERE id = :id");
                $u1->execute([':id' => $id]);
            }
            header("Location: vip_manage.php");
            exit();
        }
    } catch (PDOException $e) {
        die("Lỗi hệ thống: " . $e->getMessage());
    }
}

// Tải danh sách yêu cầu chờ duyệt lên bảng quản trị
try {
    $list_stmt = $conn->query("SELECT * FROM vip_requests ORDER BY id DESC");
    $requests = $list_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi tải danh sách: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Duyệt VIP - Admin Panel</title>
    <link rel="stylesheet" href="../css/index.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #1e1e2e; color: #fff; margin: 0; padding-top: 100px; }
        .container { max-width: 1100px; margin: 0 auto; padding: 30px; background: rgba(30, 30, 46, 0.8); border-radius: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: rgba(13,13,30,0.6); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #333; }
        th { background-color: #252538; color: #00f2fe; }
        .btn-approve { color: #00f2fe; text-decoration: none; font-weight: bold; margin-right: 15px; }
        .btn-reject { color: #ff007f; text-decoration: none; font-weight: bold; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .status-pending { background: rgba(255, 165, 0, 0.2); color: orange; border: 1px solid orange; }
        .status-approved { background: rgba(0, 242, 254, 0.2); color: #00f2fe; border: 1px solid #00f2fe; }
        .status-rejected { background: rgba(255, 0, 127, 0.2); color: #ff007f; border: 1px solid #ff007f; }
    </style>
</head>
<body>

<?php include __DIR__ . '/../header.php'; ?>

<div class="container">
    <h2>Quản Lý Yêu Cầu Nâng Cấp Gói VIP</h2>
    <table>
        <thead>
            <tr>
                <th>Tài khoản gửi</th>
                <th>Gói đăng ký</th>
                <th>Giá tiền</th>
                <th>Thời gian</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($requests)): ?>
                <tr><td colspan="6" style="text-align: center;">Chưa có yêu cầu nâng cấp nào được gửi lên.</td></tr>
            <?php else: ?>
                <?php foreach ($requests as $req): ?>
                <tr>
                    <td><?= htmlspecialchars($req['username']) ?></td>
                    <td><?= htmlspecialchars($req['package_name']) ?></td>
                    <td><?= number_format($req['price']) ?>đ</td>
                    <td><?= $req['created_at'] ?></td>
                    <td>
                        <?php if ($req['status'] === 'Chờ phê duyệt'): ?>
                            <span class="status-badge status-pending">Chờ duyệt</span>
                        <?php elseif ($req['status'] === 'Đã duyệt'): ?>
                            <span class="status-badge status-approved">Đã duyệt</span>
                        <?php else: ?>
                            <span class="status-badge status-rejected">Từ chối</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($req['status'] === 'Chờ phê duyệt'): ?>
                            <a href="vip_manage.php?action=approve&id=<?= $req['id'] ?>" class="btn-approve" onclick="return confirm('Xác nhận kích hoạt quyền VIP cho tài khoản này?')">✔️ Duyệt</a>
                            <a href="vip_manage.php?action=reject&id=<?= $req['id'] ?>" class="btn-reject" onclick="return confirm('Từ chối yêu cầu đăng ký này?')">❌ Từ chối</a>
                        <?php else: ?>
                            <span style="color:#666;">Hết hiệu lực</span>
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
