
<?php
session_start();

/*========== KẾT NỐI DATABASE ==========*/
$conn = new mysqli("localhost","root","","quanlynguoidung");
if ($conn->connect_error) {
    die("Lỗi kết nối DB");
}
$conn->set_charset("utf8");

/*========== AUTO LOGIN TỪ COOKIE (AC06) ==========*/
if(!isset($_SESSION['loggedin']) && isset($_COOKIE['remember_login'])){
    $username_cookie = $_COOKIE['remember_login'];
    $sql = "SELECT * FROM users WHERE username = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username_cookie);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows === 1){
        $user = $res->fetch_assoc();

        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['fullname'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();
    }
}

/*========== LOGOUT ==========*/
if(isset($_GET['logout'])){
    session_unset();
    session_destroy();
    setcookie("remember_login","",time()-3600, "/");
    header("Location: dangnhaphethong.php");
    exit;
}

/*========== BIẾN THÔNG BÁO ==========*/
$error = "";

/*========== LOGIN ==========*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    // AC02 – Không để trống
    if ($username === "" || $password === "") {
        $error = "Không được để trống thông tin!";
    }
    else {

        // AC03 – Kiểm tra tài khoản
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$username);
        $stmt->execute();
        $res = $stmt->get_result();

        if($res->num_rows !== 1){
            $error = "Sai tên đăng nhập hoặc mật khẩu!";
        }
        else{
            $user = $res->fetch_assoc();

           // AC07 – Kiểm tra mật khẩu an toàn
            if(!password_verify($password, $user['password'])){
                $error = "Sai tên đăng nhập hoặc mật khẩu!";
            }
            else{
                // AC04 – Đăng nhập thành công
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['fullname'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['last_activity'] = time();

                // AC06 – Ghi nhớ đăng nhập
                if($remember){
                    setcookie("remember_login", $user['username'], time()+7*24*3600,"/");
                }

                // Chuyển hướng người dùng về trang Dashboard (ở đây vẫn là dangnhaphethong.php)
                header("Location: dangnhaphethong.php");
                exit;
            }
        }
    }
}

/*========== SESSION TIMEOUT – AC08 ==========*/
$timeout = 900;
if(isset($_SESSION['last_activity']) 
&& time() - $_SESSION['last_activity'] > $timeout){

    session_unset();
    session_destroy();
    setcookie("remember_login","",time()-3600, "/");
    header("Location: dangnhaphethong.php?timeout=1");
    exit;
}

$logged = isset($_SESSION['loggedin']) && $_SESSION['loggedin'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>ĐĂNG NHẬP - KTX HƯỚNG DƯƠNG</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

<style>
/* Đã thêm CSS cho Dashboard và các thông báo lỗi/thành công để đồng bộ */
body {
    background: 
        linear-gradient(rgba(224, 244, 255, 0.5), rgba(224, 244, 255, 0.5)), 
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

.header-bar {
    width: 100%;
    padding: 10px 60px; 
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(5px);
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    box-sizing: border-box;
    z-index: 10;
}

.brand-area { display: flex; align-items: center; gap: 15px; }
.logo-img { width: 60px; height: auto; }
.brand-name-wrapper { display: flex; flex-direction: column; }

.main-name {
    font-family: 'Playfair Display', serif;
    font-size: 35px; 
    font-weight: 900;
    color: #005a9e;
    font-style: italic;
    line-height: 1;
}

.sub-name {
    font-size: 14px;
    letter-spacing: 2px;
    color: #555;
    text-transform: uppercase;
    font-weight: 700;
}

.home-link {
    text-decoration: none;
    color: #0078d4;
    font-weight: 700;
    font-size: 14px;
    padding: 8px 25px;
    border: 2px solid #0078d4;
    border-radius: 50px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: background 0.3s;
}
.home-link:hover { background: #e7f8ff; }

.main-container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.wrapper {
    width: 420px; 
    background: rgba(255, 255, 255, 0.98); 
    padding: 45px 40px; 
    border-radius: 30px; 
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    text-align: center; /* Thêm căn giữa cho dashboard */
}

.login-title {
    text-align: center;
    font-family: 'Playfair Display', serif;
    font-size: 40px; 
    font-weight: 900;
    margin-bottom: 30px;
    color: #0078d4;
    text-transform: uppercase;
}

label {
    font-weight: 700;
    display: block;
    text-align: left;
    margin-top: 15px;
    margin-bottom: 8px;
    color: #333;
    font-size: 14px;
}

input[type=text],
input[type=password] {
    width: 100%;
    padding: 15px 18px; 
    margin-bottom: 15px;
    border-radius: 12px;
    border: 1px solid #ddd;
    background: #fdfdfd;
    font-size: 16px;
    box-sizing: border-box;
}

.remember { 
    margin: 15px 0 20px 0;
    font-size: 14px; 
    color: #666;
    display: flex; 
    align-items: center; 
    gap: 8px;
    cursor: pointer;
    text-align: left;
}
.remember input[type=checkbox] { 
    cursor: pointer; 
    width: 17px; 
    height: 17px; 
    margin: 0; 
}

.btn-login {
    width: 100%;
    padding: 18px;
    border: none;
    border-radius: 50px;
    background: #0078d4;
    color: #fff;
    font-size: 18px; 
    font-weight: 700;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    margin-top: 20px;
    transition: background 0.3s;
}
.btn-login:hover { background: #005a9e; }

/* Styles cho Dashboard */
.dashboard h2 { color: #0078d4; margin-bottom: 10px; font-weight: 900; }
.dashboard h3 { margin: 0 0 20px 0; font-weight: 700; font-size: 24px; }
.dashboard p { margin: 5px 0 20px 0; font-size: 15px; }
.dashboard-actions a {
    text-decoration: none;
    display: block;
    padding: 15px;
    border-radius: 50px;
    font-weight: 700;
    margin-bottom: 12px;
    transition: background 0.3s;
}
.btn-change { background: #5cb85c; color: white; }
.btn-change:hover { background: #4cae4c; }
.btn-logout { background: #d9534f; color: white; }
.btn-logout:hover { background: #c9302c; }

/* Thông báo lỗi và thành công */
.message {
    margin-top: 20px;
    padding: 15px;
    border-radius: 10px;
    font-weight: 600;
    text-align: center;
    font-size: 14px;
}
.error { background: #fff0f0; color: #d93025; border: 1px solid #ffd2d2; }
.success { background: #f0fff4; color: #2daf50; border: 1px solid #c3e6cb; }
</style>
</head>

<body>

<header class="header-bar">
    <div class="brand-area">
        <svg class="logo-img" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="20" fill="#f57c00"/>
            <path d="M50 5L58 25H42L50 5Z" fill="#f57c00"/><path d="M50 95L42 75H58L50 95Z" fill="#f57c00"/>
            <path d="M95 50L75 42V58L95 50Z" fill="#f57c00"/><path d="M5 50L25 58V42L5 50Z" fill="#f57c00"/>
            <path d="M82 18L66 30L74 38L82 18Z" fill="#f57c00"/><path d="M18 82L34 70L26 62L18 82Z" fill="#f57c00"/>
        </svg>
        <div class="brand-name-wrapper">
            <span class="main-name">Hướng Dương</span>
            <span class="sub-name">Kí túc xá sinh viên</span>
        </div>
    </div>
    <a href="index.php" class="home-link"><i class="fas fa-home"></i> TRANG CHỦ</a>
</header>

<div class="main-container">
    <div class="wrapper">
    
    <?php if($logged): ?>
    
        <div class="dashboard">
            <h2>Xin chào</h2>
            <h3><?= htmlspecialchars($_SESSION['fullname']) ?></h3>

            <p>Vai trò: <b><?= htmlspecialchars($_SESSION['role']) ?></b></p>

            <?php if($_SESSION['role']=="admin"): ?>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
                <p style="font-weight: 700; color: #d9534f;"><i class="fas fa-lock"></i> Khu vực ADMIN</p>
            <?php endif; ?>
            
            <div class="dashboard-actions">
                <a href="thaydoimatkhau.php" class="btn-change">ĐỔI MẬT KHẨU</a>
                <a href="?logout=1" class="btn-logout">ĐĂNG XUẤT</a>
            </div>
        </div>

    <?php else: ?>

        <div class="login-title">Đăng Nhập</div>

        <form method="POST">
            <label>Tên đăng nhập</label>
            <input type="text" name="username" placeholder="Nhập tài khoản" required>
            
            <label>Mật khẩu</label>
            <input type="password" name="password" placeholder="••••••••" required>
            
            <div class="remember">
                <input type="checkbox" name="remember" id="remember_me"> 
                <label for="remember_me" style="display:inline; margin:0; cursor:pointer; font-weight: 400;">Duy trì đăng nhập</label>
            </div>
            <button type="submit" class="btn-login">ĐĂNG NHẬP <i class="fas fa-key"></i></button>
        </form>

        <?php
            // HIỂN THỊ CÁC THÔNG BÁO THEO LOGIC CŨ
            if(isset($_GET['timeout']))
                echo '<div class="message error"><i class="fas fa-exclamation-triangle"></i> Phiên đăng nhập đã hết hạn! Vui lòng đăng nhập lại.</div>';
            
            if(isset($_GET['change_success']))
                echo '<div class="message success"><i class="fas fa-check-circle"></i> Đổi mật khẩu thành công! Vui lòng đăng nhập lại bằng mật khẩu mới.</div>';

            if(!empty($error))
                echo '<div class="message error"><i class="fas fa-times-circle"></i> '.$error.'</div>';
        ?>
    
    <?php endif; ?>

    </div>
</div>

</body>
</html>
