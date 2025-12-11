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

