<?php 
require_once '../includes/check_login.php'; 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Ký túc xá Hướng Dương</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<?php include '../includes/chung.php'; ?>

<main class="main">
    <div class="header" style="display: flex; justify-content: flex-end; align-items: center; padding: 10px 20px;">
        <div class="user-greeting" style="background-color: #2563eb; color: white; padding: 8px 20px; border-radius: 20px; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <i class="fa-solid fa-user"></i> 
            Xin chào, <?php echo isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'Nhân viên'; ?>
        </div>
    </div>

    <section class="cards">
        <div class="card blue"><h3>Phòng đang trống</h3><p>15</p></div>
        <div class="card purple"><h3>Hợp đồng hiệu lực</h3><p>124</p></div>
        <div class="card orange"><h3>Yêu cầu hỗ trợ mới</h3><p>5</p></div>
        <div class="card green"><h3>Hóa đơn cần thu</h3><p>29</p></div>
    </section>

    <section class="table-box">
        <h3 style="margin-bottom:15px;">Danh sách sinh viên mới</h3>
        <table>
            <thead>
                <tr>
                    <th>MSV</th>
                    <th>Họ tên</th>
                    <th>Phòng</th>
                    <th>Ngày bắt đầu</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Kết nối database
            $conn = mysqli_connect("localhost", "root", "", "quanlysinhvien");
            
            if ($conn) {
                mysqli_set_charset($conn, "utf8");

                // Truy vấn 5 sinh viên mới nhất (sử dụng tên cột mssv, hoten, sophong, ngaybatdau theo dữ liệu của bạn)
                $sql = "SELECT mssv, hoten, sophong, ngaybatdau FROM sinhvien ORDER BY ngaybatdau DESC LIMIT 5";
                $result = mysqli_query($conn, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['mssv']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['hoten']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['sophong']) . "</td>";
                        echo "<td>" . date("d/m/Y", strtotime($row['ngaybatdau'])) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center;'>Chưa có dữ liệu sinh viên.</td></tr>";
                }
                
                // Đóng kết nối ngay để tránh treo sidebar
                mysqli_close($conn);
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>Lỗi kết nối database.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>
</main>

<script src="../assets/app.js"></script>
</body>
</html>
