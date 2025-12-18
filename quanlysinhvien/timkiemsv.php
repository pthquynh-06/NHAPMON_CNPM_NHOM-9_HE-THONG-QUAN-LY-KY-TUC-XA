<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tổng quan & Quản lý Sinh viên | KTX Hướng Dương</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

<style>
/* ================= RESET ================= */
 *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:"Segoe UI", Tahoma, sans-serif;
        }

        body{
            display:flex;
            height:100vh;
            background:#f4f6f9;
            overflow:hidden;
        }

        .sidebar {
            width: 260px;
            background: #242e53;
            color: #fff;
            padding: 20px;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            transition: 0.3s;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
            transition: 0.3s;
        }

        .sidebar.collapsed h1 {
            font-size: 0;
        }

        /* Nút thu gọn */
        .toggle-btn {
            position: absolute;
            top: 10px;
            right: 23px;
            width: 30px;
            height: 30px;
            background: #2563eb;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
        }
        
        /* Menu */
        .menu {
            list-style: none;
            margin-top: 20px;
        }

        .menu li {
            margin-bottom: 10px; 
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            color: #e5e7eb;
            text-decoration: none;
            border-radius: 10px;
            transition: 0.3s;
            font-size: 16px;
            cursor: pointer;
        }
        
         .menu a i{
            width:20px;
            text-align:center;
        }

        .menu a:hover{
            background:rgba(255,255,255,0.08);
        }

        .menu a.active{
            background:linear-gradient(90deg,#2563eb,#1d4ed8);
            color:#fff;
        }

        /* Mục có Submenu */
        .has-sub > a {
            justify-content: space-between;
        }

        .arrow {
            margin-left: auto;
            transition: 0.3s;
        }

        /* Submenu - Cấu hình Slide Toggle */
        .submenu {
            list-style: none;
            margin-top: 5px; 
            margin-left: 36px;
            display: none;
        }

        .submenu li a {
            padding: 10px 14px; 
            font-size: 14px;
            color: #c7d2fe;
            display: block; 
            border-radius: 8px;
        }
        
        /* Mở submenu */
        .has-sub.open .submenu {
            display: block;
        }
        
        .has-sub.open .arrow {
            transform: rotate(180deg);
        }

        .submenu li a:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        /* Khi thu gọn */
        .sidebar.collapsed span,
        .sidebar.collapsed .submenu,
        .sidebar.collapsed .arrow { 
            display: none; 
        }

        .sidebar.collapsed .menu a {
            justify-content: center;
        }

        /* Logout */
        .logout {
            margin-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
        }

        .logout a {
            color: #f87171;
        }

        /* ================= MAIN CONTENT ================= */
        .main {
            margin-left: 260px;
            padding: 30px;
            flex:1;
            overflow-y:auto;
            transition: 0.3s;
        }

        .sidebar.collapsed ~ .main {
            margin-left: 80px;
        }
/* ================= HEADER & CARDS (Bố cục 2x2) ================= */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.header h2{
    font-size:28px;
    font-weight:700;
    color:#111827;
}

.user{
    background:#2563eb;
    color:#fff;
    padding:10px 18px;
    border-radius:20px;
    font-size:14px;
}

/* CARD LAYOUT: 2 CỘT ĐỀU NHAU */
.cards{
    display:grid;
    /* Chia lưới thành 2 cột đều nhau */
    grid-template-columns: repeat(2, 1fr); 
    gap:20px;
    margin-bottom:30px;
}

.card{
    border-radius:16px;
    padding:20px;
    color:#fff;
    box-shadow:0 10px 20px rgba(0,0,0,0.1);
}

.card h3 {
    font-size: 16px;
    margin-bottom: 5px;
}

.card p {
    font-size: 28px;
    font-weight: 700;
}
/* ================= SEARCH CARD ================= */
.search-card{
    background:#fff;
    border-radius:12px;
    padding:30px;
    max-width:100%;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

.search-card h3{
    font-weight: 700;
    margin-bottom: 20px;
}

.search-box{
    display:flex;
    gap:10px;
    margin-bottom:25px;
}

.search-box input{
    flex:1;
    padding:12px;
    border-radius:8px;
    border:1px solid #ccc;
    font-size: 16px;
}

.search-box button{
    background:#2563eb;
    color:#fff;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    cursor:pointer;
    font-size: 16px;
    transition: background 0.2s;
}
.search-box button:hover {
    background: #1d4ed8;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:left;
}

th{
    background:#f5f5f5;
    text-transform:uppercase;
    font-size:13px;
    font-weight:700;
}

.delete-btn{
    background:none;
    border:none;
    color:#dc3545;
    cursor:pointer;
    font-size:16px;
}

.contract-active{ color:#28a745; font-weight:600; } 
.contract-expired{ color:#dc3545; font-weight:600; }

.status-message{
    margin-top:15px;
    font-weight:600;
    color: #4b5563;
}
/* Thêm vào cuối cùng của phần <style> */
.search-card {
    display: none; /* Ẩn toàn bộ khung tìm kiếm khi vừa load trang */
}
/* Đoạn này điều khiển màu xanh khi menu được kích hoạt */
.menu a.active {
    background: linear-gradient(90deg, #2563eb, #1d4ed8) !important;
    color: #fff !important;
}
</style>
</head>

<body onload="displayInitialData()">

<aside class="sidebar" id="sidebar">

    <div class="toggle-btn" onclick="toggleSidebar()">
        <i class="fa-solid fa-angle-left" id="toggleIcon"></i>
    </div>

    <h1>Ký Túc Xá<br>Hướng Dương</h1>

    <ul class="menu">

        <li>
            <a class="active">
                <i class="fa-solid fa-house"></i>
                <span>Tổng quan</span>
            </a>
        </li>

        <li class="has-sub">
            <a onclick="toggleSubmenu(this)">
                <i class="fa-solid fa-user"></i>
                <span>Sinh viên</span>
                <i class="fa-solid fa-chevron-down arrow"></i>
            </a>
            <ul class="submenu">
                <li><a>Danh sách sinh viên</a></li>
                <li><a>Thêm sinh viên</a></li>
                <li><a href="javascript:void(0)" onclick="showSearch()">Tìm kiếm sinh viên</a></li>
            </ul>
        </li>

        <li class="has-sub">
            <a onclick="toggleSubmenu(this)">
                <i class="fa-solid fa-door-open"></i>
                <span>Phòng ở</span>
                <i class="fa-solid fa-chevron-down arrow"></i>
            </a>
            <ul class="submenu">
                <li><a>Danh sách phòng</a></li>
                <li><a>Phòng trống</a></li>
                <li><a>Cập nhật trạng thái phòng</a></li>
            </ul>
        </li>

        <li class="has-sub">
            <a onclick="toggleSubmenu(this)">
                <i class="fa-solid fa-book"></i>
                <span>Hợp đồng</span>
                <i class="fa-solid fa-chevron-down arrow"></i>
            </a>
            <ul class="submenu">
                <li><a>Danh sách hợp đồng</a></li>
                <li><a>Lập hợp đồng</a></li>
                 <li><a>Sửa hợp đồng</a></li>
                <li><a>Thanh lý hợp đồng</a></li>
            </ul>
        </li>

        <li class="has-sub">
            <a onclick="toggleSubmenu(this)">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Hóa đơn</span>
                <i class="fa-solid fa-chevron-down arrow"></i>
            </a>
            <ul class="submenu">
                <li><a>Tạo hóa đơn</a></li>
                <li><a>Danh sách hóa đơn</a></li>
                <li><a>Cập nhật trạng thái thanh toán</a></li>
                <li><a>In hóa đơn</a></li>
            </ul>
        </li>
        <li class="has-sub">
            <a onclick="toggleSubmenu(this)">
                <i class="fa-solid fa-headset"></i>
                <span>Hỗ trợ</span>
                <i class="fa-solid fa-chevron-down arrow"></i>
            </a>
            <ul class="submenu">
                <li><a>Bảng tiếp nhận yêu cầu</a></li>
                <li><a>Danh sách yêu cầu</a></li>
                <li><a>Trạng thái xử lý</a></li>
            </ul>
        </li>
    </ul>

    <div class="logout">
        <a><i class="fa-solid fa-power-off"></i><span> Đăng xuất</span></a>
    </div>

</aside>

<main class="main">
    <div class="header">
        <h2></h2>
        <div class="user">Xin chào, Quản lý </div>
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
/* ===== SIDEBAR FUNCTIONS ===== */
function toggleSidebar(){
    const sidebar = document.getElementById("sidebar");
    const icon = document.getElementById("toggleIcon");
    sidebar.classList.toggle("collapsed");
    icon.classList.toggle("fa-angle-left");
    icon.classList.toggle("fa-angle-right");
}

function toggleSubmenu(el){
    el.parentElement.classList.toggle("open");
}

/* ===== DATA & SEARCH FUNCTIONS ===== */
let mockData={
    SV001:{name:"Nguyễn Văn Anh",room:"A201",contract:true},
    SV002:{name:"Trần Thị Ngọc",room:"B305",contract:false},
    SV003:{name:"Phạm Văn Quyền",room:"C101",contract:true},
    SV004:{name:"Hoàng Văn D",room:"C101",contract:true},
    SV005:{name:"Lê Thị E",room:"A202",contract:false},
};

const tbody=document.getElementById("results-tbody");
const status=document.getElementById("status-message");
const searchQuery=document.getElementById("search-query"); 

function render(data){
    tbody.innerHTML=data.map(s=>`
        <tr>
            <td>${s.mssv}</td>
            <td>${s.name}</td>
            <td>${s.room}</td>
            <td class="${s.contract?'contract-active':'contract-expired'}">
                ${s.contract?'CÓ':'KHÔNG'}
            </td>
            <td>
                <button class="delete-btn" onclick="deleteSV('${s.mssv}')">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>`).join("");
}

function displayInitialData(){
    const data=Object.keys(mockData).map(k=>({mssv:k,...mockData[k]}));
    render(data);
    status.textContent=`Hiển thị ${data.length} sinh viên`;
}

function performSearch(){
    const q=searchQuery.value.toUpperCase();
    const data=Object.keys(mockData)
        .filter(k=>k.includes(q)||mockData[k].name.toUpperCase().includes(q))
        .map(k=>({mssv:k,...mockData[k]}));
    render(data);
    status.textContent=`Tìm thấy ${data.length} kết quả`;
}

function deleteSV(mssv){
    if(mockData[mssv].contract){
        alert("Sinh viên còn hợp đồng – không thể xóa");
        return;
    }
    if(confirm("Xóa sinh viên "+mssv+"?")){
        delete mockData[mssv];
        performSearch();
    }
}
/* Thêm hàm này vào cuối phần <script> */
function showSearch() {
    // Tìm đến khung tìm kiếm và cho hiện nó lên
    document.querySelector('.search-card').style.display = 'block';
    
    // Gọi hàm hiển thị dữ liệu có sẵn trong code của bạn
    displayInitialData();
    
    // Xóa chữ ở tiêu đề h2 (nếu muốn giống ảnh bạn gửi là tiêu đề trống)
    document.querySelector('.header h2').innerText = 'Tìm kiếm Sinh viên';
}
document.querySelectorAll('.menu a').forEach(item => {
    item.addEventListener('click', function() {
        // 1. Xử lý nhảy màu xanh (Giữ nguyên)
        document.querySelectorAll('.menu a').forEach(nav => nav.classList.remove('active'));
        this.classList.add('active');

        // 2. Xử lý ẩn/hiện và tiêu đề
        const menuText = this.innerText.trim();
        const searchCard = document.querySelector('.search-card');
        const headerTitle = document.querySelector('.header h2'); // Thẻ h2 ở đầu trang

        // KHÔNG đặt lại innerText nữa để tiêu đề luôn trống
        headerTitle.innerText = ''; 

        if (menuText === "Tìm kiếm sinh viên") {
            searchCard.style.display = 'block';
            displayInitialData();
        } else {
            searchCard.style.display = 'none';
        }
    });
});
</script>

</body>
</html>