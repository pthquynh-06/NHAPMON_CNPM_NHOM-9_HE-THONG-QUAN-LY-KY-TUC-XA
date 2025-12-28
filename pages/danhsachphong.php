<?php 
require_once '../includes/check_login.php'; 
require_once '../includes/db_config_sinhvien.php'; 

// Nhúng các file chức năng
require_once 'timkiemphong.php';
require_once 'cnttphong.php';

if(!isset($_SESSION['loggedin'])){
    header("Location: ../quanlynguoidung/dangnhaphethong.php");
    exit;
}

$search = $_GET['search'] ?? ''; 
$result = getRoomData($conn, $search); // Gọi hàm từ timkiemphong.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách phòng - KTX Hướng Dương</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/style.css"> 
    <style>
        /* Giữ nguyên toàn bộ phần CSS từ mã gốc của bạn tại đây */
        @import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap');
        .main { font-family: 'Be Vietnam Pro', sans-serif; background-color: #f8faff; padding: 30px; }
        .table-container-card { background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03); margin-top: 20px; }
        .room-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        .room-table th { padding: 20px 10px; border-bottom: 2px solid #d1dae4ff; color: #818694ff; text-transform: uppercase; text-align: left; }
        .room-table td { padding: 15px 10px; border-bottom: 1px solid #f1f5f9; color: #2e3a59; vertical-align: middle; border-right: 1px solid #f1f5f9; }
        .room-id { font-weight: 700; color: #2563eb; font-size: 16px; }
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .status-empty { background: #fee2e2; color: #b91c1c;; } 
        .status-available { background: #e0f2fe; color: #0369a1; } 
        .status-full { background: #dcfce7; color: #15803d; } 
        .status-repair { background: #fef3c7; color: #92400e; } 
        .price-text { font-weight: 600; color: #1e293b; }
        .action-icon { font-size: 18px; cursor: pointer; transition: 0.2s; }
        .action-icon:hover { transform: scale(1.2); }
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 9999; }
        .modal-box { background: white; padding: 35px; border-radius: 16px; width: 90%; max-width: 500px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); animation: hienLen 0.25s ease-out; }
        .form-group { display: flex; flex-direction: column; text-align: left; margin-bottom: 15px; }
        .form-group label { font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 5px; }
        .form-group input, .form-group select { padding: 10px; border: 1.4px solid #d1dae4; border-radius: 8px; outline: none; font-size: 14px; }
        .form-group input:focus { border-color: #2563eb; }
        .btn-group { display: flex; gap: 10px; margin-top: 25px; justify-content: flex-end; }
        .btn { padding: 12px 25px; border-radius: 10px; font-weight: 600; cursor: pointer; border: none; transition: 0.2s; font-size: 14px; }
        .btn-cancel { background: #f3f4f6; color: #374151; } 
        .btn-confirm { background: #2563eb; color: white; }
        @keyframes hienLen { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
    </style>
</head>
<body>

<?php include '../includes/chung.php'; ?>

<main class="main">
    <div class="header">
        <h2 style="color: #1e2640; font-weight: 700;">Danh sách phòng ký túc xá</h2>
    </div>
    <div class="table-container-card">
        <div style="margin-bottom: 25px;">
            <form method="GET" action="danhsachphong.php" style="display: flex; gap: 10px;">
                <div style="position: relative; flex: 1;">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm số phòng hoặc trạng thái..." 
                        style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 12px; border: 1.4px solid #d1dae4; outline: none;">
                </div>
                <button type="submit" class="btn btn-confirm">Tìm kiếm</button>
                <?php if($search != ''): ?>
                    <a href="danhsachphong.php" style="padding: 12px 15px; background: #f1f5f9; color: #475569; border-radius: 11px; text-decoration: none; font-size: 14px; display: flex; align-items: center;">Hủy lọc</a>
                <?php endif; ?>
            </form>
        </div>
  

        <table class="room-table">
            <thead>
                <tr>
                    <th>Số Phòng</th>
                    <th>Sức Chứa</th>
                    <th>Giá Phòng</th>
                    <th>Hiện Có</th>
                    <th>Trạng Thái</th>
                    <th style="text-align: center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
            <?php 
               // Kiểm tra nếu biến $result có dữ liệu
                if ($result && mysqli_num_rows($result) > 0): 
                    while($row = mysqli_fetch_assoc($result)): 
                        $status_text = $row['trangthai'];
                        $status_class = ($status_text == 'Trống') ? 'status-empty' : 
                            (($status_text == 'Đầy') ? 'status-full' : 
                            (($status_text == 'Đang sửa chữa') ? 'status-repair' : 'status-available'));
                ?>
              
                    <tr>
                        <td class="room-id"><?php echo htmlspecialchars($row['sophong']); ?></td>
                        <td><?php echo $row['succhua']; ?> người</td>
                        <td class="price-text"><?php echo number_format($row['giaphong'], 0, ',', '.'); ?> đ</td>
                        <td><?php echo $row['songuoi']; ?> người</td>
                        <td><span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                        <td style="text-align: center;">
                            <i class="fa-solid fa-pen-to-square action-icon" style="color: #2563eb;" onclick='openEditRoomModal(<?php echo json_encode($row); ?>)'></i>
                        </td>
                    </tr>
                <?php 
                    endwhile; 
                else: 
                    // TRƯỜNG HỢP KHÔNG TÌM THẤY KẾT QUẢ
               ?>
                   <tr>
                       <td colspan="6" style="text-align: center; padding: 60px 20px;">
                           <div style="color: #94a3b8;">
                               <i class="fa-solid fa-magnifying-glass-minus" style="font-size: 48px; margin-bottom: 15px; color: #cbd5e1;"></i>
                               <h3 style="color: #475569; margin-bottom: 8px;">Không tìm thấy phòng nào</h3>
                               <p style="font-size: 14px;">Không có kết quả nào khớp với từ khóa "<strong><?php echo htmlspecialchars($search); ?></strong>".</p>
                               <a href="danhsachphong.php" style="display: inline-block; margin-top: 15px; color: #2563eb; text-decoration: none; font-weight: 600; font-size: 14px;">
                                   <i class="fa-solid fa-arrow-rotate-left"></i> Quay lại danh sách đầy đủ
                               </a>
                           </div>
                       </td>
                   </tr>
               <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php renderEditModal(); ?>

<script>
/* Giữ nguyên toàn bộ phần JavaScript xử lý logic Client-side */
function autoUpdateStatus() {
    const succhua = parseInt(document.getElementById('edit-succhua').value);
    const songuoi = parseInt(document.getElementById('edit-songuoi').value);
    const statusSelect = document.getElementById('edit-trangthai');

    if (songuoi === 0) {
        statusSelect.value = "Trống";
    } else if (songuoi === succhua) {
        statusSelect.value = "Đầy";
    } else {
        statusSelect.value = "Còn chỗ";
    }
}

function generateSonguoiOptions(selectedVal = 0) {
    const succhua = parseInt(document.getElementById('edit-succhua').value);
    const songuoiSelect = document.getElementById('edit-songuoi');
    songuoiSelect.innerHTML = '';
    for (let i = 0; i <= succhua; i++) {
        let opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i; 
        if (i == selectedVal) opt.selected = true;
        songuoiSelect.appendChild(opt);
    }
}

function updateSucchuaAndStatus() {
    generateSonguoiOptions(0); 
    autoUpdateStatus();
}

function openEditRoomModal(room) {
    document.getElementById('old-sophong').value = room.sophong;
    document.getElementById('edit-sophong').value = room.sophong;
    document.getElementById('edit-succhua').value = room.succhua;
    document.getElementById('edit-giaphong').value = 1500000;
    document.getElementById('edit-trangthai').value = room.trangthai;
    
    generateSonguoiOptions(room.songuoi);
    document.getElementById('editRoomModal').style.display = 'flex';
}

function saveRoomEdit() {
    const sophong = document.getElementById('old-sophong').value;
    const succhua = document.getElementById('edit-succhua').value;
    const songuoi = document.getElementById('edit-songuoi').value;
    const trangthai = document.getElementById('edit-trangthai').value;

    const d = new URLSearchParams();
    d.append('action', 'update_room');
    d.append('sophong_old', sophong);
    d.append('sophong', sophong);
    d.append('succhua', succhua);
    d.append('giaphong', 1500000); 
    d.append('songuoi', songuoi);
    d.append('trangthai', trangthai);

    fetch('', { method: 'POST', body: d })
    .then(r => r.text())
    .then(res => {
        const response = res.trim();
        if(response === "success") {
            window.location.reload();
        } else if (response.startsWith("system_error:")) {
            alert("Lỗi hệ thống: " + response.replace("system_error:", ""));
        } else if (response === "error_value") {
            alert("Lỗi: Giá trị nhập vào không hợp lệ!");
        } else {
            alert("Lưu thất bại: Đã xảy ra lỗi SQL không xác định.");
        }
    })
    .catch(err => {
        alert("Lỗi kết nối: Không thể gửi yêu cầu đến máy chủ.");
    });
}
function closeModal() {
    document.getElementById('editRoomModal').style.display = 'none';
}
</script>
</body>
</html>