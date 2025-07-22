<?php
require_once __DIR__ . '/../inc/db.php';
include_once __DIR__ . '/../inc/admin_header.php';

// ถ้ามีการ submit ฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $color = trim($_POST['color'] ?? '#22c55e');
    $icon = trim($_POST['icon'] ?? 'category');

    if ($title !== '') {
        $stmt = $db->prepare("INSERT INTO title_it (title, color, icon) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $title, $color, $icon);
        $stmt->execute();
        header('Location: categories.php');
        exit;
    } else {
        $error = 'กรุณากรอกชื่อหมวดหมู่';
    }
}
?>

<h1 style="margin-bottom:2rem;">เพิ่มหมวดหมู่ใหม่</h1>

<?php if (!empty($error)): ?>
    <div style="color:#dc2626; margin-bottom:1rem;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" style="max-width:400px;">
    <div style="margin-bottom:1.2rem;">
        <label for="title" style="display:block; margin-bottom:0.5rem;">ชื่อหมวดหมู่ <span style="color:#dc2626">*</span></label>
        <input type="text" name="title" id="title" class="adminit-input" style="width:100%;padding:0.5rem;" required>
    </div>
    <div style="margin-bottom:1.2rem;">
        <label for="color" style="display:block; margin-bottom:0.5rem;">สี</label>
        <input type="color" name="color" id="color" value="#22c55e" style="width:50px;height:34px;border:none;">
    </div>
    <div style="margin-bottom:1.2rem;">
        <label for="icon" style="display:block; margin-bottom:0.5rem;">ชื่อไอคอน (icon)</label>
        <input type="text" name="icon" id="icon" class="adminit-input" style="width:100%;padding:0.5rem;" value="category">
        <div style="font-size:0.95rem;color:#888;">* ใส่ชื่อ icon ที่มีในระบบ เช่น <b>category</b>, <b>server</b>, <b>monitor</b> เป็นต้น</div>
    </div>
    <div>
        <button type="submit" class="adminit-btn" style="background:#22c55e;color:#fff;padding:0.5rem 1.5rem;border-radius:6px;">บันทึก</button>
        <a href="categories.php" class="adminit-btn" style="background:#888;color:#fff;padding:0.5rem 1.5rem;border-radius:6px;text-decoration:none;">ยกเลิก</a>
    </div>
</form>

<?php include_once __DIR__ . '/../inc/footer.php'; ?>