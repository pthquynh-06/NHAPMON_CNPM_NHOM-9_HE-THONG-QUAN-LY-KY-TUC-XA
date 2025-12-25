
    <div class="table-container-card">
        <div style="margin-bottom: 25px;">
            <form method="GET" action="danhsachsv.php" style="display: flex; gap: 10px;">
                <div style="position: relative; flex: 1;">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Nhập tên, mã SV, phòng hoặc trường..." 
                        style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 12px; border: 1.4px solid #d1dae4; outline: none;">
                </div>
                <button type="submit" style="padding: 0 25px; background: #2563eb; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer;">Tìm kiếm</button>
                
                <?php if($search != ''): ?>
                    <a href="danhsachsv.php" style="padding: 12px 15px; background: #f1f5f9; color: #475569; border-radius: 11px; text-decoration: none; font-size: 14px; display: flex; align-items: center;">Hủy lọc</a>
                <?php endif; ?>
            </form>
        </div>
                <?php endwhile; else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 50px; color: #94a3b8; font-size: 16px;">
                            <i class="fa-solid fa-user-slash" style="font-size: 40px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                            Không tìm thấy sinh viên 
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>