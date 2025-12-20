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
    <div class="header">
        <h2>Tổng quan</h2>
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
        </table>
    </section>

</main>

<script src="../assets/app.js"></script>
</body>
</html>
