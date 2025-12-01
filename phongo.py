#quản lý phòng ở
class Student:
    """Lớp đại diện cho một Sinh viên."""
    def __init__(self, student_id, name, major):
        self.student_id = student_id  # Mã số sinh viên (Duy nhất)
        self.name = name              # Tên sinh viên
        self.major = major            # Ngành học

    def __str__(self):
        """Trả về chuỗi đại diện dễ đọc của đối tượng."""
        return f"ID: {self.student_id} | Tên: {self.name} | Ngành: {self.major}"

class Room:
    """Lớp đại diện cho một Phòng ở Ký túc xá, bao gồm thông tin sử dụng điện."""
    def __init__(self, room_number, capacity):
        self.room_number = room_number  # Số phòng (Duy nhất)
        self.capacity = capacity        # Sức chứa tối đa của phòng
        self.students = []              # Danh sách các đối tượng Student đang ở
        
        # --- Thuộc tính quản lý điện năng ---
        self.previous_usage = 0  # Số điện tháng trước (kWh)
        self.current_usage = 0   # Số điện tháng này (kWh)
        # -----------------------------------

    def is_full(self):
        """Kiểm tra xem phòng đã đầy chưa."""
        return len(self.students) >= self.capacity

    def add_student(self, student):
        """Thêm sinh viên vào phòng nếu còn chỗ."""
        if not self.is_full():
            self.students.append(student)
            return True
        return False

    def remove_student(self, student_id):
        """Xóa sinh viên khỏi phòng dựa trên ID."""
        original_count = len(self.students)
        self.students = [s for s in self.students if s.student_id != student_id]
        return len(self.students) < original_count

    def display_info(self):
        """Hiển thị thông tin chi tiết của phòng."""
        print(f"\n--- Phòng {self.room_number} ---")
        print(f"Sức chứa: {len(self.students)}/{self.capacity}")
        print(f"Chỉ số điện tháng trước: {self.previous_usage} kWh")
        if self.students:
            print("Danh sách Sinh viên:")
            for student in self.students:
                print(f"  -> {student}")
        else:
            print("Phòng đang trống.")

class DormitoryManager:
    """Lớp Quản lý Ký túc xá tổng thể."""
    def __init__(self):
        self.rooms = {}         # {số_phòng: Room object}
        self.all_students = {}  # {student_id: Student object}

    def add_room(self, room_number, capacity):
        """Thêm một phòng mới vào hệ thống."""
        if room_number not in self.rooms:
            self.rooms[room_number] = Room(room_number, capacity)
            print(f"✅ Đã thêm phòng {room_number} với sức chứa {capacity}.")
            return True
        print(f"❌ Lỗi: Phòng {room_number} đã tồn tại.")
        return False

    def register_student(self, student_id, name, major):
        """Đăng ký thông tin sinh viên mới vào hệ thống."""
        if student_id not in self.all_students:
            new_student = Student(student_id, name, major)
            self.all_students[student_id] = new_student
            print(f"✅ Đã đăng ký sinh viên: {new_student.name} ({new_student.student_id}).")
            return new_student
        print(f"❌ Lỗi: Sinh viên ID {student_id} đã tồn tại.")
        return None

    def assign_student_to_room(self, student_id, room_number):
        """Chỉ định sinh viên vào một phòng cụ thể."""
        if room_number not in self.rooms or student_id not in self.all_students:
            print("❌ Lỗi: Không tìm thấy Phòng hoặc Sinh viên.")
            return False

        room = self.rooms[room_number]
        student = self.all_students[student_id]

        if room.add_student(student):
            print(f"✅ Đã xếp {student.name} vào phòng {room_number}.")
            return True
        
        print(f"❌ Lỗi: Phòng {room_number} đã đầy ({room.capacity} người).")
        return False

    def calculate_electricity_bill(self, room_number, price_per_kwh_student, current_meter_reading):
        """
        ⚡ Tính toán tiền điện dựa trên giá sinh viên và số điện tiêu thụ.
        """
        if room_number not in self.rooms:
            print(f"❌ Lỗi: Không tìm thấy phòng {room_number} để tính tiền điện.")
            return None

        room = self.rooms[room_number]
        
        # 1. Tính toán lượng tiêu thụ (kWh)
        total_kwh_used = current_meter_reading - room.previous_usage
        
        if total_kwh_used < 0:
            print("⚠️ Cảnh báo: Số điện tháng này thấp hơn tháng trước. Kiểm tra lại dữ liệu.")
            return None

        # 2. Tính toán chi phí dựa trên giá sinh viên (Đã lưu ý: giá điện sinh viên)
        electricity_cost = total_kwh_used * price_per_kwh_student
        
        # 3. Cập nhật chỉ số cho kỳ tiếp theo
        room.previous_usage = current_meter_reading 
        
        num_students = len(room.students)
        cost_per_student = electricity_cost / num_students if num_students > 0 else 0

        print(f"\n--- Hóa đơn Điện Phòng {room_number} ---")
        print(f"Lượng tiêu thụ (kWh): {total_kwh_used}")
        print(f"Giá/kWh (Sinh viên): {price_per_kwh_student:,.0f} VND")
        print(f"Tổng chi phí: {electricity_cost:,.0f} VND")
        if num_students > 0:
            print(f"Chi phí/Sinh viên ({num_students} người): {cost_per_student:,.0f} VND")
        
        return electricity_cost

    def display_dorm_status(self):
        """Hiển thị trạng thái của tất cả các phòng."""
        print("\n" + "="*40)
        print("     📋 TRẠNG THÁI KÝ TÚC XÁ")
        print("="*40)
        if not self.rooms:
            print("Chưa có phòng nào được thiết lập.")
            return

        for room_num, room in self.rooms.items():
            room.display_info()
            print("-" * 20)
