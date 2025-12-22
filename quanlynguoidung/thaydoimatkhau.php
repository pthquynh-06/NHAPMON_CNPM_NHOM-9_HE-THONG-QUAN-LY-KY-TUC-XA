<?php
session_start();

// 1. BẢO VỆ TRANG
if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']){
    header("Location: dangnhaphethong.php");
    exit;
}

// 2. KẾT NỐI DATABASE
require_once '../includes/db_config_nguoidung.php';

// KHỞI TẠO BIẾN (Quan trọng: Để tránh lỗi Undefined variable $error)
$error = ""; 
$username = $_SESSION['username'];

// 3. XỬ LÝ ĐỔI MẬT KHẨU
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $old = $_POST['old_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if(empty($old) || empty($new) || empty($confirm)){
        $error = "Vui lòng nhập đủ thông tin";
    } elseif(!password_verify($old, $user['password'])){
        $error = "Mật khẩu hiện tại không đúng";
    } elseif(strlen($new) < 6){
        $error = "Mật khẩu mới phải ít nhất 6 ký tự";
    } elseif($new !== $confirm){
        $error = "Xác nhận mật khẩu không khớp";
    } else {
        $newHash = password_hash($new, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $update->bind_param("si", $newHash, $user['id']);
        if($update->execute()){
            session_destroy();
            header("Location: dangnhaphethong.php?change_success=1");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>ĐỔI MẬT KHẨU - HƯỚNG DƯƠNG</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(224, 244, 255, 0.4), rgba(224, 244, 255, 0.4)), 
                        url('https://tnut.edu.vn/uploads/tinyfiles/uploads/files/anh-bai-viet/2022/12/14/qtpv/ky-tuc-xa/01.jpg'); 
            background-size: cover; background-position: center; background-attachment: fixed;
            font-family: 'Montserrat', sans-serif; height: 100vh; margin: 0; display: flex; flex-direction: column;
        }
        .header-bar {
            padding: 10px 60px; display: flex; justify-content: space-between; align-items: center;
            background: rgba(255, 255, 255, 0.95); box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        .brand-area { display: flex; align-items: center; gap: 15px; }
        .brand-name-wrapper { display: flex; flex-direction: column; }
        .main-name { font-family: 'Playfair Display', serif; font-size: 32px; color: #005a9e; font-style: italic; font-weight: 900; line-height: 1; }
        .sub-name { font-size: 12px; letter-spacing: 2px; color: #555; text-transform: uppercase; font-weight: 700; }
        
        /* CĂN GIỮA Ô FORM */
        .main-content { flex: 1; display: flex; justify-content: center; align-items: center; padding: 20px; }
        .wrapper { 
            width: 400px; background: rgba(255, 255, 255, 0.98); padding: 40px; 
            border-radius: 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.2); 
        }
        
        h2 { text-align: center; font-family: 'Playfair Display', serif; color: #0078d4; font-size: 30px; text-transform: uppercase; margin-bottom: 25px; }
        label { font-weight: 700; font-size: 14px; color: #333; display: block; margin-top: 15px; margin-bottom: 5px; }
        input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; box-sizing: border-box; }
        .btn-submit { width: 100%; padding: 15px; background: #0078d4; color: #fff; border: none; border-radius: 50px; font-weight: 700; cursor: pointer; margin-top: 25px; transition: 0.3s; }
        .btn-submit:hover { background: #005a9e; }
        .error-box { background: #fff0f0; color: #d93025; padding: 10px; border-radius: 8px; font-size: 13px; text-align: center; margin-bottom: 15px; border: 1px solid #ffd2d2; }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: #666; text-decoration: none; font-size: 14px; font-weight: 600; }
    </style>
</head>
<body>

<header class="header-bar">
    <div class="brand-area">
        <i class="fa-solid fa-sun" style="font-size: 40px; color: #f97316;"></i>
        <div class="brand-name-wrapper">
            <span class="main-name">Hướng Dương</span>
            <span class="sub-name">Kí túc xá sinh viên</span>
        </div>
    </div>
</header>

<div class="main-content">
    <div class="wrapper">
        <h2>Đổi Mật Khẩu</h2>
        
        <?php if($error != ""): ?>
            <div class="error-box"><i class="fas fa-exclamation-circle"></i> <?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Mật khẩu hiện tại</label>
            <input type="password" name="old_password" placeholder="Nhập mật khẩu cũ" required>

            <label>Mật khẩu mới</label>
            <input type="password" name="new_password" placeholder="Ít nhất 6 ký tự" required>

            <label>Xác nhận mật khẩu</label>
            <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>

            <button type="submit" class="btn-submit">XÁC NHẬN ĐỔI MẬT KHẨU <i class="fas fa-check-circle"></i></button>
        </form>

        <div class="back-link">
            <a href="../pages/giaodien.php"><i class="fas fa-arrow-left"></i> Quay lại trang chủ</a>
        </div>
    </div>
</div>

</body>
</html>