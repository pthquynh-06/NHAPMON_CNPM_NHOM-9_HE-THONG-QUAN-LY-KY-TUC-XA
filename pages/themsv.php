<?php 
// 1. DUY TRÌ ĐĂNG NHẬP (Nếu file này cũng báo lỗi include, hãy dùng cách fix đường dẫn bên dưới)
require_once dirname(__DIR__) . '/includes/check_login.php'; 

// 2. CÁCH FIX ĐƯỜNG DẪN CHÍNH XÁC TUYỆT ĐỐI
// dirname(__DIR__) sẽ lấy thư mục cha của thư mục hiện tại (tức là ra khỏi folder pages)
$config_path = dirname(__DIR__) . '/includes/db_config_sinhvien.php';

if (file_exists($config_path)) {
    include $config_path;
} else {
    die("Lỗi: Không tìm thấy file cấu hình tại: " . $config_path);
}

$message = "";
$status = "";

// 3. XỬ LÝ KHI NGƯỜI DÙNG SUBMIT FORM
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Làm sạch dữ liệu
    $ho_ten    = mysqli_real_escape_string($conn, $_POST['fullname']);
    $ma_sv     = mysqli_real_escape_string($conn, $_POST['mssv']);
    $ngay_sinh = $_POST['dob'];
    $gioi_tinh = $_POST['gender'];
    $cccd      = mysqli_real_escape_string($conn, $_POST['cccd']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $phone     = mysqli_real_escape_string($conn, $_POST['phone']);
    $que_quan  = mysqli_real_escape_string($conn, $_POST['hometown']);
    $ngay_bd   = $_POST['start_date'];

    // Kiểm tra trùng mã sinh viên
    $check = mysqli_query($conn, "SELECT ma_sv FROM sinhvien WHERE ma_sv = '$ma_sv'");
    
    if (mysqli_num_rows($check) > 0) {
        $message = "Lỗi: Mã sinh viên này đã tồn tại!";
        $status = "error";
    } else {
        // Thực hiện thêm mới (Hãy đảm bảo tên database trong file config là đúng)
        $sql = "INSERT INTO sinhvien (ma_sv, ho_ten, ngay_sinh, gioi_tinh, cccd, email, so_dien_thoai, que_quan, ngay_bat_dau) 
                VALUES ('$ma_sv', '$ho_ten', '$ngay_sinh', '$gioi_tinh', '$cccd', '$email', '$phone', '$que_quan', '$ngay_bd')";

        if (mysqli_query($conn, $sql)) {
            $message = "Thêm sinh viên thành công!";
            $status = "success";
        } else {
            $message = "Lỗi SQL: " . mysqli_error($conn);
            $status = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sinh viên - KTX Hướng Dương</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 10px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 15px; }
        .form-group label { font-weight: 600; color: #374151; font-size: 14px; }
        .form-group input { padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px; outline: none; }
        .form-group input:focus { border-color: #2563eb; }
        .gender-wrap { display: flex; gap: 20px; padding-top: 5px; }
        .gender-wrap label { font-weight: normal; cursor: pointer; display: flex; align-items: center; gap: 5px; }
        .actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #f3f4f6; }
        .btn { padding: 10px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px; transition: 0.3s; }
        .btn-cancel { background: #fff; border: 1px solid #d1d5db; color: #4b5563; }
        .btn-primary { background: #2563eb; border: none; color: #fff; }
        
        /* Style thông báo */
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; text-align: center; }
        .alert-success { background-color: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
        .alert-error { background-color: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
    </style>
</head>
<body>

<?php 
// Sử dụng cùng logic đường dẫn cho file chung.php
include dirname(__DIR__) . '/includes/chung.php'; 
?>

<main class="main">
    <div class="header">
        <h2>Thêm mới sinh viên</h2>
        <div class="user-greeting" style="background-color: #2563eb; color: white; padding: 8px 15px; border-radius: 20px; font-size: 14px;"> 
            Xin chào, <?php echo isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'Khách'; ?>
        </div>
    </div>

    <div class="search-card">
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $status; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form id="studentForm" action="" method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Họ và tên</label>
                    <input type="text" name="fullname" placeholder="Nhập đầy đủ họ tên" required>
                </div>
                <div class="form-group">
                    <label>Mã sinh viên</label>
                    <input type="text" name="mssv" placeholder="Nhập MSSV" required>
                </div>
                <div class="form-group">
                    <label>Ngày sinh</label>
                    <input type="date" name="dob">
                </div>
                <div class="form-group">
                    <label>Giới tính</label>
                    <div class="gender-wrap">
                        <label><input type="radio" name="gender" value="Nam" checked> Nam</label>
                        <label><input type="radio" name="gender" value="Nữ"> Nữ</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>CCCD</label>
                    <input type="text" name="cccd" placeholder="Số CCCD">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Địa chỉ email">
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" placeholder="Số điện thoại">
                </div>
                <div class="form-group">
                    <label>Quê quán</label>
                    <input type="text" name="hometown" placeholder="Quê quán">
                </div>
                <div class="form-group">
                    <label>Ngày bắt đầu</label>
                    <input type="date" name="start_date">
                </div>
            </div>

            <div class="actions">
                <button type="button" class="btn btn-cancel" onclick="window.location.href='giaodien.php'">Hủy</button>
                <button type="submit" class="btn btn-primary">Thêm sinh viên</button>
            </div>
        </form>
    </div>
</main>

<script src="../assets/app.js"></script>
</body>
</html>