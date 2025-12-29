<?php 
require_once '../includes/check_login.php'; 
require_once '../includes/db_config_sinhvien.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Thêm code xử lý lỗi hệ thống bằng try-catch
    try {
        $mahopdong = $_POST['contract_code'];
        $mssv = $_POST['mssv'];
        
        // Thêm code định dạng họ tên viết hoa chữ cái đầu
        $hoten = mb_convert_case(trim($_POST['fullname']), MB_CASE_TITLE, "UTF-8");
        
        $sophong = $_POST['room_number'];
        $ngaybatdau = $_POST['start_date'];
        $ngayketthuc = $_POST['end_date'];
        $tienphong = $_POST['room_price'];
        $trangthai = "Còn hiệu lực";

        $check_sv = $conn->prepare("SELECT mssv FROM sinhvien WHERE mssv = ?");
        $check_sv->bind_param("s", $mssv);
        $check_sv->execute();
        
        if ($check_sv->get_result()->num_rows == 0) {
            $stmt_sv = $conn->prepare("INSERT INTO sinhvien (mssv, hoten) VALUES (?, ?)");
            $stmt_sv->bind_param("ss", $mssv, $hoten);
            $stmt_sv->execute();
        }

        $stmt_hd = $conn->prepare($sql_hd);
        $stmt_hd->bind_param("ssssssis", $mahopdong, $mssv, $hoten, $sophong, $ngaybatdau, $ngayketthuc, $tienphong, $trangthai);

        $systemError = "Lỗi hệ thống: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lập hợp đồng - KTX Hướng Dương</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .section-title { grid-column: 1 / -1; font-size: 15px; font-weight: 1000; color: #ea1313ff; text-transform: uppercase; margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 20px; }
        .section-title:first-of-type { border-top: none; padding-top: 0; margin-top: 0; }
        .form-group { display: flex; flex-direction: column; gap: 6px; position: relative; padding-bottom: 25px; } 
        .form-group label { font-size: 14px; font-weight: 600; color: #374151; }
        .form-group input { padding: 11px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; outline: none; transition: 0.3s; }
        
        /* Định dạng dòng chữ đỏ báo lỗi */
        .error-text { color: #ef4444; font-size: 12px; position: absolute; bottom: 4px; left: 2px; display: none; font-weight: 500; }
        .form-group.has-error input { border-color: #ef4444 !important; background-color: #ffffff !important; }
        .form-group.has-error .error-text { display: block; }

        .actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 25px; grid-column: 1 / -1; }
        .btn { padding: 10px 24px; border-radius: 8px; font-weight: 600; font-size: 15px; cursor: pointer; }
        .btn-primary { background: #2563eb; color: #fff; border: none; }
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); display: none; align-items: center; justify-content: center; z-index: 9999; }
        .modal-box { background: white; border-radius: 20px; padding: 30px; text-align: center; width: 90%; max-width: 380px; }
        .system-error-alert { background-color: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f87171; }
    </style>
</head>
<body>

<?php include '../includes/chung.php'; ?>

<main class="main">
    <div class="header"><h2>Lập hợp đồng sinh viên</h2></div>

    <?php if ($systemError): ?>
        <div class="system-error-alert"><?php echo $systemError; ?></div>
    <?php endif; ?>

    <div class="search-card">
        <form id="contractForm" action="" method="POST" novalidate>
            <div class="form-grid">
                <div class="section-title">THÔNG TIN SINH VIÊN</div>

                <div class="form-group">
                    <label>Họ và tên *</label>
                    <input type="text" name="fullname" id="fullname" placeholder="Ví dụ: Nguyễn Văn An">
                    <span class="error-text">Họ tên không được để trống!</span>
                </div>

                <div class="form-group">
                    <label>Mã sinh viên (MSSV) *</label>
                    <input type="text" name="mssv" id="mssv" placeholder="Ví dụ: SV001">
                    <span class="error-text">Định dạng MSSV không đúng (Ví dụ: SV001)!</span>
                </div>

                <div class="form-group">
                    <label>Số điện thoại *</label>
                    <input type="text" name="phone" id="phone" placeholder="Nhập số điện thoại">
                    <span class="error-text">Số điện thoại phải là 10 chữ số!</span>
                </div>

                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" id="email" placeholder="Nhập email">
                    <span class="error-text">Định dạng email không đúng!</span>
                </div>

                <div class="section-title">THÔNG TIN HỢP ĐỒNG</div>

                <div class="form-group">
                    <label>Mã hợp đồng *</label>
                    <input type="text" name="contract_code" id="contract_code" placeholder="Ví dụ: HĐ-2025100">
                    <span class="error-text">Định dạng sai (Ví dụ: HĐ-2025100)!</span>
                </div>

                <div class="form-group">
                    <label>Số phòng *</label>
                    <input type="text" name="room_number" id="room_number" placeholder="Ví dụ: A102 hoặc B103">
                    <span class="error-text">Định dạng sai (Ví dụ: A102, B103)!</span>
                </div>

                <div class="form-group">
                    <label>Ngày bắt đầu *</label>
                    <input type="date" name="start_date" id="start_date">
                    <span class="error-text">Vui lòng chọn ngày bắt đầu!</span>
                </div>

                <div class="form-group">
                    <label>Ngày kết thúc *</label>
                    <input type="date" name="end_date" id="end_date">
                    <span class="error-text">Vui lòng chọn ngày kết thúc!</span>
                </div>

                <div class="form-group">
                    <label>Tiền phòng (VNĐ) *</label>
                    <input type="number" name="room_price" id="room_price" value="1500000" readonly style="background-color: #f3f4f6; cursor: not-allowed;">
                    <span class="error-text">Vui lòng nhập tiền phòng!</span>
                </div>    
</body>
</html>                