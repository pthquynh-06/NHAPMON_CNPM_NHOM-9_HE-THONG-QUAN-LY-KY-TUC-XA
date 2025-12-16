<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Ký túc xá Hướng Dương</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
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

        /* ===== SIDEBAR ===== */
        .sidebar{
            width:260px;
            background:#242e53;
            color:#fff;
            padding:20px;
            position:fixed;
            top:0;
            bottom:0;
            left:0;
            overflow-y:auto;
            transition:0.3s;
        }

        .sidebar.collapsed{
            width:80px;
        }

        .sidebar h1{
            font-size:24px;
            text-align:center;
            margin-bottom:30px;
            font-weight:700;
            transition:0.3s;
        }

        .sidebar.collapsed h1{
            font-size:0;
        }

        .toggle-btn{
            position:absolute;
            top:10px;
            right:23px;
            width:30px;
            height:30px;
            background:#2563eb;
            color:#fff;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            cursor:pointer;
            z-index:10;
        }

        .menu{
            list-style:none;
            margin-top:20px;
        }

        .menu li{
            margin-bottom:10px;
        }

        .menu a{
            display:flex;
            align-items:center;
            gap:14px;
            padding:14px 16px;
            color:#e5e7eb;
            text-decoration:none;
            border-radius:10px;
            transition:0.3s;
            font-size:16px;
            cursor:pointer;
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

        /* ===== SUBMENU ===== */
        .has-sub > a{
            justify-content:space-between;
        }

        .arrow{
            margin-left:auto;
            transition:0.3s;
        }

        .submenu{
            list-style:none;
            margin-left:36px;
            margin-top:5px;
            display:none;
        }

        .submenu li a{
            padding:10px 14px;
            font-size:14px;
            color:#c7d2fe;
            display:block;
            border-radius:8px;
        }

        .submenu li a:hover{
            background:rgba(255,255,255,0.08);
        }

        .has-sub.open .submenu{
            display:block;
        }

        .has-sub.open .arrow{
            transform:rotate(180deg);
        }

        /* thu gọn sidebar */
        .sidebar.collapsed .menu a span,
        .sidebar.collapsed .submenu,
        .sidebar.collapsed .arrow{
            display:none;
        }

        .sidebar.collapsed .menu a{
            justify-content:center;
        }

        .logout{
            margin-top:40px;
            border-top:1px solid rgba(255,255,255,0.1);
            padding-top:20px;
        }

        .logout a{
            color:#f87171;
        }

        /* ===== MAIN ===== */
        .main{
            margin-left:260px;
            padding:30px;
            flex:1;
            overflow-y:auto;
            transition:0.3s;
        }

        .sidebar.collapsed ~ .main{
            margin-left:80px;
        }

        .header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:30px;
        }

        .header h2{
            font-size:28px;
            color:#111827;
        }

        .user{
            background:#2563eb;
            color:#fff;
            padding:10px 18px;
            border-radius:20px;
            font-size:14px;
        }

        /* ===== CARDS ===== */
        .cards{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
            gap:20px;
            margin-bottom:30px;
        }

        .card{
            border-radius:16px;
            padding:20px;
            color:#fff;
            box-shadow:0 10px 20px rgba(0,0,0,0.1);
        }

        .blue{background:linear-gradient(135deg,#3b82f6,#1d4ed8);}
        .purple{background:linear-gradient(135deg,#a352ef,#986be4);}
        .orange{background:linear-gradient(135deg,#fb923c,#f97316);}
        .green{background:linear-gradient(135deg,#22c55e,#16a34a);}

        /* ===== TABLE ===== */
        .table-box{
            background:#fff;
            border-radius:14px;
            padding:20px;
            box-shadow:0 8px 20px rgba(0,0,0,0.05);
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th,td{
            padding:14px;
            border-bottom:1px solid #e5e7eb;
            text-align:left;
        }

        th{
            background:#f9fafb;
            font-weight:600;
        }

        tr:hover{
            background:#f3f4f6;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
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
                <li><a>Tìm kiếm sinh viên</a></li>
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

<!-- MAIN -->
<main class="main">

    <div class="header">
        <h2>Tổng quan</h2>
        <div class="user">Xin chào, nhân viên</div>
    </div>

    <section class="cards">
        <div class="card blue"><h3>Phòng đang trống</h3><p>15</p></div>
        <div class="card purple"><h3>Hợp đồng hiệu lực</h3><p>124</p></div>
        <div class="card orange"><h3>Yêu cầu hỗ trợ mới</h3><p>5</p></div>
        <div class="card green"><h3>Hóa đơn cần thu</h3><p>29</p></div>
    </section>

    <section class="table-box">
        <h3 style="margin-bottom:15px;">Danh sách sinh viên mới</h3>
        <table>
            <thead>
                <tr>
                    <th>Mã SV</th>
                    <th>Họ tên</th>
                    <th>Lớp</th>
                    <th>Ngày thêm</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>SV003</td><td>Ngô Minh C</td><td>CNTT-01</td><td>12/01/2025</td></tr>
                <tr><td>SV004</td><td>Hoàng Văn D</td><td>CNTT-02</td><td>30/11/2025</td></tr>
                <tr><td>SV005</td><td>Lê Thị E</td><td>SpTin2B</td><td>30/11/2025</td></tr>
            </tbody>
        </table>
    </section>

</main>

<script>
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
</script>

</body>
</html>
