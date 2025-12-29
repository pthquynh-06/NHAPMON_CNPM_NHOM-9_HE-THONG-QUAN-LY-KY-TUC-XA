?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm phòng mới - KTX Hướng Dương</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { display: flex; flex-direction: column; margin-bottom: 15px; }
        .form-group label { font-weight: 600; color: #374151; font-size: 14px; margin-bottom: 6px; }
        .form-group input { padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px; outline: none; }
        
        .no-spinner::-webkit-inner-spin-button, 
        .no-spinner::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        .no-spinner { -moz-appearance: textfield; }

        .form-group input.is-invalid { border: 1px solid #ef4444 !important; }
        .error-text { color: #ef4444; font-size: 13px; margin-top: 5px; font-weight: 500; }
        .required { color: #ef4444; margin-left: 3px; }
        .actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; border-top: 1px solid #f3f4f6; padding-top: 20px; }
        .btn { padding: 10px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-primary { background: #2563eb; border: none; color: #fff; }
        .btn-cancel { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
        .search-card h3 { font-size: 25px; margin-bottom: 20px; font-weight: bold; }

        /* Style cho Modal Thành Công */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); display: flex; justify-content: center;
            align-items: center; z-index: 9999;
        }
        .modal-content {
            background: white; padding: 40px; border-radius: 20px;
            text-align: center; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            max-width: 400px; width: 90%; animation: modalPop 0.3s ease-out;
        }
        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }
        .icon-box {
            width: 80px; height: 80px; background: #22c55e; color: white;
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; font-size: 40px; margin: 0 auto 20px;
        }
    </style>
</head>
<body>

<?php include '../includes/chung.php'; ?>

<main class="main">
    <div class="search-card">
        <h3>Thêm phòng ở mới</h3>
        <form id="roomForm" action="" method="POST" novalidate> 
            <div class="form-grid">
                
                <div class="form-group">
                    <label>Số phòng <span class="required">*</span></label>
                    <input type="text" name="sophong" placeholder="Ví dụ: A101, B202" 
                           class="<?php echo isset($errors['sophong']) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo htmlspecialchars($sophong ?? ''); ?>">
                    <?php if (isset($errors['sophong'])): ?>
                        <div class="error-text"><?php echo $errors['sophong']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Sức chứa (Người) <span class="required">*</span></label>
                    <input type="number" name="succhua" 
                           placeholder="Nhập sức chứa" 
                           class="<?php echo isset($errors['succhua']) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo htmlspecialchars($succhua ?? ''); ?>">
                    <?php if (isset($errors['succhua'])): ?>
                        <div class="error-text"><?php echo $errors['succhua']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Giá phòng (VNĐ/Tháng) <span class="required">*</span></label>
                    <input type="number" name="giaphong" 
                           placeholder="Ví dụ: 1500000" 
                           class="no-spinner <?php echo isset($errors['giaphong']) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo htmlspecialchars($giaphong ?? ''); ?>">
                    <?php if (isset($errors['giaphong'])): ?>
                        <div class="error-text"><?php echo $errors['giaphong']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Trạng thái mặc định</label>
                    <input type="text" value="Trống" disabled style="background: #f9fafb; color: #6b7280;">
                </div>
            </div>

            <div class="actions">
                <button type="button" class="btn btn-cancel" onclick="window.history.back()">Hủy</button>
                <button type="submit" name="them_phong" class="btn btn-primary">Lưu thông tin</button>
            </div>
        </form>
    </div>
    
    <?php if ($show_success_modal): ?>
    <div class="modal-overlay">
        <div class="modal-content">
            <div class="icon-box">
                <i class="fas fa-check"></i>
            </div>
            <h2 style="margin-bottom: 10px; color: #1f2937; font-size: 24px;">Thành công!</h2>
            <p style="color: #6b7280; margin-bottom: 30px; line-height: 1.5;">Phòng mới đã được thêm thành công vào hệ thống.</p>
            
            <button onclick="window.location.href = window.location.pathname" 
                    class="btn btn-primary" style="width: 100%; padding: 12px;">
                Xác nhận
            </button>
        </div>
    </div>
    <?php endif; ?>
</main>
</body>
</html>