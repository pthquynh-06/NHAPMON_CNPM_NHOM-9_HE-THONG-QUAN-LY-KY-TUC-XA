<?php
$servername = "localhost";
$username = "root";
$password = "";

// Kết nối MySQL (không chọn database trước)
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Tạo database nếu chưa tồn tại
$dbname = "quanlysv";
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// Tạo bảng sinhvien nếu chưa tồn tại
$sql_table = "CREATE TABLE IF NOT EXISTS sinhvien (
    masv VARCHAR(10) PRIMARY KEY,
    hoten VARCHAR(50),
    namsinh INT,
    lop VARCHAR(10)
)";
$conn->query($sql_table);

// Thêm dữ liệu mẫu nếu bảng rỗng
$result = $conn->query("SELECT COUNT(*) as count FROM sinhvien");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $conn->query("INSERT INTO sinhvien (masv, hoten, namsinh, lop) VALUES
    ('SV001', 'Nguyen Van A', 2003, 'CNTT1'),
    ('SV002', 'Tran Thi B', 2004, 'CNTT2'),
    ('SV003', 'Le Van C', 2003, 'CNTT1')");
}

// Xử lý tìm kiếm
$search = "";
$results = [];
if (isset($_POST['timkiem'])) {
    $search = $_POST['search'];
    $search = $conn->real_escape_string($search);
    $sql = "SELECT * FROM sinhvien WHERE masv LIKE '%$search%' OR hoten LIKE '%$search%'";
    $query = $conn->query($sql);
    if ($query) {
        $results = $query->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tìm kiếm sinh viên</title>
</head>
<body>
<h1>Tìm kiếm sinh viên</h1>
<form method="POST" action="">
    <input type="text" name="search" placeholder="Nhập mã sinh viên hoặc họ tên" value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" name="timkiem" value="Tìm kiếm">
</form>

<?php if (!empty($results)) { ?>
<table border="1" cellpadding="10">
<tr>
    <th>Mã SV</th>
    <th>Họ tên</th>
    <th>Năm sinh</th>
    <th>Lớp</th>
</tr>
<?php foreach ($results as $sv) { ?>
<tr>
    <td><?php echo $sv['masv']; ?></td>
    <td><?php echo $sv['hoten']; ?></td>
    <td><?php echo $sv['namsinh']; ?></td>
    <td><?php echo $sv['lop']; ?></td>
</tr>
<?php } ?>
</table>
<?php } elseif (isset($_POST['timkiem'])) { ?>
<p>Không tìm thấy sinh viên nào.</p>
<?php } ?>

</body>
</html>

<?php
$conn->close();
?>
