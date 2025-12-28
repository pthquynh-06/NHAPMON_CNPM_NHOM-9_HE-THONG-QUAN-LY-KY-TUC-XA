<?php
// cnttphong.php - Xử lý cập nhật và Giao diện Modal

// --- LOGIC XỬ LÝ CẬP NHẬT QUA AJAX ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_room') {
    try {
        $sophong_old = $_POST['sophong_old'];
        $sophong_new = $_POST['sophong'];
        $succhua = $_POST['succhua']; 
        $giaphong = 1500000; 
        $songuoi = $_POST['songuoi'];
        $trangthai = $_POST['trangthai'];

        if ($succhua <= 0 || $songuoi < 0) {
            echo "error_value";
            exit;
        }

        if ($songuoi > $succhua) {
            throw new Exception("Số người hiện có không thể lớn hơn sức chứa của phòng.");
        }

        $sql = "UPDATE phong SET sophong=?, succhua=?, giaphong=?, songuoi=?, trangthai=? WHERE sophong=?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Lỗi hệ thống chuẩn bị truy vấn SQL: " . $conn->error);
        }

        $stmt->bind_param("siisss", $sophong_new, $succhua, $giaphong, $songuoi, $trangthai, $sophong_old);
        
        if ($stmt->execute()) {
            echo "success";
        } else {
            throw new Exception("Lỗi thực thi cập nhật cơ sở dữ liệu.");
        }
    } catch (Exception $e) {
        echo "system_error: " . $e->getMessage();
    }
    exit; 
}

// --- GIAO DIỆN MODAL CHỈNH SỬA ---
function renderEditModal() {
?>
<div id="editRoomModal" class="modal-overlay">
    <div class="modal-box">
        <h3 style="border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 20px;">Cập nhật thông tin phòng</h3>
        <input type="hidden" id="old-sophong">
        
        <div class="form-group">
            <label>Số phòng</label>
            <input type="text" id="edit-sophong" readonly style="cursor: not-allowed;">
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Sức chứa</label>
                <select id="edit-succhua" onchange="updateSucchuaAndStatus()">
                    <option value="4">4</option>
                    <option value="8">8</option>
                </select>
            </div>
            <div class="form-group">
                <label>Hiện có</label>
                <select id="edit-songuoi" onchange="autoUpdateStatus()"></select>
            </div>
        </div>
        <div class="form-group">
            <label>Giá phòng (VNĐ)</label>
            <input type="number" id="edit-giaphong" value="1500000" readonly style="cursor: not-allowed;">
        </div>
        <div class="form-group">
            <label>Trạng thái</label>
            <select id="edit-trangthai">
                <option value="Trống">Trống</option>
                <option value="Còn chỗ">Còn chỗ</option> 
                <option value="Đầy">Đầy</option>
                <option value="Đang sửa chữa">Đang sửa chữa</option>
            </select>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-cancel" onclick="closeModal()">Hủy</button>
            <button type="button" class="btn btn-confirm" onclick="saveRoomEdit()">Lưu thay đổi</button>
        </div>
    </div>
</div>
<?php
}
?>
