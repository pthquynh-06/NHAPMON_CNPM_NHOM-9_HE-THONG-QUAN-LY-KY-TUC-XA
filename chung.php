<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Ký túc xá Hướng Dương</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
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
    </style>
</head>

<body>

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
                     <li><a>trạng thái xử lý</a></li>
                </ul>
            </li>

        </ul>

        <div class="logout">
            <a>
                <i class="fa-solid fa-power-off"></i><span>Đăng xuất</span></a>
        </div>

    </aside>
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