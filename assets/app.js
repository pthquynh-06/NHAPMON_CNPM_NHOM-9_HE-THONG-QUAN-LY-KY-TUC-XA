/* ===== SIDEBAR & MENU ===== */
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const icon = document.getElementById("toggleIcon");
    sidebar.classList.toggle("collapsed");
    icon.classList.toggle("fa-angle-left");
    icon.classList.toggle("fa-angle-right");
}

function toggleSubmenu(el) {
    // 1. Tìm thẻ <li> cha có class 'has-sub' gần nhất
    const parentLi = el.closest('.has-sub');
    if (!parentLi) return;

    // 2. Kiểm tra trạng thái hiện tại (đang mở hay đóng)
    const isOpen = parentLi.classList.contains("open");

    // 3. XỬ LÝ ĐÓNG CÁC MỤC KHÁC
    document.querySelectorAll('.has-sub').forEach(li => {
        // Đóng tất cả các menu con khác
        li.classList.remove("open");
        
        // Gỡ bỏ class 'active' của các tiêu đề cha khác
        const mainLink = li.querySelector('a');
        if (mainLink) mainLink.classList.remove("active");
    });

    // 4. XỬ LÝ MỤC ĐƯỢC NHẤN
    if (!isOpen) {
        // Nếu mục đang đóng -> Mở nó ra và tô xanh
        parentLi.classList.add("open");
        el.classList.add("active");

        // Đảm bảo mục "Tổng quan" bị mất màu xanh khi mở một mục cha
        const tongQuan = document.querySelector('.menu > li > a[href="giaodien.php"]');
        if (tongQuan) tongQuan.classList.remove("active");
    } else {
        // Nếu mục đang mở mà nhấn vào -> Đóng lại và gỡ xanh
        parentLi.classList.remove("open");
        el.classList.remove("active");
    }
}

/* ===== GIỮ NGUYÊN CÁC HÀM DATA CỦA BẠN ===== */
function displayInitialData() {
    const tbody = document.getElementById("results-tbody");
    if (!tbody) return; // Tránh lỗi nếu trang không có bảng
    const data = Object.keys(mockData).map(k => ({ mssv: k, ...mockData[k] }));
    render(data);
}
// ... Các hàm render, performSearch, deleteSV của bạn giữ nguyên ...