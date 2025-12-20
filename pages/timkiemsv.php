<?php 
require_once '../includes/check_login.php'; 
require_once '../includes/db_config_sinhvien.php'; 

// 1. KIỂM TRA ĐĂNG NHẬP
if(!isset($_SESSION['loggedin'])){
    header("Location: ../quanlynguoidung/dangnhaphethong.php");
    exit;
}

// 2. XỬ LÝ BACKEND TÌM KIẾM (Đã sửa để hiển thị tất cả nếu query trống)
$search = $_GET['query'] ?? ''; 
$php_results = [];

// Truy vấn: Nếu $search trống, LIKE '%%' sẽ lấy tất cả bản ghi
$sql = "SELECT * FROM sinhvien WHERE hoten LIKE ? OR mssv LIKE ? OR sophong LIKE ? OR truong LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$php_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tìm kiếm sinh viên - KTX Hướng Dương</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        /* CSS bổ sung cho thông báo trống */
        .no-data-msg {
            text-align: center;
            padding: 30px;
            color: #6b7280;
            font-style: italic;
        }
        .contract-active { color: #059669; font-weight: bold; }
        .contract-expired { color: #dc2626; font-weight: bold; }
        .status-summary {
            margin-top: 15px;
            font-size: 0.9em;
            color: #4b5563;
        }
    </style>
</head>
<body>

<?php include '../includes/chung.php'; ?>

<main class="main">

    <div class="search-card">
        <h3><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm Sinh viên</h3><br>

        <form action="" method="GET" class="search-box">
            <input type="text" name="query" id="search-query" 
                   placeholder="Nhập mã SV, họ tên hoặc phòng..." 
                   value="<?= htmlspecialchars($search) ?>">
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
                    <th>Trường</th>
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
                            <td><?= htmlspecialchars($sv['truong']) ?></td>
                            <td class="<?= $sv['contract_status'] == 1 ? 'contract-active' : 'contract-expired' ?>">
                                <?= $sv['contract_status'] == 1 ? 'CÓ' : 'KHÔNG' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-data-msg">
                            <i class="fa-solid fa-folder-open"></i><br>
                            <?php if ($search == ''): ?>
                                Hiện tại chưa có sinh viên nào trong hệ thống.
                            <?php else: ?>
                                Không tìm thấy sinh viên nào khớp với từ khóa "<strong><?= htmlspecialchars($search) ?></strong>".
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="status-summary">
            <?php 
            if (!empty($php_results)) {
                echo "Tìm thấy " . count($php_results) . " kết quả.";
            }
            ?>
        </div>
    </div>
</main>

</body>
</html>