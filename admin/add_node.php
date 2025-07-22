<?php
require_once __DIR__ . '/../inc/db.php';
include_once __DIR__ . '/../inc/admin_header.php';

// ดึงหมวดหมู่
$titles = [];
$res = $db->query("SELECT id, title FROM title_it ORDER BY title ASC");
while ($row = $res->fetch_assoc()) $titles[] = $row;

// ดึง Node ทั้งหมด (สำหรับเลือก Parent)
$nodes = [];
$res = $db->query("SELECT id, title FROM cause_it ORDER BY title ASC");
while ($row = $res->fetch_assoc()) $nodes[] = $row;

// เมื่อ submit ฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $title_id = intval($_POST['title_id'] ?? 0);
    $parent_id = intval($_POST['parent_id'] ?? 0);
    $image = null;

    // อัปโหลดรูปถ้ามี
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $fname = uniqid('img_', true) . '.' . $ext;
        $target = __DIR__ . '/../uploads/' . $fname;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $fname;
        }
    }

    if ($title && $title_id) {
        $stmt = $db->prepare("INSERT INTO cause_it (title, type, image, title_id, parent_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssii', $title, $type, $image, $title_id, $parent_id);
        $stmt->execute();
        header('Location: editor.php');
        exit;
    } else {
        $error = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    }
}
?>

<h1 style="margin-bottom:2rem;">เพิ่ม Node ใหม่</h1>
<?php if (!empty($error)): ?>
    <div style="color:#dc2626; margin-bottom:1rem;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" style="max-width:500px;">
    <div style="margin-bottom:1.2rem;">
        <label for="title_id" style="display:block; margin-bottom:0.5rem;">หมวดหมู่ <span style="color:#dc2626">*</span></label>
        <select name="title_id" id="title_id" class="adminit-input" style="width:100%;padding:0.5rem;" required>
            <option value="">-- เลือกหมวดหมู่ --</option>
            <?php foreach($titles as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div style="margin-bottom:1.2rem;">
        <label for="title" style="display:block; margin-bottom:0.5rem;">ชื่อ Node <span style="color:#dc2626">*</span></label>
        <input type="text" name="title" id="title" class="adminit-input" style="width:100%;padding:0.5rem;" required>
    </div>
    <div style="margin-bottom:1.2rem;">
        <label for="type" style="display:block; margin-bottom:0.5rem;">ประเภท</label>
        <input type="text" name="type" id="type" class="adminit-input" style="width:100%;padding:0.5rem;">
    </div>
    <div style="margin-bottom:1.2rem;">
        <label for="parent_id" style="display:block; margin-bottom:0.5rem;">Parent Node</label>
        <select name="parent_id" id="parent_id" class="adminit-input" style="width:100%;padding:0.5rem;">
            <option value="0">-- ไม่มี --</option>
            <?php foreach($nodes as $n): ?>
                <option value="<?= $n['id'] ?>"><?= htmlspecialchars($n['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div style="margin-bottom:1.2rem;">
        <label for="image" style="display:block; margin-bottom:0.5rem;">รูปภาพ</label>
        <input type="file" name="image" id="image" accept="image/*">
    </div>
    <div>
        <button type="submit" class="adminit-btn" style="background:#22c55e;color:#fff;padding:0.5rem 1.5rem;border-radius:6px;">บันทึก</button>
        <a href="editor.php" class="adminit-btn" style="background:#888;color:#fff;padding:0.5rem 1.5rem;border-radius:6px;text-decoration:none;">ยกเลิก</a>
    </div>
</form>

<?php include_once __DIR__ . '/../inc/footer.php'; ?>