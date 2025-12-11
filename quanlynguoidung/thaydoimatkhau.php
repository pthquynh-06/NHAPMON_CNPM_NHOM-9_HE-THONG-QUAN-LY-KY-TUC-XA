// Khối Bảo Vệ Trang
session_start();
if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']){
    header("Location: dangnhaphethong.php");
    exit;
}
//... (Cập nhật hoạt động phiên nếu cần)
//...


//  Toàn bộ khối HTML/CSS/Form
<!DOCTYPE html>
<html lang="vi">
//... (Phần <head> và <style>)
<body>
<div class="wrapper">
<h2>ĐỔI MẬT KHẨU</h2>
//... (Hiển thị thông báo, xem mục HQ)
<form method="POST">
<label>Mật khẩu hiện tại</label>
<input type="password" name="old_password" required>

<label>Mật khẩu mới</label>
<input type="password" name="new_password" required>

<label>Xác nhận mật khẩu</label>
<input type="password" name="confirm_password" required>

<button type="submit">ĐỔI MẬT KHẨU</button>
</form>
//...
</div>
</body>
</html>

