<?php
// File chạy độc lập, chưa cần DB
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sinh viên KTX</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f5f6fa;
        }

        /* ===== LAYOUT ===== */
        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 240px;
            background: #1f2a4a;
            color: #fff;
            padding: 20px 15px;
        }

        .sidebar h2 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 30px;
        }

        .menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu li {
            margin-bottom: 10px;
        }

        .menu a {
            display: block;
            padding: 10px 12px;
            color: #cfd6f6;
            text-decoration: none;
            border-radius: 6px;
        }

        .menu a.active,
        .menu a:hover {
            background: #2f6bff;
            color: #fff;
        }

        .menu-bottom {
            position: absolute;
            bottom: 20px;
            width: 210px;
        }

        .menu-bottom a {
            display: block;
            padding: 10px 12px;
            margin-bottom: 8px;
            color: #ffb3b3;
            text-decoration: none;
            border-radius: 6px;
        }

        .menu-bottom a:hover {
            background: #ff4d4d;
            color: #fff;
        }

        /* ===== MAIN CONTENT ===== */
        .content {
            flex: 1;
            padding: 25px;
        }

        .content h2 {
            margin-bottom: 15px;
        }

        /* ===== TABLE ===== */
        .table-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            max-height: 600px;
            overflow-y: auto;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1500px;
        }

        thead {
            position: sticky;
            top: 0;
            background: #f1f3f6;
            z-index: 10;
        }

        th, td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
            white-space: nowrap;
        }

        th {
            font-weight: bold;
        }

        tr:hover {
            background: #f9fafc;
        }

        .email {
            max-width: 160px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h2>Ký Túc Xá<br>Hướng Dương</h2>

        <ul class="menu">
            <li><a href="#">Tổng quan</a></li>
            <li><a href="#" class="active">Sinh viên</a></li>
            <li><a href="#">Danh sách sinh viên</a></li>
            <li><a href="#">Thêm sinh viên</a></li>
            <li><a href="#">Tìm kiếm sinh viên</a></li>
            <li><a href="#">Phòng ở</a></li>
            <li><a href="#">Hợp đồng</a></li>
            <li><a href="#">Hóa đơn</a></li>
            <li><a href="#">Hỗ trợ</a></li>
        </ul>

        <div class="menu-bottom">
            <a href="#">Đổi mật khẩu</a>
            <a href="#">Đăng xuất</a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content">
        <h2>Danh sách sinh viên KTX</h2>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Mã SV</th>
                        <th>Họ tên</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>CCCD</th>
                        <th>Quê quán</th>
                        <th>Ngày bắt đầu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>MSSV001</td>
                        <td>Trần Thị Mai Lan</td>
                        <td>15/03/2002</td>
                        <td>Nữ</td>
                        <td class="email">lantran@gmail.com</td>
                        <td>0912345678</td>
                        <td>001201009876</td>
                        <td>Hà Nội</td>
                        <td>01/02/2024</td>
                    </tr>
                    <tr>
                        <td>MSSV002</td>
                        <td>Nguyễn Văn Hùng</td>
                        <td>20/08/2001</td>
                        <td>Nam</td>
                        <td class="email">hungnv@gmail.com</td>
                        <td>0987654321</td>
                        <td>001202003456</td>
                        <td>Bắc Ninh</td>
                        <td>05/02/2024</td>
                    </tr>
                    <tr>
                        <td>MSSV003</td>
                        <td>Phạm Thị Ngọc Anh</td>
                        <td>05/12/2003</td>
                        <td>Nữ</td>
                        <td class="email">anhpham@gmail.com</td>
                        <td>0908123456</td>
                        <td>001203056789</td>
                        <td>Thanh Hóa</td>
                        <td>10/02/2024</td>
                    </tr>
                    <tr>
                        <td>MSSV004</td>
                        <td>Lê Hoàng Sơn</td>
                        <td>18/06/2002</td>
                        <td>Nam</td>
                        <td class="email">sonle@gmail.com</td>
                        <td>0922333444</td>
                        <td>00120407912</td>
                        <td>Quảng Ngãi</td>
                        <td>12/02/2024</td>
                    </tr>
                    <tr>
                        <td>MSSV005</td>
                        <td>Vũ Hồng Ngọc</td>
                        <td>07/10/2001</td>
                        <td>Nữ</td>
                        <td class="email">ngocvu@gmail.com</td>
                        <td>0968777888</td>
                        <td>001205065432</td>
                        <td>Đồng Nai</td>
                        <td>15/02/2024</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

</div>

</body>
</html>
