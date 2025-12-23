<?php 
require_once '../includes/check_login.php'; 
require_once '../includes/db_config_sinhvien.php'; 

// Khởi tạo mảng lưu lỗi cho từng ô
$errors = [];

if (isset($_POST['them_sv'])) {
    // 1. NHẬN VÀ LÀM SẠCH DỮ LIỆU (Sanitization)
    // Dùng htmlspecialchars để ngăn chặn các thẻ script lạ
    $mssv        = htmlspecialchars(trim($_POST['mssv']));    
    $hoten       = htmlspecialchars(trim($_POST['fullname']));
    $ngaysinh    = $_POST['dob'];
    $gioitinh    = $_POST['gender'];
    $cccd        = htmlspecialchars(trim($_POST['cccd']));
    $email       = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL); // Làm sạch email
    $sdt         = htmlspecialchars(trim($_POST['phone']));
    $quequan     = htmlspecialchars(trim($_POST['hometown']));
    $truong      = isset($_POST['school']) ? htmlspecialchars(trim($_POST['school'])) : ''; 
    $sophong     = isset($_POST['sophong']) ? htmlspecialchars(trim($_POST['sophong'])) : '';
    $ngaybatdau  = $_POST['start_date'];
    // 1. Kiểm tra bắt buộc bằng PHP
    if (empty($hoten)) $errors['fullname'] = "Họ tên không được để trống!";
    if (empty($mssv)) $errors['mssv'] = "Mã sinh viên không được để trống!";
    if (empty($ngaysinh)) $errors['dob'] = "Ngày sinh không được để trống!";
    if (empty($cccd)) $errors['cccd'] = "CCCD không được để trống!";
    if (empty($sdt)) $errors['phone'] = "Số điện thoại không được để trống!";
    if (empty($truong)) $errors['school'] = "Trường không được để trống!";
    if (empty($sophong)) $errors['sophong'] = "Số phòng không được để trống!";
    if (empty($ngaybatdau)) $errors['start_date'] = "Ngày bắt đầu không được để trống!";

    // 2. Kiểm tra lỗi chính tả họ tên
    if (!empty($hoten)) {
        $hop_le = true;
        if (preg_match('/[0-9]/', $hoten)) {
            $hop_le = false;
        } else {
            $words = explode(' ', $hoten);
            foreach ($words as $w) {
                if (empty($w)) continue;
                $first = mb_substr($w, 0, 1, 'UTF-8');
                $rest  = mb_substr($w, 1, null, 'UTF-8');
                if (mb_strtoupper($first, 'UTF-8') !== $first || mb_strtolower($rest, 'UTF-8') !== $rest) {
                    $hop_le = false; break;
                }
            }
        }
        if (!$hop_le) $errors['fullname'] = "Họ tên sai định dạng!";
    }

    // 3. Kiểm tra trùng MSSV
    if (empty($errors)) {
        $check = $conn->prepare("SELECT mssv FROM sinhvien WHERE mssv = ?");
        $check->bind_param("s", $mssv);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $errors['mssv'] = "MSSV này đã tồn tại trên hệ thống!";
        }
        $check->close();
    }

    // 4. Thực hiện INSERT
    if (empty($errors)) {
        $sql = $conn->prepare("INSERT INTO sinhvien (mssv, hoten, ngaysinh, truong, sophong,gioitinh,cccd, email,sodienthoai,quequan,ngaybatdau) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $sql->bind_param("sssssssssss", $mssv, $hoten, $ngaysinh, $truong, $sophong, $gioitinh, $cccd, $email, $sdt, $quequan, $ngaybatdau);
 
        if ($sql->execute()) {
            $show_success_modal = true; // Kích hoạt biến hiển thị modal thành công
        } else {
            $errors['system'] = "Lỗi hệ thống: " . $conn->error;
        }
        $sql->close();
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
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { display: flex; flex-direction: column; margin-bottom: 15px; }
        .form-group label { font-weight: 600; color: #374151; font-size: 14px; margin-bottom: 6px; }
        .form-group input { padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px; outline: none; }
        .form-group input.is-invalid { border: 1px solid #ef4444 !important; }
        .error-text { color: #ef4444; font-size: 13px; margin-top: 5px; font-weight: 500; }
        .required { color: #ef4444; margin-left: 3px; }
        .actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; border-top: 1px solid #f3f4f6; padding-top: 20px; }
        .btn { padding: 10px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-primary { background: #2563eb; border: none; color: #fff; }
        .search-card h3 { font-size: 25px; margin-bottom: 20px; }
    </style>
</head>
<body>

<?php include '../includes/chung.php'; ?>

<main class="main">
    <div class="search-card">
        <h3>Thêm sinh viên mới</h3>
        <form id="studentForm" action="" method="POST" novalidate> <div class="form-grid">
                
                <div class="form-group">
                    <label>Họ và tên <span class="required">*</span></label>
                    <input type="text" name="fullname" placeholder="Nhập họ tên (Ví dụ: Nguyễn Văn A)" class="<?php echo isset($errors['fullname']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($hoten ?? ''); ?>">
                    <?php if (isset($errors['fullname'])): ?><div class="error-text"><?php echo $errors['fullname']; ?></div><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Mã sinh viên (MSSV) <span class="required">*</span></label>
                    <input type="text" name="mssv" placeholder="Nhập MSSV" class="<?php echo isset($errors['mssv']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($mssv ?? ''); ?>">
                    <?php if (isset($errors['mssv'])): ?><div class="error-text"><?php echo $errors['mssv']; ?></div><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Ngày sinh <span class="required">*</span></label>
                    <input type="date" name="dob" class="<?php echo isset($errors['dob']) ? 'is-invalid' : ''; ?>" value="<?php echo $ngaysinh ?? ''; ?>">
                    <?php if (isset($errors['dob'])): ?><div class="error-text"><?php echo $errors['dob']; ?></div><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Giới tính</label>
                    <div style="display:flex; gap:20px; padding-top:10px;">
                        <label><input type="radio" name="gender" value="Nam" <?php echo ($gioitinh ?? 'Nam') == 'Nam' ? 'checked' : ''; ?>> Nam</label>
                        <label><input type="radio" name="gender" value="Nữ" <?php echo ($gioitinh ?? '') == 'Nữ' ? 'checked' : ''; ?>> Nữ</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>CCCD <span class="required">*</span></label>
                    <input type="text" name="cccd" placeholder=" Nhập số CCCD" class="<?php echo isset($errors['cccd']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($cccd ?? ''); ?>">
                    <?php if (isset($errors['cccd'])): ?><div class="error-text"><?php echo $errors['cccd']; ?></div><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Nhập email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Số điện thoại <span class="required">*</span></label>
                    <input type="text" name="phone" placeholder="Nhập số điện thoại" class="<?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($sdt ?? ''); ?>">
                    <?php if (isset($errors['phone'])): ?><div class="error-text"><?php echo $errors['phone']; ?></div><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Quê quán</label>
                    <input type="text" name="hometown" placeholder=" Nhập quê quán" value="<?php echo htmlspecialchars($quequan ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Trường <span class="required">*</span></label>
                    <input type="text" name="school" placeholder=" Nhập tên trường" class="<?php echo isset($errors['school']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($truong ?? ''); ?>">
                    <?php if (isset($errors['school'])): ?><div class="error-text"><?php echo $errors['school']; ?></div><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Số phòng <span class="required">*</span></label>
                    <input type="text" name="sophong" placeholder=" Nhập số phòng" class="<?php echo isset($errors['sophong']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($sophong ?? ''); ?>">
                    <?php if (isset($errors['sophong'])): ?><div class="error-text"><?php echo $errors['sophong']; ?></div><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Ngày bắt đầu <span class="required">*</span></label>
                    <input type="date" name="start_date" class="<?php echo isset($errors['start_date']) ? 'is-invalid' : ''; ?>" value="<?php echo $ngaybatdau ?? ''; ?>">
                    <?php if (isset($errors['start_date'])): ?><div class="error-text"><?php echo $errors['start_date']; ?></div><?php endif; ?>
                </div>
            </div>

            <div class="actions">
                <button type="button" class="btn btn-cancel" onclick="window.location.href='giaodien.php'">Hủy</button>
                <button type="submit" name="them_sv" class="btn btn-primary">Thêm sinh viên</button>
            </div>
        </form>
    </div>
</main>
</body>
</html>