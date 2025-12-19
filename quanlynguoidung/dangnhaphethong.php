<?php
// 1. Nhúng file cấu hình (Đảm bảo đường dẫn này đúng với file của bạn
require_once '../includes/db_config.php';

// 2. Xử lý Đăng xuất
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    setcookie("remember_login", "", time() - 3600, "/");
    header("Location: dangnhaphethong.php");
    exit;
}

// 3. TỰ ĐỘNG CHUYỂN HƯỚNG (Phần này quan trọng để không bị thừa trang chào)
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: ../pages/giaodien.php");
    exit;
}

// 4. XỬ LÝ ĐĂNG NHẬP
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if ($username === "" || $password === "") {
        $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!";
    } else {
        // Truy vấn kiểm tra tài khoản
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $user = $res->fetch_assoc();
            // Kiểm tra mật khẩu mã hóa
            if (password_verify($password, $user['password'])) {
                // Lưu thông tin vào Session
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['fullname'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['last_activity'] = time();

                // Ghi nhớ đăng nhập bằng Cookie (7 ngày)
                if ($remember) {
                    setcookie("remember_login", $user['username'], time() + 7 * 24 * 3600, "/");
                }

                // Đăng nhập thành công -> Vào thẳng giao diện chính
                header("Location: ../pages/giaodien.php");
                exit;
            }
        }
        $error = "Tên đăng nhập hoặc mật khẩu không chính xác!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ĐĂNG NHẬP - KTX HƯỚNG DƯƠNG</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(224, 244, 255, 0.4), rgba(224, 244, 255, 0.4)), 
                        url('https://tnut.edu.vn/uploads/tinyfiles/uploads/files/anh-bai-viet/2022/12/14/qtpv/ky-tuc-xa/01.jpg'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* HEADER CHỈ CÒN LOGO */
        .header-bar {
            width: 100%;
            padding: 15px 60px; 
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }

        .brand-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .main-name {
            font-family: 'Playfair Display', serif;
            font-size: 35px; 
            font-weight: 900;
            color: #005a9e;
            font-style: italic;
            line-height: 1.1;
        }

        .sub-name {
            font-size: 14px;
            font-weight: 700;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 2px;
        }
        /* FORM ĐĂNG NHẬP */
        .main-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .wrapper {
            width: 400px; 
            background: rgba(255, 255, 255, 0.98); 
            padding: 40px; 
            border-radius: 25px; 
            box-shadow: 0 15px 50px rgba(0,0,0,0.2);
        }
        .brand-name-wrapper {
            display: flex;
            flex-direction: column; /* Xếp theo cột để dòng sub-name nằm dưới */
            justify-content: center;
        }
        .login-title {
            text-align: center;
            font-family: 'Playfair Display', serif;
            font-size: 35px; 
            font-weight: 900;
            margin-bottom: 30px;
            color: #0078d4;
            text-transform: uppercase;
        }

        label {
            font-weight: 700;
            display: block;
            margin-bottom: 8px;
            color: #444;
            font-size: 14px;
        }

        input[type=text], input[type=password] {
            width: 100%;
            padding: 14px; 
            margin-bottom: 20px;
            border-radius: 10px;
            border: 1px solid #ddd;
            background: #fdfdfd;
            font-size: 15px;
            box-sizing: border-box;
        }

        .remember-box {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 25px;
            font-size: 14px;
            color: #666;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 50px;
            background: #0078d4;
            color: #fff;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-login:hover {
            background: #005a9e;
        }

        .error-msg {
            background: #fff0f0;
            color: #d93025;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 20px;
            text-align: center;
            border: 1px solid #ffd2d2;
        }
    </style>
</head>
<body>

<header class="header-bar">
    <div class="brand-area">
        <div class="logo-container">
            <i class="fa-solid fa-sun" style="font-size: 40px; color: #f97316;"></i>
        </div>
        
        <div class="brand-name-wrapper">
            <span class="main-name">Hướng Dương</span>
            <span class="sub-name">Kí túc xá sinh viên</span>
        </div>
    </div>
</header>

<div class="main-container">
    <div class="wrapper">
        <?php if(isset($_GET['change_success'])): ?>
            <div style="background: #e6fffa; color: #2c7a7b; padding: 12px; border-radius: 10px; margin-bottom: 20px; text-align: center; border: 1px solid #b2f5ea; font-weight: 600;">
                <i class="fas fa-check-circle"></i> Đổi mật khẩu thành công! Vui lòng đăng nhập lại.
            </div>
        <?php endif; ?>
        <div class="login-title">Đăng Nhập</div>

        <form method="POST">
            <label>Tên đăng nhập</label>
            <input type="text" name="username" placeholder="Nhập tài khoản" required>

            <label>Mật khẩu</label>
            <input type="password" name="password" placeholder="••••••••" required>

            <label class="remember-box">
                <input type="checkbox" name="remember"> Duy trì đăng nhập
            </label>

            <button type="submit" class="btn-login">
                ĐĂNG NHẬP <i class="fas fa-sign-in-alt"></i>
            </button>
        </form>

        <?php if(!empty($error)): ?>
            <div class="error-msg">
                <i class="fas fa-times-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php 
        // Hiển thị thông báo nếu hết hạn phiên từ trang khác đá về
        if(isset($_GET['timeout'])) {
            echo '<div class="error-msg" style="color:#856404; background:#fff3cd; border-color:#ffeeba;">Phiên làm việc đã hết hạn.</div>';
        }
        ?>
    </div>
</div>

</body>
</html>
