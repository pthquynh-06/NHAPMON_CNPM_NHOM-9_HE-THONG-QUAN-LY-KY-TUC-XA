# quản lý hóa đơn
import pymysql

def delete_invoice(invoice_id):
    try:
        conn = pymysql.connect(
            host='localhost',
            user='root',
            password='',
            database='ktx'
        )
        cursor = conn.cursor()

        sql = "DELETE FROM hoadon WHERE id = %s"
        cursor.execute(sql, (invoice_id,))
        conn.commit()

        if cursor.rowcount > 0:
            print("Xóa hóa đơn thành công!")
        else:
            print("Hóa đơn không tồn tại!")

    except Exception as e:
        print("Lỗi:", e)

    finally:
        conn.close()


# Gọi hàm
delete_invoice(5)   # ví dụ xóa hóa đơn có id = 5
