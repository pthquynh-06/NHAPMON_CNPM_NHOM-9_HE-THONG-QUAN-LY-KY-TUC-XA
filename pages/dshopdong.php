
        $sql = "UPDATE hopdong SET mssv=?, hoten=?, sophong=?, ngaybatdau=?, ngayketthuc=?, tienphong=?, trangthai=? WHERE mahopdong=?";
        $stmt = $conn->prepare($sql);
        if(!$stmt) throw new Exception("system_error");

        $stmt->bind_param("sssssiss", $mssv, $hoten, $sophong, $ngaybatdau, $ngayketthuc, $tienphong, $trangthai, $mahd);
        echo $stmt->execute() ? "success" : "system_error";
    } catch (Exception $e) {
        echo "system_error";
    }
    exit; 
}
