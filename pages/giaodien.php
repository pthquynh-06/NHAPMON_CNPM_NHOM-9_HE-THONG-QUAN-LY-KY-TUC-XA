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

    <div class="header">
        <h2>Tổng quan</h2>
        <div class="user-greeting" style="background-color: #2563eb; color: white; padding: 8px 15px; border-radius: 20px; font-size: 14px;">
             Xin chào, <?php echo isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'Khách'; ?></div>
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
                    <th>Mã SV</th>
                    <th>Họ tên</th>
                    <th>Lớp</th>
                    <th>Ngày thêm</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>SV003</td><td>Ngô Minh C</td><td>CNTT-01</td><td>12/01/2025</td></tr>
                <tr><td>SV004</td><td>Hoàng Văn D</td><td>CNTT-02</td><td>30/11/2025</td></tr>
                <tr><td>SV005</td><td>Lê Thị E</td><td>SpTin2B</td><td>30/11/2025</td></tr>
            </tbody>
        </table>
    </section>

</main>

<script src="../assets/app.js"></script>
</body>
</html>
