<?php
// Lấy tên file hiện tại, ví dụ: 'themsv.php'
$current_page = basename($_SERVER['PHP_SELF']); 
?>
    <aside class="sidebar" id="sidebar">

        <div class="toggle-btn" onclick="toggleSidebar()">
            <i class="fa-solid fa-angle-left" id="toggleIcon"></i>
        </div>

        <h1>Ký Túc Xá<br>Hướng Dương</h1>
                <ul class="menu">
                    <li><a href="giaodien.php" class="<?= ($current_page == 'giaodien.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-house"></i>
                    <span>Tổng quan</span>
                </a>
            </li>

            <li class="has-sub <?= (in_array($current_page, ['themsv.php','danhsachsv.php'])) ? 'open' : '' ?>">
                <a onclick="toggleSubmenu(this)" class="<?= (in_array($current_page, ['themsv.php','danhsachsv.php'])) ? 'active' : '' ?>">
                    <i class="fa-solid fa-user"></i>
                    <span>Sinh viên</span>
                    <i class="fa-solid fa-chevron-down arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="danhsachsv.php" style="<?= ($current_page == 'danhsachsv.php') ? 'color: #fff; font-weight: bold;' : '' ?>">Danh sách sinh viên</a></li>
                    <li><a href="themsv.php" style="<?= ($current_page == 'themsv.php') ? 'color: #fff; font-weight: bold;' : '' ?>">Thêm sinh viên</a></li>
                </ul>
            </li>

            <li class="has-sub <?= (in_array($current_page, ['danhsachphong.php','phongtrong.php', 'ttphong.php'])) ? 'open' : '' ?>">
                <a onclick="toggleSubmenu(this)" class="<?= (in_array($current_page, ['danhsachphong.php','phongtrong.php', 'ttphong.php'])) ? 'active' : '' ?>">
                    <i class="fa-solid fa-door-open"></i>
                    <span>Phòng ở</span>
                    <i class="fa-solid fa-chevron-down arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="danhsachphong.php" style="<?= ($current_page == 'danhsachphong.php') ? 'color: #fff; font-weight: bold;' : '' ?>">Danh sách phòng</a></li>
                    <li><a href="phongtrong.php" style="<?= ($current_page == 'phongtrong.php') ? 'color: #fff; font-weight: bold;' : '' ?>">Phòng trống</a></li>
                    <li><a href="cnttphong.php" style="<?= ($current_page == 'cnttphong.php') ? 'color: #fff; font-weight: bold;' : '' ?>">Trạng thái phòng</a></li>
                </ul>
            </li>

            <li class="has-sub <?= (in_array($current_page, ['dshopdong.php','laphopdong.php', 'suahopdong.php', 'thanhlyhopdong.php'])) ? 'open' : '' ?>">
                <a onclick="toggleSubmenu(this)" class="<?= (in_array($current_page, ['dshopdong.php','laphopdong.php', 'suahopdong.php', 'thanhlyhopdong.php'])) ? 'active' : '' ?>">
            
                    <i class="fa-solid fa-book"></i>
                    <span>Hợp đồng</span>
                    <i class="fa-solid fa-chevron-down arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="dshopdong.php" style="<?= ($current_page == 'dshopdong.php') ? 'color: #fff; font-weight: bold;' : '' ?>">Danh sách hợp đồng</a></li>
                    <li><a href="laphopdong.php" style="<?= ($current_page == 'laphopdong.php') ? 'color: #fff; font-weight: bold;' : '' ?>">Lập hợp đồng</a></li>
                    <li><a href="suahopdong.php" style="<?= ($current_page == 'suahopdong.php') ? 'color: #fff; font-weight: bold;' : '' ?>">Sửa hợp đồng</a></li>
                    <li><a href="thanhlyhopdong.php" style="<?= ($current_page == 'thanhlyhopdong.php') ? 'color: #fff; font-weight: bold;' : '' ?>">Thanh lý hợp đồng</a></li>
                </ul>
            </li>

            <li class="has-sub <?= (in_array($current_page, ['taohoadon.php','dshoadon.php', 'ttthanhtoan.php', 'inhoadon.php'])) ? 'open' : '' ?>">
                <a onclick="toggleSubmenu(this)" class="<?= (in_array($current_page, ['taohoadon.php','dshoadon.php', 'ttthanhtoan.php', 'inhoadon.php'])) ? 'active' : '' ?>">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Hóa đơn</span>
                    <i class="fa-solid fa-chevron-down arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="taohoadon.php" style="<?= ($current_page == 'taohoadon.php') ? 'color: #fff; font-weight: bold;' : '' ?>">Tạo hóa đơn</a></li>
                    <li><a href="dshoadon.php" style="<?= ($current_page == 'dshoadon.php') ? 'color: #fff; font-weight: bold;' : '' ?>">Danh sách hóa đơn</a></li>
                    <li><a href="ttthanhtoan.php" style="<?= ($current_page == 'ttthanhtoan.php') ? 'color: #fff; font-weight: bold;' : '' ?>">Trạng thái thanh toán</a></li>
                </ul>
            </li>

            <li class="has-sub <?= (in_array($current_page, ['dsyeucau.php', 'trangthaixl.php'])) ? 'open' : '' ?>">
                <a onclick="toggleSubmenu(this)" class="<?= (in_array($current_page, ['dsyeucau.php', 'trangthaixl.php'])) ? 'active' : '' ?>">
                    <i class="fa-solid fa-headset"></i>
                    <span>Hỗ trợ</span>
                    <i class="fa-solid fa-chevron-down arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="dsyeucau.php" style="<?= ($current_page == 'dsyeucau.php') ? 'color: #fff; font-weight: bold;' : '' ?>">Danh sách yêu cầu</a></li>
                    <li><a href="trangthaixl.php" style="<?= ($current_page == 'trangthaixl.php') ? 'color: #fff; font-weight: bold;' : '' ?>">Trạng thái xử lý</a></li>
                </ul>
            </li>
        </ul>

        <div class="logout">
            <a href="../quanlynguoidung/thaydoimatkhau.php" style="margin-bottom: 10px; display: block;">
                <i class="fa-solid fa-key"></i><span> Đổi mật khẩu</span>
            </a>
    
            <a href="../quanlynguoidung/dangnhaphethong.php?logout=1">
                <i class="fa-solid fa-power-off"></i><span> Đăng xuất</span>
            </a>
        </div>
        
    </aside>
    <script src="../assets/app.js"></script>