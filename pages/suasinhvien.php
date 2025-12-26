<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../includes/db_config_sinhvien.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $mssv = $_POST['mssv'] ?? '';
    $hoten = trim($_POST['hoten'] ?? '');
    $ngaysinh = $_POST['ngaysinh'] ?? '';
    $cccd = trim($_POST['cccd'] ?? '');
    $sodienthoai = trim($_POST['sodienthoai'] ?? '');
    $sophong = trim($_POST['sophong'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $ngaybatdau = $_POST['ngaybatdau'] ?? '';

    // AC02: Kiểm tra dữ liệu bắt buộc (Trừ Trường và Quê quán)
    if (empty($hoten)) $errors['hoten'] = "Họ tên không được để trống!";
    if (empty($ngaysinh)) $errors['ngaysinh'] = "Ngày sinh không được để trống!";
    if (empty($cccd)) $errors['cccd'] = "Số CCCD không được để trống!";
    if (empty($sodienthoai)) $errors['sodienthoai'] = "Số điện thoại không được để trống!";
    if (empty($email)) $errors['email'] = "Email không được để trống!";
    if (empty($sophong)) $errors['sophong'] = "Số phòng không được để trống!";
    if (empty($ngaybatdau)) $errors['ngaybatdau'] = "Ngày bắt đầu không được để trống!";

    // AC03: Kiểm tra định dạng toàn bộ
    // Bắt buộc email có đuôi 3 ký tự trở lên (loại bỏ .co, .c)
    $emailPattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{3,}$/";
    if (empty($email)) {
        $errors['email'] = "Email không được để trống!";
    } elseif (!preg_match($emailPattern, $email)) {
        $errors['email'] = "Định dạng email phải đầy đủ abc@gmail.com!";
    }
    if (!empty($cccd) && !preg_match('/^[0-9]{12}$/', $cccd)) {
        $errors['cccd'] = "CCCD phải bao gồm đúng 12 chữ số!";
    }
    if (!empty($sodienthoai) && !preg_match('/^(0[3|5|7|8|9])[0-9]{8}$/', $sodienthoai)) {
        $errors['sodienthoai'] = "Số điện thoại phải đúng định dạng 10 số!";
    }

    // AC04: Kiểm tra trùng email
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT mssv FROM sinhvien WHERE (email = ? OR cccd = ? OR sodienthoai = ?) AND mssv != ?");
        $stmt->bind_param("ssss", $email, $cccd, $sodienthoai, $mssv);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors['system_error'] = "Dữ liệu (Email/CCCD/SĐT) đã tồn tại trên hệ thống!";
        }
    }

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    // AC05: Lưu thay đổi & AC07: Lỗi hệ thống
    try {
        $sql = "UPDATE sinhvien SET hoten=?, gioitinh=?, ngaysinh=?, cccd=?, sodienthoai=?, 
                sophong=?, truong=?, email=?, quequan=?, ngaybatdau=? WHERE mssv=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssss", $hoten, $_POST['gioitinh'], $ngaysinh, $cccd, 
                          $sodienthoai, $sophong, $_POST['truong'], $email, $_POST['quequan'], 
                          $ngaybatdau, $mssv);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception("Lỗi database: " . $conn->error);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'errors' => ['system_error' => $e->getMessage()]]);
    }
}
?>