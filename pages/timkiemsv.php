<?php 
require_once '../includes/check_login.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Tìm kiếm sinh viên</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet"
 href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<link rel="stylesheet" href="../assets/style.css">
</head>

<body onload="displayInitialData()">

<?php include '../includes/chung.php'; ?>

<main class="main">
    <div class="header">
        <h2></h2>
        <div class="user-greeting" style="background-color: #2563eb; color: white; padding: 8px 15px; border-radius: 20px; font-size: 14px;">
            Xin chào, <?php echo isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'Khách'; ?></div>
    </div>

    <div class="search-card">
        <h3><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm Sinh viên</h3><br>

        <div class="search-box">
            <input type="text" id="search-query" placeholder="Nhập mã SV hoặc họ tên">
            <button onclick="performSearch()">
                <i class="fa fa-search"></i> Tìm kiếm
            </button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Mã SV</th>
                    <th>Họ tên</th>
                    <th>Phòng</th>
                    <th>Hợp đồng</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="results-tbody"></tbody>
        </table>

        <div id="status-message" class="status-message"></div>
    </div>
</main>

<script>
let mockData = {
    SV001:{name:"Nguyễn Văn Anh",room:"A201",contract:true},
    SV002:{name:"Trần Thị Ngọc",room:"B305",contract:false},
    SV003:{name:"Phạm Văn Quyền",room:"C101",contract:true},
};

const tbody = document.getElementById("results-tbody");
const status = document.getElementById("status-message");
const searchQuery = document.getElementById("search-query");

function render(data){
    tbody.innerHTML = data.map(s => `
        <tr>
            <td>${s.mssv}</td>
            <td>${s.name}</td>
            <td>${s.room}</td>
            <td class="${s.contract ? 'contract-active' : 'contract-expired'}">
                ${s.contract ? 'CÓ' : 'KHÔNG'}
            </td>
            <td></td>
        </tr>
    `).join("");
}

function displayInitialData(){
    const data = Object.keys(mockData).map(k => ({
        mssv: k,
        ...mockData[k]
    }));
    render(data);
    status.innerText = `Hiển thị ${data.length} sinh viên`;
}

function performSearch(){
    const q = searchQuery.value.toUpperCase();
    const data = Object.keys(mockData)
        .filter(k => k.includes(q) || mockData[k].name.toUpperCase().includes(q))
        .map(k => ({mssv:k, ...mockData[k]}));

    render(data);
    status.innerText = `Tìm thấy ${data.length} kết quả`;
}
</script>
<script src="../assets/app.js"></script> 
</body>
</html>