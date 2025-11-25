# quản lý hóa đơn ký túc xá
import datetime

class Student:
    """
    Lớp đại diện cho một Sinh viên và các khoản phí của họ.
    """
    def __init__(self, student_id, name, room_number, monthly_rent, electricity_unit_price=3500, water_fee_per_person=50000):
        self.student_id = student_id
        self.name = name
        self.room_number = room_number
        self.monthly_rent = monthly_rent  # Tiền thuê phòng (cố định)
        self.electricity_unit_price = electricity_unit_price
        self.water_fee_per_person = water_fee_per_person
        self.electricity_usage = 0 # Số điện tiêu thụ (kWh)

    def set_electricity_usage(self, usage):
        """Cập nhật số điện tiêu thụ trong tháng."""
        if usage >= 0:
            self.electricity_usage = usage
        else:
            print("Lỗi: Số điện tiêu thụ không thể âm.")

    def calculate_total_electricity_fee(self):
        """Tính tổng tiền điện."""
        return self.electricity_usage * self.electricity_unit_price

    def calculate_total_fee(self):
        """Tính tổng chi phí tháng bao gồm: Tiền phòng + Tiền điện + Tiền nước."""
        total_fee = self.monthly_rent
        total_fee += self.calculate_total_electricity_fee()
        total_fee += self.water_fee_per_person # Tiền nước cố định
        return total_fee

class InvoiceGenerator:
    """
    Lớp tạo hóa đơn.
    """
    def __init__(self, dormitory_name="Ký Túc Xá Đại Học XYZ"):
        self.dormitory_name = dormitory_name

    def generate_invoice(self, student):
        """Tạo và hiển thị hóa đơn cho một sinh viên cụ thể."""
        
        # Lấy thông tin cần thiết
        date_today = datetime.date.today().strftime("%d/%m/%Y")
        electricity_fee = student.calculate_total_electricity_fee()
        total_amount = student.calculate_total_fee()
        
        # Tạo chuỗi hóa đơn
        invoice = f"""
==================================================
              HÓA ĐƠN CHI PHÍ KÝ TÚC XÁ
              {self.dormitory_name}
==================================================
Ngày lập: {date_today}
---
**THÔNG TIN SINH VIÊN:**
Mã SV:          {student.student_id}
Họ & Tên:       {student.name}
Phòng:          {student.room_number}
---
**CHI TIẾT THANH TOÁN THÁNG:**
1. Tiền thuê phòng:     {student.monthly_rent:,.0f} VND
2. Tiền điện:
   - Số điện tiêu thụ:  {student.electricity_usage} kWh
   - Đơn giá điện:      {student.electricity_unit_price:,.0f} VND/kWh
   - Tổng tiền điện:    {electricity_fee:,.0f} VND
3. Tiền nước (cố định): {student.water_fee_per_person:,.0f} VND
---
**TỔNG CỘNG:**
TỔNG SỐ TIỀN PHẢI THANH TOÁN: {total_amount:,.0f} VND
==================================================
"""
        return invoice

# --- PHẦN SỬ DỤNG VÀ THỬ NGHIỆM ---

# 1. Khởi tạo thông tin sinh viên
# student_id, name, room_number, monthly_rent
student1 = Student("SV001", "Nguyễn Văn A", "C201", 1_000_000)

# 2. Cập nhật số điện tiêu thụ (ví dụ: 85 kWh)
student1.set_electricity_usage(85)

# 3. Khởi tạo bộ tạo hóa đơn
generator = InvoiceGenerator()

# 4. Tạo và in hóa đơn
invoice_output = generator.generate_invoice(student1)
print(invoice_output)

# --- Thử nghiệm với sinh viên khác ---
student2 = Student("SV002", "Trần Thị B", "A305", 1_200_000)
student2.set_electricity_usage(55)
invoice_output_2 = generator.generate_invoice(student2)
print(invoice_output_2)