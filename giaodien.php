<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Ký túc xá Hướng Dương</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome (icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS tích hợp -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background: #f4f6f9;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: #242e53ff;
            color: #fff;
            padding: 20px;
        }


        .sidebar h1 {
            font-size: 26px;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
        }

        .menu {
            list-style: none;
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
        }

        .menu a i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .menu a:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .menu a.active {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
            color: #fff;
        }

        .logout {
            margin-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
        }

        .logout a {
            color: #f87171;
        }

        /* ===== MAIN CONTENT ===== */
        .main {
            flex: 1;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h2 {
            font-size: 28px;
            color: #111827;
        }

        .user {
            background: #2563eb;
            color: #fff;
            padding: 10px 18px;
            border-radius: 20px;
            font-size: 14px;
        }

        /* ===== DASHBOARD CARDS ===== */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            border-radius: 16px;
            padding: 20px;
            color: #fff;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .card h3 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 36px;
            font-weight: bold;
        }

        .blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .purple { background: linear-gradient(135deg, #a352efff, #986be4ff); }
        .orange { background: linear-gradient(135deg, #fb923c, #f97316); }
        .green { background: linear-gradient(135deg, #22c55e, #16a34a); }

        /* ===== TABLE ===== */
        .table-box {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f9fafb;
            font-weight: 600;
        }

        tr:hover {
            background: #f3f4f6;
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h1>Ký Túc Xá<br>Hướng Dương</h1>

        <ul class="menu">
            <li>
                <a href="#" class="active">
                    <i class="fa-solid fa-house"></i>
                    Tổng quan
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa-solid fa-user"></i>
                    Sinh viên
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa-solid fa-door-open"></i>
                    Phòng ở
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa-solid fa-book"></i>
                    Hợp đồng
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    Hóa đơn
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa-solid fa-wrench"></i>
                    Hỗ trợ
                </a>
            </li>
        </ul>

        <div class="logout">
            <a href="#" class="menu a">
                <i class="fa-solid fa-power-off"></i>
                Đăng xuất
            </a>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="main">
        <div class="header">
            <h2>Tổng quan</h2>
            <div class="user">Xin chào, Phạm Quỳnh Anh</div>
        </div>

        <section class="cards">
            <div class="card blue">
                <h3>Phòng đang trống</h3>
                <p>15</p>
            </div>
            <div class="card purple">
                <h3>Hợp đồng hiệu lực</h3>
                <p>124</p>
            </div>
            <div class="card orange">
                <h3>Yêu cầu hỗ trợ mới</h3>
                <p>5</p>
            </div>
            <div class="card green">
                <h3>Hóa đơn cần thu</h3>
                <p>29</p>
            </div>
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
                    <tr>
                        <td>SV003</td>
                        <td>Ngô Minh C</td>
                        <td>CNTT-01</td>
                        <td>12/01/2025</td>
                    </tr>
                    <tr>
                        <td>SV004</td>
                        <td>Hoàng Văn D</td>
                        <td>CNTT-02</td>
                        <td>30/11/2025</td>
                    </tr>
                    <tr>
                        <td>SV005</td>
                        <td>Lê Thị E</td>
                        <td>SpTin2B</td>
                        <td>30/11/2025</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>

</body>
</html>
