<?php
// Mật khẩu thường (người dùng nhập)
$plainPassword = "123456";  // hoặc $_POST['password']

// Mã hóa thành mật khẩu hash
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// In ra mật khẩu hash
echo "Mật khẩu hash: " . $hashedPassword;
?>