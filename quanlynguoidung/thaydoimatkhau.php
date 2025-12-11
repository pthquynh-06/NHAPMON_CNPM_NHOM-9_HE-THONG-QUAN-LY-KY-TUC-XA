<?php
session_start();

// BẢO VỆ TRANG (AC07 - Bảo mật trang)
if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']){
    header("Location: dangnhaphethong.php");
    exit;
}


