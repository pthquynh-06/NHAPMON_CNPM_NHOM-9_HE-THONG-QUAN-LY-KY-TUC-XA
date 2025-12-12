<?php
session_start();

// BẢO VỆ TRANG (AC07 - Bảo mật trang)
if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']){
    header("Location: dangnhaphethong.php");
    exit;
}
// CẬP NHẬT HOẠT ĐỘNG PHIÊN (Giúp tránh lỗi hết hạn phiên)
$_SESSION['last_activity'] = time();

// KẾT NỐI DATABASE
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli("localhost","root","","quanlynguoidung");
$conn->set_charset("utf8");
$error = "";
$success = "";

// LẤY USER ĐANG LOGIN (Sử dụng 'username' được lưu trong Session)
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?"; 
$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if(!$user){
    // Xử lý nếu không tìm thấy user hoặc phiên lỗi
    session_destroy();
    header("Location: dangnhaphethong.php");
    exit;
}

$currentHashedPass = $user['password'];
$user_id = $user['id'];

// Khối Xử Lý ĐỔI MẬT KHẨU và Kiểm Tra Hợp Lệ
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $old = $_POST['old_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Kiểm tra đầu vào trống
    if(empty($old) || empty($new) || empty($confirm)){
        $error = "Vui lòng nhập đủ thông tin";
    }
    // AC02 – Kiểm tra mật khẩu hiện tại
    elseif(!password_verify($old, $currentHashedPass)){
        $error = "Mật khẩu hiện tại không đúng";
    }
    // AC04 – Kiểm tra quy tắc (độ dài)
    elseif(strlen($new) < 6){
        $error = "Mật khẩu mới phải ít nhất 6 ký tự";
    }
    // AC03 – Kiểm tra mật khẩu mới trùng khớp
    elseif($new !== $confirm){
        $error = "Xác nhận mật khẩu không khớp";
    }
    // AC05 – Mật khẩu mới không được trùng với mật khẩu cũ
    elseif(password_verify($new, $currentHashedPass)){
        $error = "Mật khẩu mới không được trùng với mật khẩu cũ";
    }
    else{
        // AC07 – Mã hóa mật khẩu mới 
        $newHashedPass = password_hash($new, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si",$newHashedPass,$user_id);

        if($stmt->execute()){
            // Xử lý bảo mật: HỦY PHIÊN và Cookie ngay sau khi đổi mật khẩu thành công
            session_unset();
            session_destroy();
            setcookie("remember_login","",time()-3600, "/");

            // Chuyển hướng người dùng về trang đăng nhập với thông báo thành công (AC06)
            header("Location: dangnhaphethong.php?change_success=1");
            exit;
        }
        else{
            $error = "Lỗi cập nhật mật khẩu";
    }
}
                             
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>ĐỔI MẬT KHẨU - KTX HƯỚNG DƯƠNG</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

<style>
/* GIAO DIỆN ĐỒNG BỘ 50% MỜ */
body {
    background: 
        linear-gradient(rgba(224, 244, 255, 0.5), rgba(224, 244, 255, 0.5)), 
        url('https://tnut.edu.vn/uploads/tinyfiles/uploads/files/anh-bai-viet/2022/12/14/qtpv/ky-tuc-xa/01.jpg'); 
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    font-family: 'Montserrat', sans-serif;
    height: 100vh;
    margin: 0;
    display: flex;
    flex-direction: column;
}

/* HEADER ĐỒNG BỘ */
.header-bar {
    width: 100%;
    padding: 10px 60px; 
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(5px);
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    box-sizing: border-box;
}

.brand-area {
    display: flex;
    align-items: center;
    gap: 15px;
}
.logo-img {
    width: 60px; 
    height: auto;
}
.brand-name-wrapper {
    display: flex;
    flex-direction: column;
}

.main-name {
    font-family: 'Playfair Display', serif;
    font-size: 35px; 
    font-weight: 900;
    color: #005a9e;
    font-style: italic;
    line-height: 1;
}
.sub-name {
    font-size: 14px;
    letter-spacing: 2px;
    color: #555;
    text-transform: uppercase;
    font-weight: 700;
}
.user-info {
    font-weight: 700; 
    color: #0078d4; 
    font-size: 14px;
    padding: 8px 15px;
    border: 2px solid #0078d4;
    border-radius: 50px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.main-container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.wrapper {
    width: 420px; 
    background: rgba(255, 255, 255, 0.98); 
    padding: 40px; 
    border-radius: 30px; 
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
}

h2 {
    text-align: center;
    font-family: 'Playfair Display', serif;
    font-size: 30px; 
    font-weight: 900;
    margin-bottom: 25px;
    color: #0078d4;
    text-transform: uppercase;
}

label {
    font-weight: 700;
    display: block;
    margin-top: 15px;
    margin-bottom: 8px;
    color: #333;
    font-size: 14px;
}

input {
    width: 100%;
    padding: 14px 18px; 
    margin-bottom: 5px;
    border-radius: 12px;
    border: 1px solid #ddd;
    background: #fdfdfd;
    font-size: 15px;
    box-sizing: border-box;
}

button {
    width: 100%;
    padding: 16px;
    border: none;
    border-radius: 50px;
    background: #0078d4;
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    margin-top: 25px;
    transition: 0.3s;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
}

button:hover {
    background: #005a9e;
}

.error {
    background: #fff0f0;
    color: #d93025;
    padding: 12px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 20px;
    text-align: center;
    border: 1px solid #ffd2d2;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.back {
    text-align: center;
    margin-top: 20px;
}

.back a {
    text-decoration: none;
    color: #666;
    font-weight: 600;
    font-size: 14px;
    transition: color 0.3s;
}
.back a:hover {
    color: #0078d4;
}
</style>
</head>
<body>

<header class="header-bar">
    <div class="brand-area">
        <svg class="logo-img" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="20" fill="#f57c00"/>
            <path d="M50 5L58 25H42L50 5Z" fill="#f57c00"/><path d="M50 95L42 75H58L50 95Z" fill="#f57c00"/>
            <path d="M95 50L75 42V58L95 50Z" fill="#f57c00"/><path d="M5 50L25 58V42L5 50Z" fill="#f57c00"/>
            <path d="M82 18L66 30L74 38L82 18Z" fill="#f57c00"/><path d="M18 82L34 70L26 62L18 82Z" fill="#f57c00"/>
        </svg>
        <div class="brand-name-wrapper">
            <span class="main-name">Hướng Dương</span>
            <span class="sub-name">Kí túc xá sinh viên</span>
        </div>
    </div>
    <div class="user-info">
        <i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['fullname']) ?>
    </div>
</header>

<div class="main-container">
    <div class="wrapper">
        <h2>Đổi Mật Khẩu</h2>

        <?php if($error != ""): ?>
            <div class="error"><i class="fas fa-exclamation-circle"></i> <?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Mật khẩu hiện tại</label>
            <input type="password" name="old_password" placeholder="Nhập mật khẩu cũ" required>

            <label>Mật khẩu mới</label>
            <input type="password" name="new_password" placeholder="Ít nhất 6 ký tự" required>

            <label>Xác nhận mật khẩu</label>
            <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>

            <button type="submit">XÁC NHẬN ĐỔI MẬT KHẨU <i class="fas fa-check-circle"></i></button>
        </form>

        <div class="back">
            <a href="dangnhaphethong.php"><i class="fas fa-arrow-left"></i> Quay lại trang chính</a>
        </div>
    </div>
</div>

</body>
</html>                             
