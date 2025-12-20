<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli("localhost", "root", "", "quanlynguoidung");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Kết nối DB thất bại: " . $conn->connect_error);
}
?>



