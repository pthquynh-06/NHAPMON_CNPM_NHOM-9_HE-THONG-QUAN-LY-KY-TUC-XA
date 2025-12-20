<?php 
require_once '../includes/check_login.php'; 
require_once '../includes/db_config_sinhvien.php'; 

// 1. KIỂM TRA ĐĂNG NHẬP (AC06)
if(!isset($_SESSION['loggedin'])){
    header("Location: ../quanlynguoidung/dangnhaphethong.php");
    exit;
}

// 2. XỬ LÝ BACKEND TÌM KIẾM
$search = $_GET['query'] ?? ''; 
$php_results = [];

if (!empty($search)) {
    // Sử dụng đúng tên cột từ ảnh image_390868.jpg
    $sql = "SELECT * FROM sinhvien WHERE hoten LIKE ? OR mssv LIKE ? OR sophong LIKE ? OR lop LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$search%";
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $php_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Tìm kiếm sinh viên</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../assets/style.css">
</head>
<body onload="displayInitialData()">

<?php include '../includes/chung.php'; ?>

<main class="main">
    <div class="header">
        <div class="user-greeting" style="background-color: #2563eb; color: white; padding: 8px 15px; border-radius: 20px; font-size: 14px;">
            Xin chào, <?php echo isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'Khách'; ?>
        </div>
    </div>

    <div class="search-card">
        <h3><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm Sinh viên</h3><br>

        <form action="" method="GET" class="search-box">
            <input type="text" name="query" id="search-query" placeholder="Nhập mã SV, họ tên hoặc phòng" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">
                <i class="fa fa-search"></i> Tìm kiếm
            </button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Mã SV</th>
                    <th>Họ tên</th>
                    <th>Phòng</th>
                    <th>Lớp</th>
                    <th>Hợp đồng</th>
                </tr>
            </thead>
            <tbody id="results-tbody">
                <?php if (!empty($php_results)): ?>
                    <?php foreach ($php_results as $sv): ?>
                        <tr>
                            <td><?= htmlspecialchars($sv['mssv']) ?></td>
                            <td><?= htmlspecialchars($sv['hoten']) ?></td>
                            <td><?= htmlspecialchars($sv['sophong']) ?></td>
                            <td><?= htmlspecialchars($sv['lop']) ?></td>
                            <td class="<?= $sv['contract_status'] == 1 ? 'contract-active' : 'contract-expired' ?>">
                                <?= $sv['contract_status'] == 1 ? 'CÓ' : 'KHÔNG' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div id="status-message" class="status-message">
            <?php 
            if (!empty($search)) {
                echo empty($php_results) ? "Không tìm thấy kết quả cho '$search'" : "Tìm thấy " . count($php_results) . " sinh viên.";
            }
            ?>
        </div>
    </div>
</main>

<script>
// Giữ nguyên MockData và Logic hiển thị ban đầu của bạn
let mockData = {
    SV001:{name:"Nguyễn Văn Anh",room:"A201",contract:true, class:"CNTT01"},
    SV002:{name:"Trần Thị Ngọc",room:"B305",contract:false, class:"KT02"},
    SV003:{name:"Phạm Văn Quyền",room:"C101",contract:true, class:"DienTu01"},
};

const tbody = document.getElementById("results-tbody");
const status = document.getElementById("status-message");

function render(data){
    // Nếu có tìm kiếm PHP thì không chạy MockData
    <?php if(!empty($search)): ?> return; <?php endif; ?>

    tbody.innerHTML = data.map(s => `
        <tr>
            <td>${s.mssv}</td>
            <td>${s.name}</td>
            <td>${s.room}</td>
            <td>${s.class}</td>
            <td class="${s.contract ? 'contract-active' : 'contract-expired'}">
                ${s.contract ? 'CÓ' : 'KHÔNG'}
            </td>
        </tr>
    `).join("");
}

function displayInitialData(){
    <?php if (empty($search)): ?>
    const data = Object.keys(mockData).map(k => ({ mssv: k, ...mockData[k] }));
    render(data);
    status.innerText = `Hiển thị dữ liệu mẫu`;
    <?php endif; ?>
}
</script>
</body>
</html>