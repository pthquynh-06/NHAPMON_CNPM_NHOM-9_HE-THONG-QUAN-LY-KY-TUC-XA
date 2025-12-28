<?php 
require_once '../includes/check_login.php'; 
require_once '../includes/db_config_sinhvien.php'; 

if(!isset($_SESSION['loggedin'])){
    header("Location: ../quanlynguoidung/dangnhaphethong.php");
    exit;
}

// 1. XỬ LÝ XÓA QUA AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete_contract') {
    $mahd = $_POST['mahopdong']; 
    $sql = "DELETE FROM hopdong WHERE mahopdong = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $mahd);
    echo $stmt->execute() ? "success" : "error";
    exit; 
}

// 2. XỬ LÝ CẬP NHẬT QUA AJAX (Đã thêm xử lý lỗi hệ thống)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_full') {
    try {
        $mahd = $_POST['mahopdong'];
        $mssv = $_POST['mssv']; 
        $hoten = $_POST['hoten'];
        $sophong = $_POST['sophong'];
        $ngaybatdau = $_POST['ngaybatdau']; 
        $ngayketthuc = $_POST['ngayketthuc'];
        $tienphong = $_POST['tienphong'];
        $trangthai = $_POST['trangthai'];

        // --- KIỂM TRA TRÙNG HỢP ĐỒNG CÒN HIỆU LỰC ---
        if ($trangthai == 'Còn hiệu lực') {
            $check_sql = "SELECT mahopdong FROM hopdong WHERE mssv = ? AND trangthai = 'Còn hiệu lực' AND mahopdong != ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("ss", $mssv, $mahd);
            $check_stmt->execute();
            if ($check_stmt->get_result()->num_rows > 0) {
                echo "duplicate_active";
                exit;
            }
        }

        $sql = "UPDATE hopdong SET mssv=?, hoten=?, sophong=?, ngaybatdau=?, ngayketthuc=?, tienphong=?, trangthai=? WHERE mahopdong=?";
        $stmt = $conn->prepare($sql);
        if(!$stmt) throw new Exception("system_error");

        $stmt->bind_param("sssssiss", $mssv, $hoten, $sophong, $ngaybatdau, $ngayketthuc, $tienphong, $trangthai, $mahd);
        echo $stmt->execute() ? "success" : "system_error";
    } catch (Exception $e) {
        echo "system_error";
    }
    exit; 
}

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hợp đồng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;700&display=swap');
        html, body { height: 100%; margin: 0; padding: 0; background-color: #f8faff; }
        body { font-family: 'Be Vietnam Pro', sans-serif; color: #1e293b; display: flex; flex-direction: column; min-height: 100vh; }
        .main { flex: 1; padding: 20px; display: flex; justify-content: center; align-items: flex-start; overflow-y: auto; }
        .table-container-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); width: 100%; max-width: 1200px; box-sizing: border-box; margin-bottom: 40px; }
        .table-responsive { width: 100%; overflow-x: auto; }
        .student-table { width: 100%; border-collapse: collapse; table-layout: auto; }
        .student-table th, .student-table td { padding: 12px 8px; border-bottom: 1px solid #f1f5f9; font-size: 13.5px; text-align: left; vertical-align: middle; }
        .student-table th { background: #f8fafc; color: #64748b; font-size: 11px; text-transform: uppercase; white-space: nowrap; position: sticky; top: 0; }
        .col-mahd { font-weight: 700; color: #2563eb; }
        .btn-search { background: #2563eb; color: white; padding: 10px 25px; border-radius: 8px; border: none; font-weight: 700; cursor: pointer; }
        .btn-clear { background: #f1f5f9; color: #64748b; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13.5px; display: inline-flex; align-items: center; border: 1px solid #e2e8f0; }

        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); display: none; align-items: center; justify-content: center; z-index: 2000; }
        .modal-box { background: white; border-radius: 28px; padding: 40px 30px; text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,0.1); width: 90%; max-width: 400px; animation: zoomIn 0.3s ease; }
        .modal-edit { max-width: 850px; text-align: left; }
        @keyframes zoomIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
        .status-badge { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
        .con-han { background: #dcfce7; color: #15803d; }
        .het-han { background: #fee2e2; color: #b91c1c; }

        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-top: 20px; }
        .form-group label { display: block; font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 6px; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; box-sizing: border-box; }
        
        .input-locked { background: #f8fafc !important; color: #94a3b8 !important; cursor: not-allowed; border: 1px dashed #cbd5e1 !important; }
        .input-error { border: 1px solid #ef4444 !important; background-color: #fffafb !important; }
        .error-text { color: #ef4444; font-size: 10px; font-weight: 700; margin-top: 4px; display: none; }
        .modal-alert { padding: 12px; border-radius: 10px; margin-bottom: 20px; font-weight: 700; font-size: 14px; text-align: center; display: none; }
        .alert-fail { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .student-table td b { text-transform: capitalize; }
        #edit-hoten { text-transform: capitalize; }
    </style>
</head>
<body>

<?php include '../includes/chung.php'; ?>

<main class="main"> 
    <div class="table-container-card">
        <h2 style="margin-bottom: 20px;">Danh sách hợp đồng</h2>
        
        <div class="table-responsive">
            <table class="student-table">
                <thead>
                    <tr>
                        <th>MÃ HĐ</th>
                        <th>SINH VIÊN</th>
                        <th>PHÒNG</th>
                        <th>TIỀN PHÒNG</th>
                        <th>KẾT THÚC</th>
                        <th>TRẠNG THÁI</th>
                        <th style="text-align: center;">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>

                
                <tr id="row-<?= $hd['mahopdong'] ?>">
                            <td class="col-mahd"><?= $hd['mahopdong'] ?></td>
                            <td><b><?= $hd['hoten'] ?></b><br><small><?= $hd['mssv'] ?></small></td>
                            <td><b><?= $hd['sophong'] ?></b></td>
                            <td><?= number_format($hd['tienphong'], 0, ',', '.') ?> đ</td>
                            <td><?= date('d/m/Y', strtotime($hd['ngayketthuc'])) ?></td>
                            <td><span class="status-badge <?= $status_class ?>"><?= $status_label ?></span></td>
                            <td style="text-align: center;">
                                <i class="fa-solid fa-pen-to-square" style="color: #2563eb; cursor: pointer; margin-right: 15px;" onclick='openEditModal(<?= json_encode($hd) ?>)'></i>
                                <i class="fa-solid fa-trash" style="color: #ef4444; cursor: pointer;" onclick="handleDeleteLogic('<?= $hd['mahopdong'] ?>', <?= $is_expired ? 'true' : 'false' ?>)"></i>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align: center; padding: 20px;">Không tìm thấy kết quả.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div id="modalCannotDelete" class="modal-overlay">
    <div class="modal-box">
        <h3 style="color: #1e293b;">Thông báo</h3>
        <p>Hợp đồng còn hạn không thể xóa!</p>
        <button onclick="closeModal('modalCannotDelete')" style="margin-top:20px; padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Đã hiểu</button>
    </div>
</div>

<div id="modalConfirmDelete" class="modal-overlay">
    <div class="modal-box">
        <h3 style="color: #1e293b;">Xác nhận xóa</h3>
        <p>Bạn có chắc muốn xóa hợp đồng này?</p>
        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 25px;">
            <button onclick="closeModal('modalConfirmDelete')" style="padding: 10px 20px; background: #f1f5f9; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Hủy</button>
            <button id="btnConfirmAction" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Đồng ý</button>
        </div>
    </div>
</div>

<div id="modalDeleteSuccess" class="modal-overlay">
    <div class="modal-box">
        <h3 style="color: #166534;">Thông báo</h3>
        <p>Xóa hợp đồng thành công!</p>
        <button onclick="window.location.reload()" style="margin-top:20px; padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Đã hiểu</button>
    </div>
</div>

<div id="editModal" class="modal-overlay">
    <div class="modal-box modal-edit">
        <h3 style="margin-top: 0;">Chỉnh sửa hợp đồng</h3>
        <div id="modal-alert-box" class="modal-alert"></div> 

        <div class="form-grid">
            <input type="hidden" id="edit-mahd">
            <div class="form-group">
                <label>Mã hợp đồng</label>
                <input type="text" id="display-mahd" readonly class="input-locked">
            </div>
            <div class="form-group">
                <label>Mã sinh viên</label>
                <input type="text" id="edit-mssv" readonly class="input-locked">
            </div>
            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" id="edit-hoten">
                <div id="err-hoten" class="error-text">Không được để trống</div>
            </div>
            <div class="form-group">
                <label>Phòng</label>
                <input type="text" id="edit-phong">
                <div id="err-phong" class="error-text">Không được để trống</div>
            </div>
            <div class="form-group">
                <label>Tiền phòng (VNĐ)</label>
                <input type="number" id="edit-tienphong" readonly class="input-locked">
                <div id="err-tienphong" class="error-text">Không thể thay đổi tiền phòng</div>
            </div>
            <div class="form-group">
                <label>Trạng thái</label>
                <select id="edit-trangthai">
                    <option value="Còn hiệu lực">Còn hiệu lực</option>
                    <option value="Hết hạn">Hết hạn</option>
                </select>
            </div>
            <div class="form-group">
                <label>Ngày bắt đầu</label>
                <input type="date" id="edit-ngaybatdau">
            </div>
            <div class="form-group">
                <label>Ngày kết thúc</label>
                <input type="date" id="edit-ngayketthuc">
                <div id="err-ngay" class="error-text">Ngày kết thúc phải sau ngày bắt đầu</div>
            </div>
        </div>
        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 30px;">
            <button onclick="closeModal('editModal')" style="padding: 10px 20px; background: #f1f5f9; border: none; border-radius: 8px; cursor: pointer;">Hủy</button>
            <button onclick="saveEdit()" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 8px; cursor: pointer;">Cập nhật</button>
        </div>
    </div>
</div>

<script>
function chuanHoaTen(str) {
    return str.toLowerCase().replace(/(^|\s)\S/g, function(l) {
        return l.toUpperCase();
    });
}

function handleDeleteLogic(mahopdong, isExpired) {
    if (!isExpired) {
        document.getElementById('modalCannotDelete').style.display = 'flex';
    } else {
        document.getElementById('modalConfirmDelete').style.display = 'flex';
        document.getElementById('btnConfirmAction').onclick = function() {
            const p = new URLSearchParams();
            p.append('action', 'delete_contract');
            p.append('mahopdong', mahopdong);
            fetch('', { method: 'POST', body: p }).then(r => r.text()).then(r => {
                if(r.trim()==="success") {
                    closeModal('modalConfirmDelete');
                    document.getElementById('modalDeleteSuccess').style.display = 'flex';
                } else {
                    alert("Lỗi hệ thống: Không thể xóa hợp đồng vào lúc này.");
                }
            });
        };
    }
}

function openEditModal(d) {
    document.getElementById('modal-alert-box').style.display = 'none';
    document.querySelectorAll('.error-text').forEach(e => e.style.display = 'none');
    document.querySelectorAll('input').forEach(e => e.classList.remove('input-error'));

    document.getElementById('edit-mahd').value = d.mahopdong;
    document.getElementById('display-mahd').value = d.mahopdong;
    document.getElementById('edit-mssv').value = d.mssv;
    document.getElementById('edit-hoten').value = d.hoten;
    document.getElementById('edit-phong').value = d.sophong;
    document.getElementById('edit-tienphong').value = d.tienphong;
    document.getElementById('edit-trangthai').value = d.trangthai;
    document.getElementById('edit-ngaybatdau').value = d.ngaybatdau;
    document.getElementById('edit-ngayketthuc').value = d.ngayketthuc;
    document.getElementById('editModal').style.display = 'flex';
}

function saveEdit() {
    const alertBox = document.getElementById('modal-alert-box');
    let hasError = false;
    
    ['hoten', 'phong'].forEach(f => {
        const input = document.getElementById('edit-' + f);
        if(!input.value.trim()) {
            input.classList.add('input-error');
            document.getElementById('err-' + f).style.display = 'block';
            hasError = true;
        } else {
            input.classList.remove('input-error');
            document.getElementById('err-' + f).style.display = 'none';
        }
    });

    if(hasError) {
        alertBox.innerText = "Vui lòng kiểm tra dữ liệu và nhập đầy đủ thông tin!";
        alertBox.className = "modal-alert alert-fail";
        alertBox.style.display = 'block';
        return;
    }

    const start = new Date(document.getElementById('edit-ngaybatdau').value);
    const end = new Date(document.getElementById('edit-ngayketthuc').value);
    if(end <= start) {
        alertBox.innerText = "Ngày kết thúc phải sau ngày bắt đầu!";
        alertBox.className = "modal-alert alert-fail";
        alertBox.style.display = 'block';
        document.getElementById('edit-ngayketthuc').classList.add('input-error');
        document.getElementById('err-ngay').style.display = 'block';
        return;
    }

    const d = new URLSearchParams();
    d.append('action', 'update_full');
    d.append('mahopdong', document.getElementById('edit-mahd').value);
    d.append('mssv', document.getElementById('edit-mssv').value);
    d.append('hoten', document.getElementById('edit-hoten').value);
    d.append('sophong', document.getElementById('edit-phong').value);
    d.append('tienphong', document.getElementById('edit-tienphong').value);
    d.append('trangthai', document.getElementById('edit-trangthai').value);
    d.append('ngaybatdau', document.getElementById('edit-ngaybatdau').value);
    d.append('ngayketthuc', document.getElementById('edit-ngayketthuc').value);

    fetch('', { method: 'POST', body: d })
    .then(r => r.text())
    .then(r => {
        const res = r.trim();
        if(res === "success") {
            alertBox.innerText = "Chỉnh sửa thành công!";
            alertBox.className = "modal-alert alert-success";
            alertBox.style.display = 'block';
            setTimeout(() => window.location.reload(), 1000);
        } else if(res === "duplicate_active") {
            alertBox.innerText = "Lỗi: Sinh viên này đang có một hợp đồng khác còn hiệu lực!";
            alertBox.className = "modal-alert alert-fail";
            alertBox.style.display = 'block';
        } else {
            alertBox.innerText = "Lỗi hệ thống: Không thể cập nhật dữ liệu vào lúc này.";
            alertBox.className = "modal-alert alert-fail";
            alertBox.style.display = 'block';
        }
    })
    .catch(error => {
        alertBox.innerText = "Lỗi kết nối: Vui lòng kiểm tra internet.";
        alertBox.className = "modal-alert alert-fail";
        alertBox.style.display = 'block';
    });
}

function closeModal(id) { document.getElementById(id).style.display = 'none'; }

document.getElementById('edit-hoten').addEventListener('input', function(e) {
    let cursorPosition = this.selectionStart;
    this.value = chuanHoaTen(this.value);
    this.setSelectionRange(cursorPosition, cursorPosition);
});

const originalOpenEditModal = openEditModal;
openEditModal = function(d) {
    originalOpenEditModal(d);
    const hotenInput = document.getElementById('edit-hoten');
    hotenInput.value = chuanHoaTen(hotenInput.value);
};
</script>
</body>
</html>