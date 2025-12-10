<?php
/*
--THIẾT KẾ BẢNG USERS:
CREATE DATABASE QUANLYNGUOIDUNG;
USE QUANLYNGUOIDUNG;

CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role VARCHAR(20) NOT NULL,
    status VARCHAR(20) NOT NULL,
    PRIMARY KEY (id)
);

INSERT INTO users (id,email,password,full_name,role,status)
VALUES (01,'admin@gmail.com','123456','Quản trị viên','admin','active');

--Xóa LẼ LOGIC ĐĂNG NHẬP:
<?php
session_start();
require 'db_connect.php';
...
?>

  (Phần SQL / ghi chú gốc kết thúc)
*/
?>

<?php
// Bắt đầu PHP chính thức - phần này là code thực thi

session_start();

// kết nối DB - nếu bạn dùng file db_connect.php thì include thay dòng dưới
$host = "localhost";
$user = "root";
$pass = "";
$db   = "quanlynguoidung";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Lỗi kết nối DB: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// Biến hiển thị lỗi
$error = "";

// XỬ LÝ ĐĂNG XUẤT (nếu có)
if (isset($_GET['logout'])) {
    // hủy session và redirect về trang login
    session_unset();
    session_destroy();
    header("Location: quanlynguoidung.php");
    exit;
}

// XỬ LÝ ĐĂNG NHẬP (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // AC02 - kiểm tra rỗng
    if ($username === '' || $password === '') {
        $error = "Không được để trống thông tin!";
    } else {
        // AC03 - kiểm tra tồn tại tài khoản (sử dụng email field trong DB)
        $sql = "SELECT * FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows !== 1) {
                $error = "Sai thông tin đăng nhập!";
            } else {
                $user = $result->fetch_assoc();

                // AC07 - kiểm tra mật khẩu
                // NOTE: Nếu trong DB bạn lưu password chưa hash (ví dụ '123456'),
                // bạn có thể tạm dùng so sánh trực tiếp (CHỈ DÙNG CHO TEST).
                // Tuy nhiên chuẩn là lưu hash và dùng password_verify().
                // Nếu bạn đã import hash rồi, dùng password_verify($password,$user['password'])
                $dbPassword = $user['password'];

                $passwordOk = false;
                // nếu password trong db có ký tự $2y$ (bcrypt) => dùng password_verify
                if (strpos($dbPassword, '$2y$') === 0 || strpos($dbPassword, '$2a$') === 0 || strpos($dbPassword, '$argon') !== false) {
                    if (password_verify($password, $dbPassword)) {
                        $passwordOk = true;
                    }
                } else {
                    // fallback nếu bạn chèn mật khẩu plaintext trong DB (chỉ dùng khi test)
                    if ($password === $dbPassword) {
                        $passwordOk = true;
                    }
                }

                if (! $passwordOk) {
                    $error = "Sai mật khẩu!";
                } else {
                    // AC04 - đăng nhập thành công
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $user['email'];
                    // nếu bạn muốn dùng full_name thì lấy $user['full_name']
                    $_SESSION['fullname'] = $user['full_name'] ?? $user['email'];
                    $_SESSION['role'] = $user['role'] ?? 'user';
                    $_SESSION['last_activity'] = time();

                    // AC06 - ghi nhớ đăng nhập (remember me)
                    if (isset($_POST['remember'])) {
                        $token = bin2hex(random_bytes(32));
                        setcookie("remember_user", $token, time() + 7*24*3600, "/");
                        // lưu token vào DB
                        $upd = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                        if ($upd) {
                            $upd->bind_param("si", $token, $user['id']);
                            $upd->execute();
                            $upd->close();
                        }
                    }

                    // chuyển về cùng file để hiển thị dashboard
                    header("Location: quanlynguoidung.php");
                    exit;
                }
            }
            $stmt->close();
        } else {
            $error = "Lỗi truy vấn DB.";
        }
    }
}

// AC06 - auto-login từ cookie remember (nếu có và chưa đăng nhập)
if (!isset($_SESSION['loggedin']) && isset($_COOKIE['remember_user'])) {
    $token = $_COOKIE['remember_user'];
    $q = $conn->prepare("SELECT * FROM users WHERE remember_token = ?");
    if ($q) {
        $q->bind_param("s", $token);
        $q->execute();
        $res = $q->get_result();
        if ($res && $res->num_rows === 1) {
            $u = $res->fetch_assoc();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $u['email'];
            $_SESSION['fullname'] = $u['full_name'] ?? $u['email'];
            $_SESSION['role'] = $u['role'] ?? 'user';
            $_SESSION['last_activity'] = time();
        }
        $q->close();
    }
}

// AC08 - session timeout: 15 phút (900 giây)
$timeout = 900;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header("Location: quanlynguoidung.php?timeout=1");
    exit;
}

// nếu đã đăng nhập thì hiển thị dashboard (phần HTML dưới)
$logged = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng - KTX</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        .box { max-width:480px; margin:0 auto; border:1px solid #ddd; padding:16px; border-radius:6px; }
        input[type=text], input[type=password] { width:100%; padding:8px; margin:6px 0; box-sizing:border-box; }
        button { padding:8px 16px; }
        .error { color:#b30000; }
        .meta { color:#555; font-size:0.9em; margin-bottom:10px; }
    </style>
</head>
<body>

<?php if ($logged): ?>

    <div class="box">
        <h2>Dashboard</h2>
        <p class="meta">Xin chào <strong><?php echo htmlspecialchars($_SESSION['fullname']); ?></strong></p>
        <p>Vai trò: <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong></p>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <hr>
            <h3>Phần dành cho admin</h3>
            <p>Ở đây bạn có thể thêm chức năng quản trị.</p>
        <?php endif; ?>

        <p><a href="quanlynguoidung.php?logout=1">Đăng xuất</a></p>
    </div>

<?php else: ?>

    <div class="box">
        <h2>Đăng nhập hệ thống</h2>

        <?php
        if (isset($_GET['timeout'])) {
            echo '<p class="error">Phiên đăng nhập hết hạn, vui lòng đăng nhập lại.</p>';
        }
        if ($error !== "") {
            echo '<p class="error">'.htmlspecialchars($error).'</p>';
        }
        ?>

        <form method="post" action="quanlynguoidung.php">
            <label>Email / Tên đăng nhập</label>
            <input type="text" name="username" placeholder="Nhập email hoặc username" required>

            <label>Mật khẩu</label>
            <input type="password" name="password" placeholder="Mật khẩu" required>

            <label><input type="checkbox" name="remember"> Ghi nhớ đăng nhập</label><br><br>

            <button type="submit" name="login">Đăng nhập</button>
        </form>
    </div>

<?php endif; ?>

</body>
</html>