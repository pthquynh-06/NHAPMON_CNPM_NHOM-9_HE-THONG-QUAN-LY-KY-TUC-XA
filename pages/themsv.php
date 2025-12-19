<?php 
require_once '../includes/check_login.php'; 
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
        /* Tối ưu dựa trên ảnh giao diện của bạn */
        .form-grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 20px; 
            margin-top: 10px;
        }
        .form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 15px; }
        .form-group label { font-weight: 600; color: #374151; font-size: 14px; }
        .form-group input { 
            padding: 12px; 
            border: 1px solid #d1d5db; 
            border-radius: 8px; 
            font-size: 15px; 
            outline: none;
        }
        .form-group input:focus { border-color: #2563eb; }
        
        .gender-wrap { display: flex; gap: 20px; padding-top: 5px; }
        .gender-wrap label { font-weight: normal; cursor: pointer; display: flex; align-items: center; gap: 5px; }

        .actions { 
            display: flex; 
            justify-content: flex-end; 
            gap: 12px; 
            margin-top: 20px; 
            padding-top: 20px;
            border-top: 1px solid #f3f4f6;
        }
        .btn { padding: 10px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px; transition: 0.3s; }
        .btn-cancel { background: #fff; border: 1px solid #d1d5db; color: #4b5563; }
        .btn-cancel:hover { background: #f9fafb; }
        .btn-primary { background: #2563eb; border: none; color: #fff; }
        .btn-primary:hover { background: #1d4ed8; }

        /* Thông báo ẩn mặc định */
        #successMsg { display: none; color: #16a34a; font-weight: 600; margin-bottom: 10px; }
    </style>
</head>
<body>

<?php include '../includes/chung.php'; ?>

<main class="main">
    <div class="header">
        <h2>Thêm mới sinh viên</h2>
        <div class="user-greeting" style="background-color: #2563eb; color: white; padding: 8px 15px; border-radius: 20px; font-size: 14px;"> 
            Xin chào, <?php echo isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'Khách'; ?></div>
    </div>

    <div class="search-card">
        <form id="studentForm" action="process_add.php" method="POST">
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

            <div id="successMsg">Thêm sinh viên thành công!</div>

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