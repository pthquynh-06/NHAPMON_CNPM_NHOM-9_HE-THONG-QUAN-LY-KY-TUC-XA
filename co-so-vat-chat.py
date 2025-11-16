#Quản lý cơ sở vật chất ở ký túc xá
class Facility:
    def __init__(self, name, status="Tốt", room=None):
        self.name = name
        self.status = status
        self.room = room

class FacilityManager:
    def __init__(self):
        self.facilities = []

    def add_facility(self, name, room):
        facility = Facility(name=name, room=room)
        self.facilities.append(facility)
        print(f"Đã thêm: {name} tại phòng {room}")

    def report_issue(self, name, issue):
        for f in self.facilities:
            if f.name == name:
                f.status = issue
                print(f"Đã cập nhật: {name} -> Tình trạng: {issue}")
                return
        print("Không tìm thấy cơ sở vật chất!")

    def show_all(self):
        print("\nDanh sách cơ sở vật chất:")
        for f in self.facilities:
            print(f"- {f.name} | Phòng: {f.room} | Tình trạng: {f.status}")


# --- Demo ---
manager = FacilityManager()
manager.add_facility("Quạt trần", "A203")
manager.add_facility("Máy nước nóng", "B105")

manager.report_issue("Quạt trần", "Hỏng – cần sửa")

manager.show_all()
