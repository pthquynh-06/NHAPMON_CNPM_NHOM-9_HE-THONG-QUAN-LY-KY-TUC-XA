

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>ĐỔI MẬT KHẨU</title>
<style>
body{background:#e7f8ff;font-family:Arial;height:100vh;margin:0;display:flex;justify-content:center;align-items:center;}
.wrapper{width:400px;background:#fff;padding:35px;border-radius:15px;box-shadow:0 12px 30px rgba(0,0,0,.18);}
h2{text-align:center;margin-bottom:20px;}
label{font-weight:bold;display:block;}
input{width:100%;padding:12px;margin:8px 0 15px;border-radius:8px;border:1px solid #bbb;}
button{width:100%;padding:14px;border:none;border-radius:10px;background:#0288d1;color:#fff;font-weight:bold;cursor:pointer;}
button:hover{background:#0277bd;}
.error{color:red;font-weight:bold;text-align:center;margin-bottom:12px;}
.success{color:green;font-weight:bold;text-align:center;margin-bottom:12px;}
.back{text-align:center;margin-top:12px;}



</style>
</head>
<body>
<div class="wrapper">
<h2>ĐỔI MẬT KHẨU</h2>

<?php if($error != ""): ?>
<div class="error"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
<label>Mật khẩu hiện tại</label>
<input type="password" name="old_password" required>

<label>Mật khẩu mới</label>
<input type="password" name="new_password" required>

<label>Xác nhận mật khẩu</label>
<input type="password" name="confirm_password" required>

<button type="submit">ĐỔI MẬT KHẨU</button>
</form>

<div class="back">
<a href="dangnhaphethong.php">← Quay lại trang chính</a>
</div>
</div>
</body>
</html>

