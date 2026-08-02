<?php
// 1. Kiểm tra quyền Admin (Chặn truy cập trái phép)
include 'check_admin.php';

// 2. Nhúng kết nối cơ sở dữ liệu
include __DIR__ . '/../database.php';

// 3. Kiểm tra xem có nhận được ID cần xóa hay không
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        // Sử dụng Prepared Statement để xóa dữ liệu an toàn chống SQL Injection
        $sql = "DELETE FROM songs WHERE id = :id";
        $stmt = $conn->prepare($sql);
        
        // Thực thi câu lệnh xóa
        $stmt->execute([':id' => $id]);
        
        // Xóa thành công, quay về trang dashboard kèm trạng thái (nếu muốn debug)
        header("Location: dashboard.php?status=success_delete");
        exit();

    } catch (PDOException $e) {
        // Nếu phát hiện lỗi hệ thống (ví dụ: ràng buộc khóa ngoại), dừng lại và thông báo
        die("Lỗi hệ thống, không thể xóa bản ghi: " . $e->getMessage());
    }
} else {
    // Nếu không có ID hoặc ID không hợp lệ, lập tức đẩy quay lại trang dashboard
    header("Location: dashboard.php?status=invalid_id");
    exit();
}
?>
