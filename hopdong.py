#quản lý hợp đồng cho thuê phòng ở ký túc xá..

# Danh sách lưu hợp đồng
contracts = []

# Thêm hợp đồng
def them_hop_dong():
    ma_hd = input("Mã hợp đồng: ")
    ma_sv = input("Mã sinh viên: ")
    phong = input("Mã phòng: ")

    contracts.append([ma_hd, ma_sv, phong])
    print("✅ Đã thêm hợp đồng!")

# Hiện danh sách hợp đồng
def hien_thi():
    print("\nDANH SÁCH HỢP ĐỒNG")
    for hd in contracts:
        print("Mã HD:", hd[0], "- Mã SV:", hd[1], "- Phòng:", hd[2])

# Tìm hợp đồng theo mã SV
def tim_kiem():
    ma_sv = input("Nhập mã SV cần tìm: ")

    for hd in contracts:
        if hd[1] == ma_sv:
            print("✅ Tìm thấy:", hd)
            return
    
    print("❌ Không tìm thấy!")

# Menu
while True:
    print("\n===== MENU =====")
    print("1. Thêm hợp đồng")
    print("2. Xem danh sách")
    print("3. Tìm hợp đồng")
    print("0. Thoát")

    chon = input("Chọn: ")

    if chon == "1":
        them_hop_dong()
    elif chon == "2":
        hien_thi()
    elif chon == "3":
        tim_kiem()
    elif chon == "0":
        print("Thoát!")
        break
    else:
        print("Sai lựa chọn!")

