/* ===== SIDEBAR & MENU ===== */
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const icon = document.getElementById("toggleIcon");
    sidebar.classList.toggle("collapsed");
    icon.classList.toggle("fa-angle-left");
    icon.classList.toggle("fa-angle-right");
}

function toggleSubmenu(el) {
    el.parentElement.classList.toggle("open");
}

/* ===== XỬ LÝ MÀU XANH (ACTIVE) VÀ CHUYỂN TRANG ===== */
document.querySelectorAll('.menu a').forEach(item => {
    item.addEventListener('click', function(e) {
        // Nếu là link có địa chỉ thật (như giaodien.php, themsv.php)
        // thì KHÔNG dùng e.preventDefault() để trang được load lại
        const href = this.getAttribute('href');
        if (href && href !== '#') {
            return; // Cho phép trình duyệt nhảy trang
        }

        // Nếu là menu có submenu (như mục "Sinh viên")
        document.querySelectorAll('.menu a').forEach(nav => nav.classList.remove('active'));
        this.classList.add('active');
        
        const headerTitle = document.querySelector('.header h2');
        if (headerTitle) headerTitle.innerText = ''; 
    });
});

/* ===== GIỮ NGUYÊN CÁC HÀM DATA CỦA BẠN ===== */
function displayInitialData() {
    const tbody = document.getElementById("results-tbody");
    if (!tbody) return; // Tránh lỗi nếu trang không có bảng
    const data = Object.keys(mockData).map(k => ({ mssv: k, ...mockData[k] }));
    render(data);
}
// ... Các hàm render, performSearch, deleteSV của bạn giữ nguyên ...