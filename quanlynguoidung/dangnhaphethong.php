
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
<title>ĐĂNG NHẬP - HỆ THỐNG KTX</title>

<style>

body{
    background: linear-gradient(135deg,#bdefff,#e7f8ff);
    font-family: Arial;
    height:100vh;
    margin:0;
    display:flex;
    justify-content:center;
    align-items:center;
}

.wrapper{
    width:520px;
    background:#fff;
    padding:45px 40px;
    border-radius:18px;
    box-shadow:0 12px 35px rgba(0,0,0,.2);
}

.system-title{
    text-align:center;
    font-size:26px;
    font-weight:bold;
    color:#0277bd;
    margin-bottom:25px;
}

.login-title{
    text-align:center;
    font-size:22px;
    font-weight:bold;
    margin-bottom:25px;
}

label{
    font-weight:bold;
    display:block;
    margin-top:10px;
    margin-bottom:6px;
}

input[type=text],
input[type=password]{
    width:100%;
    padding:14px;
    margin-bottom:15px;
    border-radius:10px;
    border:1px solid #ccc;
    font-size:15px;
}

.remember{
    margin-top:12px;
    margin-bottom:20px;
    font-size:14px;
}

button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:12px;
    background:#0288d1;
    color:#fff;
    font-size:17px;
    font-weight:bold;
    cursor:pointer;
}

button:hover{ background:#0277bd; }

.error{
    margin-top:16px;
    color:red;
    font-weight:bold;
    text-align:center;
}

.success{ 
    margin-top:16px;
    color:green; 
    font-weight:bold; 
    text-align:center;
}

.dashboard{ text-align:center; }

</style>
</head>

<body>

<?php if($logged): ?>

<div class="wrapper dashboard">

    <h2>Xin chào</h2>
    <h3><?= htmlspecialchars($_SESSION['fullname']) ?></h3>

    <p>Vai trò: <b><?= htmlspecialchars($_SESSION['role']) ?></b></p>

    <?php if($_SESSION['role']=="admin"): ?>
        <hr>
        <p>⚙ Khu vực ADMIN</p>
    <?php endif; ?>

    <br>

    <a href="thaydoimatkhau.php">
        <button style="background:#5cb85c; margin-bottom: 10px;">ĐỔI MẬT KHẨU</button>
    </a>
    <a href="?logout=1">
        <button>ĐĂNG XUẤT</button>
    </a>

</div>

<?php else: ?>

<div class="wrapper">

    <div class="system-title">
        HỆ THỐNG QUẢN LÝ KÝ TÚC XÁ
    </div>

    <div class="login-title">
        ĐĂNG NHẬP
    </div>

    <form method="POST">

        <label>Tên đăng nhập</label>
        <input type="text" name="username" required>

        <label>Mật khẩu</label>
        <input type="password" name="password" required>

        <div class="remember">
            <input type="checkbox" name="remember"> Ghi nhớ đăng nhập
        </div>

        <button type="submit">ĐĂNG NHẬP</button>

        <?php
            if(isset($_GET['timeout']))
                echo '<div class="error">Phiên đăng nhập đã hết hạn! Vui lòng đăng nhập lại.</div>';
            
            // HIỂN THỊ THÔNG BÁO ĐỔI MẬT KHẨU THÀNH CÔNG
            if(isset($_GET['change_success']))
                echo '<div class="success">Đổi mật khẩu thành công! Vui lòng đăng nhập lại bằng mật khẩu mới.</div>';

            if(!empty($error))
                echo '<div class="error">'.$error.'</div>';
        ?>

    </form>

</div>

<?php endif; ?>

</body>
</html>