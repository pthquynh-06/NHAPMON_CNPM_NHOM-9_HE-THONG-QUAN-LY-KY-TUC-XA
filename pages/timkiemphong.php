<?php
// timkiemphong.php - Xử lý logic tìm kiếm dữ liệu

function getRoomData($conn, $search) {
    $result = false;
    if (!empty($search)) {
        // Truy vấn tìm kiếm có tham số để bảo mật
        $sql = "SELECT * FROM phong WHERE sophong LIKE ? OR trangthai LIKE ? ORDER BY sophong ASC";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $searchTerm = "%$search%";
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();
        }
    } else {
        // Truy vấn mặc định lấy toàn bộ danh sách
        $sql = "SELECT * FROM phong ORDER BY sophong ASC";
        $result = mysqli_query($conn, $sql);
    }
    return $result;
}
?>