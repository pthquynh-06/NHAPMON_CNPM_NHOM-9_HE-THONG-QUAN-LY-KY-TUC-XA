            
            } else {
                if ($sodien > 0 && $sonuoc > 0) {
                    $phidichvu = 50000; 
                    $ngaytao = date('Y-m-d');
                    $sql = "INSERT INTO hoadon (mahoadon, sophong, thang, nam, sodien, sonuoc, phidichvu, ngaytao, trangthai) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Chưa thanh toán')";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssddds", $mahd, $sophong, $month, $year, $sodien, $sonuoc, $phidichvu, $ngaytao);
                    if ($stmt->execute()) { 
                        $showSuccessModal = true; 
                        $next_mahd = getNextMaHD($conn);
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lập hóa đơn - KTX Hướng Dương</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .search-card { padding-top: 5px !important; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .section-title { grid-column: 1 / -1; font-size: 16px; font-weight: 700; color: #ef4444; text-transform: uppercase; margin-top: 15px; }
        .section-separator { border-top: 1px solid #eee; padding-top: 20px; margin-top: 10px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; position: relative; padding-bottom: 22px; }
        .form-group label { font-size: 14px; font-weight: 600; color: #4b5563; }
        .form-group input { padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; outline: none; }
        
        .month-container { position: relative; width: 100%; }
        .month-display { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 14px; color: #374151; pointer-events: none; z-index: 1; }
        .input-month-fix { position: relative; background: transparent !important; z-index: 2; color: transparent !important; border: 1px solid #d1d5db; border-radius: 8px; padding: 12px; width: 100%; cursor: pointer; }
        .input-month-fix::-webkit-calendar-picker-indicator { background: transparent; position: absolute; inset: 0; width: auto; height: auto; cursor: pointer; }
        .calendar-icon { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #6b7280; z-index: 1; }

        input[readonly] { background-color: #f9fafb; color: #6b7280; cursor: not-allowed; border-style: dashed; }
        .summary-container { grid-column: 1 / -1; display: flex; flex-direction: column; align-items: center; margin-top: 10px; gap: 20px; }
        .total-box { width: 100%; max-width: 400px; background: #f0f9ff; padding: 15px 25px; border-radius: 12px; border: 1px solid #bae6fd; display: flex; justify-content: space-between; align-items: center; }
        .total-amount { color: #0369a1; font-weight: 800; font-size: 20px; }
        
        .actions-center { display: flex; justify-content: center; gap: 15px; }
        .btn { padding: 12px 40px; border-radius: 8px; font-weight: 700; cursor: pointer; border: none; font-size: 15px; }
        .btn-cancel { background: #f3f4f6; color: #4b5563; }
        .btn-primary { background: #2563eb; color: #fff; }

        .error-text { color: #ef4444; font-size: 12px; font-weight: 500; position: absolute; bottom: 0; display: none; width: 100%; }
        .has-error input { border-color: #ef4444 !important; background-color: #ffffff; }
        .has-error .error-text { display: block; }

        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 9999; }
        .modal-box { background: white; padding: 30px; border-radius: 20px; width: 350px; text-align: center; }
    </style>
</head>
<body>
<?php include '../includes/chung.php'; ?>
<main class="main">
    <div class="header"><h2>Lập hóa đơn dịch vụ</h2></div>
    <div class="search-card">
        <form id="invoiceForm" method="POST" novalidate>
            <div class="form-grid">
                <div class="section-title">Thông tin chung</div>
                <div class="form-group">
                    <label>Mã hóa đơn</label>
                    <input type="text" name="invoice_code" value="<?= $next_mahd ?>" readonly>
                </div>

                <div class="form-group <?= $errorMsg ? 'has-error' : '' ?>">
                    <label>Số phòng</label>
                    <input type="text" name="room_number" id="room_number" placeholder="Ví dụ: A101" value="<?= isset($_POST['room_number']) ? htmlspecialchars($_POST['room_number']) : '' ?>">
                    <span class="error-text" id="room-error"><?= $errorMsg ? $errorMsg : 'Vui lòng nhập lại' ?></span>
                </div>

                <div class="form-group">
                    <label>Tháng thanh toán</label>
                    <div class="month-container">
                        <div class="month-display" id="monthText">--/----</div>
                        <i class="fa-regular fa-calendar calendar-icon"></i>
                        <input type="month" name="billing_month" id="billing_month" class="input-month-fix">
                    </div>
                    <span class="error-text">Vui lòng nhập lại!</span>
                </div>

                <div class="section-title section-separator">Chi tiết chi phí tiêu thụ</div>
                <div class="form-group">
                    <label>Số điện tiêu thụ (kWh)</label>
                    <input type="number" name="sodien" id="sodien" class="calc-input" placeholder="0" min="1">
                    <span class="error-text">Vui lòng nhập lại</span>
                </div>
                <div class="form-group">
                    <label>Số nước tiêu thụ (m3)</label>
                    <input type="number" name="sonuoc" id="sonuoc" class="calc-input" placeholder="0" min="1">
                    <span class="error-text">Vui lòng nhập lại</span>
                </div>

                <div class="section-title section-separator">Chi phí cố định</div>
                <div class="form-group"><label>Tiền phòng (VNĐ)</label><input type="text" value="1.500.000" readonly></div>
                <div class="form-group"><label>Phí dịch vụ khác (VNĐ)</label><input type="text" value="50.000" readonly></div>

                <div class="summary-container">
                    <div class="total-box">
                        <span>Tổng cộng:</span>
                        <span class="total-amount" id="displayTotal">1.550.000 VNĐ</span>
                    </div>
                    <div class="actions-center">
                        <button type="button" class="btn btn-cancel" onclick="window.location.href='danhsachhoadon.php'">Hủy</button>
                        <button type="button" class="btn btn-primary" onclick="validateAndSubmit()">Lập hóa đơn</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<div id="successModal" class="modal-overlay" <?php if($showSuccessModal) echo 'style="display:flex;"'; ?>>
    <div class="modal-box">
        <div style="color: #22c55e; font-size: 50px; margin-bottom: 10px;"><i class="fa-solid fa-circle-check"></i></div>
        <h3 style="margin-bottom: 10px;">Tạo hóa đơn thành công</h3>
        <button class="btn btn-primary" style="width:100%" onclick="location.href=location.pathname">Tiếp tục lập hóa đơn</button>
    </div>
</div>

<script>
    const monthInput = document.getElementById('billing_month');
    const monthText = document.getElementById('monthText');
    monthInput.addEventListener('input', function() {
        if (this.value) {
            const [y, m] = this.value.split('-');
            monthText.innerText = m + '/' + y;
        }
    });

    function calculate() {
        const d = parseFloat(document.getElementById('sodien').value) || 0;
        const n = parseFloat(document.getElementById('sonuoc').value) || 0;
        const total = (d * 2800) + (n * 11000) + 1550000;
        document.getElementById('displayTotal').innerText = total.toLocaleString('vi-VN') + ' VNĐ';
    }
    document.querySelectorAll('.calc-input').forEach(i => i.addEventListener('input', calculate));

    function validateAndSubmit() {
        let isValid = true;
        ['room_number', 'billing_month', 'sodien', 'sonuoc'].forEach(id => {
            const el = document.getElementById(id);
            const parent = el.closest('.form-group');
            
            if (id !== 'room_number' || !parent.classList.contains('has-error')) {
                parent.classList.remove('has-error');
            }

            if(!el.value || (el.type === 'number' && parseFloat(el.value) <= 0)) {
                parent.classList.add('has-error');
                if (id === 'room_number' && !el.value) {
                    document.getElementById('room-error').innerText = 'Vui lòng nhập lại';
                }
                isValid = false;
            }
        });
        if(isValid) document.getElementById('invoiceForm').submit();
    }
</script>
</body>
</html>