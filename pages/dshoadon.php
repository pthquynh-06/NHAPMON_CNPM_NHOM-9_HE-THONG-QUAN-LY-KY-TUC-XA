<?php 
require_once '../includes/check_login.php'; 
require_once '../includes/db_config_sinhvien.php'; 

// --- XỬ LÝ CẬP NHẬT TRẠNG THÁI (Giữ nguyên logic cũ) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_status') {
    $mahd = $_POST['mahoadon'];
    $tt = $_POST['trangthai'];

    $stmt_update = $conn->prepare("UPDATE hoadon SET trangthai = ?, sodien = ?, sonuoc = ? WHERE mahoadon = ?");
    $stmt_update->bind_param("ssis", $_POST['trangthai'], $_POST['sodien'], $_POST['sonuoc'], $_POST['mahoadon']);

    if ($stmt_update->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit; 
}

if (!isset($conn) || $conn->connect_error) { die("system_error"); }
if(!isset($_SESSION['loggedin'])){
    header("Location: ../quanlynguoidung/dangnhaphethong.php");
    exit;
}

// --- LOGIC TÌM KIẾM & TỰ ĐỘNG TÍNH QUÁ HẠN ---
$search = $_GET['search'] ?? ''; 
$sql_select = "SELECT *, 
               CASE 
                   WHEN trangthai = 'Chưa thanh toán' AND DATEDIFF(NOW(), ngaytao) > 7 THEN 'Đã quá hạn'
                   ELSE trangthai 
               END AS trangthai_ao,
               DATE_ADD(ngaytao, INTERVAL 7 DAY) AS han_thanhtoan
               FROM hoadon ";

if (!empty($search)) {
    $sql_base = $sql_select . " WHERE mahoadon LIKE ? OR sophong LIKE ? ORDER BY mahoadon ASC";
    $stmt = $conn->prepare($sql_base);
    $searchTerm = "%$search%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = mysqli_query($conn, $sql_select . " ORDER BY mahoadon ASC");
}

$total_rows = mysqli_num_rows($result);
?>
               
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách hóa đơn - KTX Hướng Dương</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/style.css"> 
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap');
        .main { font-family: 'Be Vietnam Pro', sans-serif; background-color: #f8faff; padding: 30px; }
        .table-container-card { background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03); margin-top: 20px; }
        .invoice-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .invoice-table th { padding: 18px 10px; border-bottom: 2px solid #d1dae4; color: #818694; text-transform: uppercase; text-align: left; background: #fafbfc; }
        .invoice-table td { padding: 15px 10px; border-bottom: 1px solid #f1f5f9; color: #2e3a59; vertical-align: middle; }
        .badge { padding: 5px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; white-space: nowrap; }
        .status-paid { background: #dcfce7; color: #166534; }
        .status-unpaid { background: #fee2e2; color: #991b1b; }
        .status-overdue { background: #fef3c7; color: #92400e; border: 1px solid #f59e0b; }
        .price-text { font-weight: 600; color: #1e2640; }
        .action-icon {margin-right: 10px;}
        .action-icon:hover { transform: scale(1.2); }
        .search-btn { min-width: 130px; height: 45px; background: #2563eb; color: white; border: none; border-radius: 25px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .btn-clear { text-decoration: none; padding: 0 20px; height: 45px; background: #f1f5f9; color: #475569; border-radius: 25px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 5px; transition: 0.2s; flex-shrink: 0; }
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 9999; }
        .modal-box { background: white; padding: 30px; border-radius: 16px; width: 90%; max-width: 600px; animation: hienLen 0.25s ease-out; }
        .confirm-box { max-width: 400px; text-align: center; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 20px; }
        .form-group { display: flex; flex-direction: column; text-align: left; }
        .form-group label { font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 5px; }
        .form-group input { padding: 12px; border: 1.4px solid #d1dae4; border-radius: 8px; background: #f8fafc; cursor: not-allowed; }
        .form-group select { padding: 12px; border: 1.4px solid #2563eb; border-radius: 8px; cursor: pointer; }
        .btn-group { display: flex; gap: 10px; margin-top: 25px; justify-content: flex-end; }
        .btn { padding: 12px 25px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; }
        .btn-cancel { background: #f3f4f6; color: #374151; }
        .btn-confirm { background: #2563eb; color: white; }
        @keyframes hienLen { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
        .error-msg { color: #ef4444; font-size: 11px; margin-top: 4px; display: none; font-weight: 500; }
        .input-invalid { border-color: #ef4444 !important; background-color: #fef2f2 !important; }
    </style>
</head>
<body>

<?php include '../includes/chung.php'; ?>

<main class="main">
    <div class="header"><h2>Quản lý hóa đơn điện nước</h2></div>
    
    <div class="table-container-card">
        <div style="margin-bottom: 25px;">
            <form method="GET" action="" style="display: flex; gap: 12px; align-items: center;">
                <div style="position: relative; flex: 1;">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm theo mã hóa đơn hoặc số phòng..." 
                        style="width: 100%; height: 45px; padding: 0 15px 0 45px; border-radius: 25px; border: 1.4px solid #d1dae4; outline: none;">
                </div>
                <button type="submit" class="search-btn">Tìm kiếm</button>
                <?php if(!empty($search)): ?>
                    <a href="?" class="btn-clear"><i class="fa-solid fa-rotate-left"></i> Hủy lọc</a>
                <?php endif; ?>
            </form>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Mã HĐ</th>
                    <th>Phòng</th>
                    <th>Kỳ HĐ</th>
                    <th style="text-align: center;">Hạn thanh toán</th> 
                    <th>Điện/Nước</th>
                    <th>Tổng cộng</th>
                    <th>Trạng thái</th>
                    <th style="text-align: center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if($total_rows > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): 
                        $status_hien_thi = $row['trangthai_ao'];
                        $status_class = 'status-unpaid';
                        if ($status_hien_thi == 'Đã thanh toán') $status_class = 'status-paid';
                        if ($status_hien_thi == 'Đã quá hạn') $status_class = 'status-overdue';
                        
                        $tong_tien_hien_thi = ($row['sodien'] * 11000) + ($row['sonuoc'] * 2800) + $row['phidichvu'] + 1500000;
                    ?>
                    <tr id="row-<?php echo $row['mahoadon']; ?>" data-json='<?php echo json_encode($row); ?>'>
                        <td style="font-weight: 700; color: #2563eb;"><?php echo $row['mahoadon']; ?></td>
                        <td style="font-weight: 600;"><?php echo $row['sophong']; ?></td>
                        <td><?php echo $row['thang'].'/'.$row['nam']; ?></td>
                        <td style="color: #ef4444; font-weight: 600; text-align: center;">
                            <?php echo date('d/m/Y', strtotime($row['han_thanhtoan'])); ?>
                        </td>
                        <td>⚡<?php echo $row['sodien']; ?> | 💧<?php echo $row['sonuoc']; ?></td>
                        <td class="price-text"><?php echo number_format($tong_tien_hien_thi, 0, ',', '.'); ?>đ</td>
                        <td><span class="badge <?php echo $status_class; ?>"><?php echo $status_hien_thi; ?></span></td>
                        <td style="text-align: center;">
                            <i class="fa-solid fa-eye action-icon" style="color: #64748b;" onclick="openViewModal('<?php echo $row['mahoadon']; ?>')"></i>
                        </td>
                    </tr>  
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" style="text-align: center; padding: 50px; color: #64748b;">Không tìm thấy dữ liệu</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<div id="viewModal" class="modal-overlay">
    <div class="modal-box">
        <h3>Chi tiết hóa đơn</h3>
        <form id="updateStatusForm">
            <input type="hidden" name="action" value="update_status">
            <div class="form-grid">
                <div class="form-group"><label>Mã hóa đơn</label><input type="text" name="mahoadon" id="view-ma" readonly></div>
                <div class="form-group"><label>Số phòng</label><input type="text" id="view-phong" readonly></div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Hạn thanh toán (Sau 7 ngày tạo)</label>
                    <input type="text" id="view-han-tt" readonly style="font-weight: 700; color: #ef4444; background: #fff1f2;">
                </div>

                <div class="form-group"><label>Số điện (kWh)</label><input type="number" name="sodien" id="view-dien" style="background: #fff; border-color: #2563eb;"><span id="error-dien" class="error-msg">Sai định dạng</span></div>
                <div class="form-group"><label>Số nước (m3)</label><input type="number" name="sonuoc" id="view-nuoc" style="background: #fff; border-color: #2563eb;"><span id="error-nuoc" class="error-msg">Sai định dạng</span></div>
                
                <div class="form-group"><label>Phí dịch vụ</label><input type="text" id="view-dv" data-raw-dv="0" readonly></div>
                <div class="form-group"><label>Tiền phòng</label><input type="text" value="1.500.000" readonly></div>
                
                <div class="form-group" style="grid-column: span 2;"><label>Tổng tiền</label><input type="text" id="view-tong" readonly style="font-weight: 700; color: #166534; background: #f0fdf4;"></div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Trạng thái</label>
                    <select name="trangthai" id="view-trangthai">
                        <option value="Chưa thanh toán">Chưa thanh toán</option>
                        <option value="Đã thanh toán">Đã thanh toán</option>
                        <option value="Đã quá hạn">Đã quá hạn</option>
                    </select>
                </div>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-cancel" onclick="closeModal('viewModal')">Đóng</button>
                <button type="submit" class="btn btn-confirm">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<div id="alertModal" class="modal-overlay"><div class="modal-box confirm-box"><div id="alertIcon" style="font-size: 70px; margin-bottom: 20px;"></div><h2 id="alertTitle" style="margin-bottom: 10px;"></h2><p id="alertMessage" style="color: #64748b; margin-bottom: 30px;"></p><div style="display: flex; justify-content: center;"><button id="alertBtn" class="btn btn-confirm" style="min-width: 120px; border-radius: 25px;">Đóng</button></div></div></div>

<script>
function validateNumberInput(val, errorElementId, inputElementId) {
    const errorEl = document.getElementById(errorElementId);
    const inputEl = document.getElementById(inputElementId);
    const isValid = val !== "" && !isNaN(val) && parseFloat(val) >= 0 && Number.isInteger(parseFloat(val));
    if (!isValid) { errorEl.style.display = 'block'; inputEl.classList.add('input-invalid'); return false; } 
    else { errorEl.style.display = 'none'; inputEl.classList.remove('input-invalid'); return true; }
}

function tinhTongTien() {
    const dien = parseFloat(document.getElementById('view-dien').value) || 0;
    const nuoc = parseFloat(document.getElementById('view-nuoc').value) || 0;
    const phiDV = parseFloat(document.getElementById('view-dv').getAttribute('data-raw-dv')) || 0;
    const tong = (dien * 11000) + (nuoc * 2800) + phiDV + 1500000;
    document.getElementById('view-tong').value = new Intl.NumberFormat('vi-VN').format(tong) + ' đ';
}

document.getElementById('view-dien').addEventListener('input', tinhTongTien);
document.getElementById('view-nuoc').addEventListener('input', tinhTongTien);

function showAlert(title, message, type = 'success') {
    const icon = document.getElementById('alertIcon'); const btn = document.getElementById('alertBtn');
    if (type === 'success') { icon.innerHTML = '<i class="fa-solid fa-circle-check"></i>'; icon.style.color = '#22c55e'; btn.style.background = '#22c55e'; } 
    else { icon.innerHTML = '<i class="fa-solid fa-circle-xmark"></i>'; icon.style.color = '#ef4444'; btn.style.background = '#ef4444'; }
    document.getElementById('alertTitle').innerText = title; document.getElementById('alertMessage').innerText = message;
    document.getElementById('alertModal').style.display = 'flex';
    btn.onclick = function() { closeModal('alertModal'); if (type === 'success') location.reload(); };
}

function openViewModal(id) {
    const row = document.getElementById('row-' + id);
    const data = JSON.parse(row.getAttribute('data-json'));
    document.getElementById('view-ma').value = data.mahoadon;
    document.getElementById('view-phong').value = data.sophong;
    document.getElementById('view-dien').value = data.sodien;
    document.getElementById('view-nuoc').value = data.sonuoc;
    document.getElementById('view-dv').setAttribute('data-raw-dv', data.phidichvu);
    document.getElementById('view-dv').value = new Intl.NumberFormat('vi-VN').format(data.phidichvu) + ' đ';
    
    const hanTT = new Date(data.han_thanhtoan);
    document.getElementById('view-han-tt').value = hanTT.toLocaleDateString('vi-VN');
    
    tinhTongTien();
    document.getElementById('view-trangthai').value = data.trangthai_ao;
    document.getElementById('viewModal').style.display = 'flex';
}

document.getElementById('updateStatusForm').onsubmit = function(e) {
    e.preventDefault();
    fetch(window.location.href, { method: 'POST', body: new FormData(this) })
    .then(res => res.json()).then(data => {
        if(data.success) { closeModal('viewModal'); showAlert('Thành công', 'Cập nhật thành công!', 'success'); }
        else { showAlert('Thất bại', 'Lỗi!', 'error'); }
    });
};

function closeModal(id) { document.getElementById(id).style.display = 'none'; }
window.onclick = function(e) { if (e.target.classList.contains('modal-overlay')) e.target.style.display = 'none'; }
</script>
</body>
</html>