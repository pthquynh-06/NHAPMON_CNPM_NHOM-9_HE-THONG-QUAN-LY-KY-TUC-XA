<?php
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
        // ...
    }
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