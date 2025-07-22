<?php
require_once __DIR__ . '/../inc/db.php';
include_once __DIR__ . '/../inc/admin_header.php';

// ดึงหัวข้อปัญหาทั้งหมด พร้อมชื่อหมวดหมู่
$res = $db->query("
    SELECT c.id, c.title, c.text, c.type, c.image, c.title_id, c.parent_id, t.title AS category_title
    FROM cause_it c
    LEFT JOIN title_it t ON c.title_id = t.id
    ORDER BY c.id ASC
");
$problems = [];
while ($row = $res->fetch_assoc()) $problems[] = $row;
?>

<h1 style="margin-bottom:2rem;">จัดการปัญหา</h1>
<div style="margin-bottom:2rem;">
    <a href="add_problem.php" class="adminit-btn" style="background:#22c55e;color:#fff;padding:0.5rem 1.2rem;border-radius:6px;text-decoration:none;">+ เพิ่มปัญหาใหม่</a>
</div>
<table style="width:100%;border-collapse:collapse;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="padding:0.75rem;">หมวดหมู่</th>
            <th style="padding:0.75rem;">ชื่อปัญหา</th>
            <th style="padding:0.75rem;">รายละเอียด</th>
            <th style="padding:0.75rem;">ประเภท</th>
            <th style="padding:0.75rem;">รูปภาพ</th>
            <th style="padding:0.75rem;text-align:right;">จัดการ</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($problems as $p): ?>
        <tr>
            <td style="padding:0.75rem;"><?= htmlspecialchars($p['category_title']) ?></td>
            <td style="padding:0.75rem;"><?= htmlspecialchars($p['title']) ?></td>
            <td style="padding:0.75rem;"><?= htmlspecialchars($p['text']) ?></td>
            <td style="padding:0.75rem;"><?= htmlspecialchars($p['type']) ?></td>
            <td style="padding:0.75rem;">
                <?php if($p['image']): ?>
                    <img src="../uploads/<?= htmlspecialchars($p['image']) ?>" alt="img" style="max-width:48px;max-height:48px;">
                <?php endif; ?>
            </td>
            <td style="padding:0.75rem;text-align:right;">
                <a href="edit_problem.php?id=<?= $p['id'] ?>" class="adminit-btn" style="background:#eab308;color:#fff;padding:0.3rem 0.8rem;border-radius:6px;text-decoration:none;">แก้ไข</a>
                <a href="delete_problem.php?id=<?= $p['id'] ?>" class="adminit-btn" style="background:#dc2626;color:#fff;padding:0.3rem 0.8rem;border-radius:6px;text-decoration:none;" onclick="return confirm('ลบปัญหานี้?');">ลบ</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include_once __DIR__ . '/../inc/footer.php'; ?>