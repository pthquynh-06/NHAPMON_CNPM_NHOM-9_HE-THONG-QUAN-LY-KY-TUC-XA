<?php 
require_once '../includes/check_login.php'; 
require_once '../includes/db_config_sinhvien.php'; 
// ===== XỬ LÝ LỖI HỆ THỐNG: KẾT NỐI CSDL =====
if (!isset($conn) || $conn->connect_error) {
    die("system_error");
}

if(!isset($_SESSION['loggedin'])){
    header("Location: ../quanlynguoidung/dangnhaphethong.php");
    exit;
}

$sql = "SELECT * FROM sinhvien ORDER BY mssv ASC";

// --- XỬ LÝ TÌM KIẾM ---
$search = $_GET['search'] ?? ''; 
if (!empty($search)) {
    $sql = "SELECT * FROM sinhvien WHERE hoten LIKE ? OR mssv LIKE ? OR sophong LIKE ? OR truong LIKE ? ORDER BY mssv DESC";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$search%";
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();

    if ($stmt->errno) {
        die("system_error");
    }
    
    $result = $stmt->get_result();
} else {
    $result = mysqli_query($conn, $sql);
    
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sinh viên - KTX Hướng Dương</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/style.css"> 
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap');
        .main { font-family: 'Be Vietnam Pro', sans-serif; background-color: #f8faff; padding: 30px; }
        .table-container-card { background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03); margin-top: 20px; }
        .student-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .student-table th { padding: 22px 10px; border-bottom: 2px solid #d1dae4ff; color: #818694ff; text-transform: uppercase; text-align: left; }
        .student-table td { padding: 15px 10px; border-bottom: 1px solid #f1f5f9; color: #2e3a59; vertical-align: middle; border-right: 1px solid #f1f5f9; }
        
        .student-name { font-weight: 600; color: #1e2640; display: block; }
        .badge { padding: 3px 8px; border-radius: 12px; font-size: 10px; font-weight: 600; }
        .badge-nam { background: #e0f2fe; color: #0369a1; }
        .badge-nu { background: #fce7f3; color: #be185d; }
        
        .action-icon { font-size: 18px; cursor: pointer; transition: 0.2s; margin-right: 12px; }
        .action-icon:hover { transform: scale(1.2); }

        /* MODAL CHUNG */
        .nen-mo, .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 9999; }
        .o-trang, .modal-box { background: white; padding: 35px; border-radius: 16px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); animation: hienLen 0.25s ease-out; }
        /* MODAL SỬA (Rộng - Grid) */
        .modal-box { width: 90%; max-width: 750px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 20px; text-align: left; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 5px; }
        .form-group input, .form-group select { padding: 10px; border: 1.4px solid #d1dae4; border-radius: 8px; outline: none; font-size: 14px; }
        .form-group input:focus { border-color: #2563eb; }

        .btn-group { display: flex; gap: 10px; margin-top: 25px; }
        .nut-modal, .btn { padding: 12px 25px; border-radius: 10px; font-weight: 600; cursor: pointer; border: none; transition: 0.2s; font-size: 14px; }
        .nut-huy, .btn-cancel { background: #f3f4f6; color: #374151; }  
        .nut-xoa, .btn-confirm { background: #2563eb; color: white; }

        @keyframes hienLen { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
    </style>
</head>
<body>

<?php include '../includes/chung.php'; ?>

<main class="main">
    <div class="header">
        <h2 style="color: #1e2640; font-weight: 700;">Danh sách sinh viên ký túc xá</h2>
    </div>
    
    <div class="table-container-card">
        <div style="margin-bottom: 25px;">
            <form method="GET" action="danhsachsv.php" style="display: flex; gap: 10px;">
                <div style="position: relative; flex: 1;">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Nhập tên, mã SV, phòng hoặc trường..." 
                        style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 12px; border: 1.4px solid #d1dae4; outline: none;">
                </div>
                <button type="submit" style="padding: 0 25px; background: #2563eb; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer;">Tìm kiếm</button>
                
                <?php if($search != ''): ?>
                    <a href="danhsachsv.php" style="padding: 12px 15px; background: #f1f5f9; color: #475569; border-radius: 11px; text-decoration: none; font-size: 14px; display: flex; align-items: center;">Hủy lọc</a>
                <?php endif; ?>
            </form>
        </div>
        <table class="student-table">
            <thead>
                <tr>
                    <th>MSV</th><th>Họ Tên & GT</th><th>Ngày Sinh</th><th>Phòng & Trường</th><th>Liên hệ</th><th>CCCD</th><th>Quê quán</th><th>Bắt đầu</th><th style="text-align: center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): 
                    while($row = mysqli_fetch_assoc($result)): 
                        $gt_class = ($row['gioitinh'] == 'Nam') ? 'badge-nam' : 'badge-nu';
                        $mssv = $row['mssv'];
                ?>
                <tr id="row-<?php echo $mssv; ?>">
                    <td class="c-mssv" style="font-family: monospace; font-weight: 700;"><?php echo $mssv; ?></td>
                    <td>
                        <span class="student-name c-hoten"><?php echo htmlspecialchars($row['hoten']); ?></span>
                        <span class="badge <?php echo $gt_class; ?> c-gioitinh"><?php echo $row['gioitinh']; ?></span>
                    </td>
                    <td class="c-ngaysinh" data-raw="<?php echo $row['ngaysinh']; ?>"><?php echo date('d/m/Y', strtotime($row['ngaysinh'])); ?></td>
                    <td>
                        <span class="student-name c-sophong"><?php echo htmlspecialchars($row['sophong']); ?></span>
                        <small class="c-truong" style="color:#64748b"><?php echo htmlspecialchars($row['truong']); ?></small>
                    </td>
                    <td>
                        <div class="c-sdt"><?php echo htmlspecialchars($row['sodienthoai']); ?></div>
                        <small class="c-email" style="color:#64748b"><?php echo htmlspecialchars($row['email']); ?></small>
                    </td>
                        <td class="c-cccd"><?php echo htmlspecialchars($row['cccd']); ?></td>
                        <td class="c-quequan"><?php echo htmlspecialchars($row['quequan']); ?></td>
                        <td class="c-ngaybatdau" data-raw="<?php echo $row['ngaybatdau']; ?>"><?php echo date('d/m/Y', strtotime($row['ngaybatdau'])); ?></td>
                        <td style="text-align: center; white-space: nowrap;">
                            <i class="fa-solid fa-pen-to-square action-icon" style="color: #2563eb;" onclick="openEditModal('<?php echo $mssv; ?>')"></i>
                            <i class="fa-solid fa-trash action-icon" style="color: #ef4444;" onclick="moXacNhanXoa('<?php echo $mssv; ?>')"></i>
                    </td>
                </tr>  
                <?php endwhile; else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 50px; color: #94a3b8; font-size: 16px;">
                            <i class="fa-solid fa-user-slash" style="font-size: 40px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                            Không tìm thấy sinh viên 
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main> 

<div id="editModal" class="modal-overlay">
    <div class="modal-box">
        <h3 style="border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 10px;">Chỉnh sửa thông tin sinh viên</h3>
        <form id="formEdit">
            <div class="form-grid">
                <div class="form-group"><label>Mã sinh viên (MSV)</label><input type="text" id="edit-mssv" name="mssv" readonly style="background:#f8fafc"></div>
                <div class="form-group"><label>Họ tên</label><input type="text" id="edit-hoten" name="hoten"></div>
                <div class="form-group">
                    <label>Giới tính</label>
                    <select id="edit-gioitinh" name="gioitinh">
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                    </select>
                </div>
                <div class="form-group"><label>Ngày sinh</label><input type="date" id="edit-ngaysinh" name="ngaysinh"></div>
                <div class="form-group"><label>Số CCCD</label><input type="text" id="edit-cccd" name="cccd"></div>
                <div class="form-group"><label>Số điện thoại</label><input type="text" id="edit-sdt" name="sodienthoai"></div>
                <div class="form-group"><label>Số phòng</label><input type="text" id="edit-phong" name="sophong"></div>
                <div class="form-group"><label>Trường</label><input type="text" id="edit-truong" name="truong"></div>
                <div class="form-group"><label>Email</label><input type="email" id="edit-email" name="email"></div>
                <div class="form-group"><label>Quê quán</label><input type="text" id="edit-quequan" name="quequan"></div>
                <div class="form-group"><label>Ngày bắt đầu</label><input type="date" id="edit-ngaybatdau" name="ngaybatdau"></div>
            </div>
            <div class="btn-group" style="justify-content: flex-end;">
                <button type="button" class="btn btn-cancel" onclick="dongModalSua()">Hủy</button>
                <button type="submit" class="btn btn-confirm">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
 
<div id="modalXacNhan" class="nen-mo">
    <div class="o-trang">
        <div style="color: #f59e0b; font-size: 55px; margin-bottom: 15px;"><i class="fas fa-question-circle"></i></div>
        <h2>Xác nhận xóa</h2>
        <p style="color: #6b7280; margin-bottom: 25px;">Bạn có chắc chắn muốn xóa sinh viên này?</p>
        <div style="display: flex; justify-content: center;">
            <button class="nut-modal nut-huy" onclick="dongXacNhan()">Hủy</button>
            <button id="btnConfirmDelete" class="nut-modal nut-xoa">Đồng ý</button>
        </div>
    </div>
</div>

<div id="modalThanhCongXoa" class="nen-mo">
    <div class="o-trang">
        <div style="color: #1adb5aff; font-size: 55px; margin-bottom: 15px;"><i class="fas fa-check-circle"></i></div>
        <h2>Thành công!</h2>
        <p>Đã xóa sinh viên ra khỏi hệ thống</p>
        <button class="nut-modal nut-xoa" onclick="location.reload()">Đóng</button>
    </div>
</div>

<script>

// 4. LOGIC XÓA (GIỮ NGUYÊN CÓ THÔNG BÁO NHƯ BẠN MUỐN)
function moXacNhanXoa(mssv) {
    document.getElementById('modalXacNhan').style.display = 'flex';
    document.getElementById('btnConfirmDelete').onclick = function() {
        fetch('xoasinhvien.php?id=' + mssv)
        .then(() => {
            document.getElementById('modalXacNhan').style.display = 'none';
            document.getElementById('modalThanhCongXoa').style.display = 'flex';
        });
    };
}

function dongXacNhan() {
    document.getElementById('modalXacNhan').style.display = 'none';
}

// Đóng modal khi click ra ngoài
window.onclick = function(event) {
    if (event.target.className === 'modal-overlay') dongModalSua();
    if (event.target.className === 'nen-mo') dongXacNhan();
}
</script>
</body>
</html>