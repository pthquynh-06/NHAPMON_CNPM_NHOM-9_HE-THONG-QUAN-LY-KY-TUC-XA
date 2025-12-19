<?php
session_start();

// 1. Kiểm tra nếu chưa đăng nhập thì đá về trang đăng nhập
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Lưu ý: Đường dẫn từ thư mục 'pages' ra ngoài là '../'
    header("Location: ../quanlynguoidung/dangnhaphethong.php");
    exit;
}

// 2. Kiểm tra hết hạn phiên (timeout 15 phút như bạn đã cài)
$timeout = 900; 
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    setcookie("remember_login", "", time() - 3600, "/");
    header("Location: ../quanlynguoidung/dangnhaphethong.php?timeout=1");
    exit;
}

// 3. Cập nhật thời gian hoạt động mới
$_SESSION['last_activity'] = time();
?>