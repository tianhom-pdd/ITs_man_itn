<?php
require_once __DIR__ . '/../inc/db.php';
include_once __DIR__ . '/../inc/admin_header.php';

// ดึงหมวดหมู่ทั้งหมด
$titles = [];
$res = $db->query("SELECT id, title, color, icon FROM title_it ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $titles[] = $row;
?>

<h1 style="margin-bottom:2rem;">จัดการหมวดหมู่</h1>
<div style="margin-bottom:2rem;">
    <a href="add_category.php" class="adminit-btn" style="background:#22c55e;color:#fff;padding:0.5rem 1.2rem;border-radius:6px;text-decoration:none;">+ เพิ่มหมวดหมู่ใหม่</a>
</div>
<table style="width:100%;border-collapse:collapse;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="padding:0.75rem;">สี</th>
            <th style="padding:0.75rem;">ไอคอน</th>
            <th style="padding:0.75rem;">ชื่อหมวดหมู่</th>
            <th style="padding:0.75rem;text-align:right;">จัดการ</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($titles as $cat): ?>
        <tr>
            <td style="padding:0.75rem;">
                <div style="width: 24px; height: 24px; border-radius: 50%; background-color: <?= htmlspecialchars($cat['color']) ?>;"></div>
            </td>
            <td style="padding:0.75rem;">
                <svg width="24" height="24"><use href="#icon-<?= htmlspecialchars($cat['icon']) ?>"></use></svg>
            </td>
            <td style="padding:0.75rem;"><?= htmlspecialchars($cat['title']) ?></td>
            <td style="padding:0.75rem;text-align:right;">
                <a href="edit_category.php?id=<?= $cat['id'] ?>" class="adminit-btn" style="background:#eab308;color:#fff;padding:0.3rem 0.8rem;border-radius:6px;text-decoration:none;">แก้ไข</a>
                <a href="delete_category.php?id=<?= $cat['id'] ?>" class="adminit-btn" style="background:#dc2626;color:#fff;padding:0.3rem 0.8rem;border-radius:6px;text-decoration:none;" onclick="return confirm('ลบหมวดหมู่นี้?');">ลบ</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include_once __DIR__ . '/../inc/footer.php'; ?>