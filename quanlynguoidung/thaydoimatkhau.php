// CẬP NHẬT HOẠT ĐỘNG PHIÊN (Giúp tránh lỗi hết hạn phiên)
$_SESSION['last_activity'] = time();

// KẾT NỐI DATABASE
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli("localhost","root","","quanlynguoidung");
$conn->set_charset("utf8");

$error = "";
$success = "";

// LẤY USER ĐANG LOGIN (Sử dụng 'username' được lưu trong Session)
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?"; 
$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if(!$user){
    // Xử lý nếu không tìm thấy user hoặc phiên lỗi
    session_destroy();
    header("Location: dangnhaphethong.php");
    exit;
}

$currentHashedPass = $user['password'];
$user_id = $user['id'];