<?php
require_once '../includes/check_login.php';
require_once '../includes/db_config_sinhvien.php';

if (isset($_GET['id'])) {
    $mssv = $_GET['id'];
    
    // AC03: Kiểm tra ràng buộc (Ví dụ: kiểm tra sinh viên có đang trong hợp đồng không)
    // Ở đây ta thực hiện lệnh xóa trực tiếp, nếu DB có ràng buộc khóa ngoại nó sẽ báo lỗi
    $sql = "DELETE FROM sinhvien WHERE mssv = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $mssv);
    
    if ($stmt->execute()) {
        // AC04: Xóa sinh viên thành công
        header("Location: danhsachsv.php?msg=deleted");
    } else {
        // AC06: Xử lý lỗi hệ thống (ví dụ vi phạm ràng buộc dữ liệu)
        header("Location: danhsachsv.php?msg=error");
    }
    $stmt->close();
}
exit;
?>