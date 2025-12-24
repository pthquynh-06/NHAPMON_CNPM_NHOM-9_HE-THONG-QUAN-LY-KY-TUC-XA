<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sinh viên - KTX Hướng Dương</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/style.css"> 
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap');
        .main { font-family: 'Be Vietnam Pro', sans-serif; background-color: #f8faff; padding: 30px; }
        .table-container-card { background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03); margin-top: 20px; }
        .student-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .student-table th { padding: 22px 10px; border-bottom: 2px solid #d1dae4ff; color: #818694ff; text-transform: uppercase; text-align: left; }
        .student-table td { padding: 15px 10px; border-bottom: 1px solid #f1f5f9; color: #2e3a59; vertical-align: middle; border-right: 1px solid #f1f5f9; }
        
        .student-name { font-weight: 600; color: #1e2640; display: block; }
        .badge { padding: 3px 8px; border-radius: 12px; font-size: 10px; font-weight: 600; }
        .badge-nam { background: #e0f2fe; color: #0369a1; }
        .badge-nu { background: #fce7f3; color: #be185d; }
        
        .action-icon { font-size: 18px; cursor: pointer; transition: 0.2s; margin-right: 12px; }
        .action-icon:hover { transform: scale(1.2); }

        /* MODAL CHUNG */
        .nen-mo, .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 9999; }
        .o-trang, .modal-box { background: white; padding: 35px; border-radius: 16px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); animation: hienLen 0.25s ease-out; }
    </style>
</head>
<body>

<?php include '../includes/chung.php'; ?>

<main class="main">
    <div class="header">
        <h2 style="color: #1e2640; font-weight: 700;">Danh sách sinh viên ký túc xá</h2>
    </div>
    
    <div class="table-container-card">
        <table class="student-table">
            <thead>
                <tr>
                    <th>MSV</th><th>Họ Tên & GT</th><th>Ngày Sinh</th><th>Phòng & Trường</th><th>Liên hệ</th><th>CCCD</th><th>Quê quán</th><th>Bắt đầu</th><th style="text-align: center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): 
                    while($row = mysqli_fetch_assoc($result)): 
                        $gt_class = ($row['gioitinh'] == 'Nam') ? 'badge-nam' : 'badge-nu';
                        $mssv = $row['mssv'];
                ?>
                <tr id="row-<?php echo $mssv; ?>">
                    <td class="c-mssv" style="font-family: monospace; font-weight: 700;"><?php echo $mssv; ?></td>
                    <td>
                        <span class="student-name c-hoten"><?php echo htmlspecialchars($row['hoten']); ?></span>
                        <span class="badge <?php echo $gt_class; ?> c-gioitinh"><?php echo $row['gioitinh']; ?></span>
                    </td>
                    <td class="c-ngaysinh" data-raw="<?php echo $row['ngaysinh']; ?>"><?php echo date('d/m/Y', strtotime($row['ngaysinh'])); ?></td>
                    <td>
                        <span class="student-name c-sophong"><?php echo htmlspecialchars($row['sophong']); ?></span>
                        <small class="c-truong" style="color:#64748b"><?php echo htmlspecialchars($row['truong']); ?></small>
                    </td>
                    <td>
                        <div class="c-sdt"><?php echo htmlspecialchars($row['sodienthoai']); ?></div>
                        <small class="c-email" style="color:#64748b"><?php echo htmlspecialchars($row['email']); ?></small>
                    </td>
                        <td class="c-cccd"><?php echo htmlspecialchars($row['cccd']); ?></td>
                        <td class="c-quequan"><?php echo htmlspecialchars($row['quequan']); ?></td>
                        <td class="c-ngaybatdau" data-raw="<?php echo $row['ngaybatdau']; ?>"><?php echo date('d/m/Y', strtotime($row['ngaybatdau'])); ?></td>
                        </td>
                </tr>