<?php
require_once __DIR__ . '/../inc/db.php';
include_once __DIR__ . '/../inc/admin_header.php';

// ดึงหมวดหมู่ทั้งหมดสำหรับ dropdown
$titles = [];
$res = $db->query("SELECT id, title FROM title_it ORDER BY title ASC");
while ($row = $res->fetch_assoc()) $titles[] = $row;

// กำหนด title_id_selected (ใช้ทั้ง GET/POST)
$title_id_selected = 0;
if (isset($_POST['title_id'])) {
    $title_id_selected = intval($_POST['title_id']);
} elseif (isset($_GET['title_id'])) {
    $title_id_selected = intval($_GET['title_id']);
}

// ดึง node เฉพาะ title_id ที่เลือก
$nodes = [];
if ($title_id_selected) {
    $res = $db->query("SELECT id, title FROM cause_it WHERE title_id = $title_id_selected ORDER BY id ASC");
    while ($row = $res->fetch_assoc()) $nodes[] = $row;
}

// เมื่อ submit ฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $text = trim($_POST['text'] ?? '');
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
        $stmt = $db->prepare("INSERT INTO cause_it (title, text, type, image, title_id, parent_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssii', $title, $text, $type, $image, $title_id, $parent_id);
        $stmt->execute();
        header('Location: problems.php');
        exit;
    } else {
        $error = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    }
}
?>

<h1 style="margin-bottom:2rem;">เพิ่มปัญหาใหม่</h1>

<?php if (!empty($error)): ?>
    <div style="color:#dc2626; margin-bottom:1rem;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" style="max-width:500px;">
    <div style="margin-bottom:1.2rem;">
        <label for="title_id" style="display:block; margin-bottom:0.5rem;">หมวดหมู่ <span style="color:#dc2626">*</span></label>
        <select name="title_id" id="title_id" class="adminit-input" style="width:100%;padding:0.5rem;" required onchange="this.form.submit()">
            <option value="">-- เลือกหมวดหมู่ --</option>
            <?php foreach($titles as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $title_id_selected==$cat['id']?'selected':'' ?>><?= htmlspecialchars($cat['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if ($title_id_selected): ?>
    <div style="margin-bottom:1.2rem;">
        <label for="parent_id" style="display:block; margin-bottom:0.5rem;">Node แม่ (Parent Node)</label>
        <select name="parent_id" id="parent_id" class="adminit-input" style="width:100%;padding:0.5rem;">
            <option value="0">-- ไม่มี (Node หลัก) --</option>
            <?php foreach($nodes as $node): ?>
                <option value="<?= $node['id'] ?>"><?= htmlspecialchars($node['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>
    <div style="margin-bottom:1.2rem;">
        <label for="title" style="display:block; margin-bottom:0.5rem;">ชื่อปัญหา <span style="color:#dc2626">*</span></label>
        <input type="text" name="title" id="title" class="adminit-input" style="width:100%;padding:0.5rem;" required>
    </div>
    <div style="margin-bottom:1.2rem;">
        <label for="text" style="display:block; margin-bottom:0.5rem;">รายละเอียด</label>
        <textarea name="text" id="text" class="adminit-input" style="width:100%;padding:0.5rem;" rows="3"></textarea>
    </div>
    <div style="margin-bottom:1.2rem;">
        <label for="type" style="display:block; margin-bottom:0.5rem;">ประเภท</label>
        <input type="text" name="type" id="type" class="adminit-input" style="width:100%;padding:0.5rem;">
    </div>
    <div style="margin-bottom:1.2rem;">
        <label for="image" style="display:block; margin-bottom:0.5rem;">รูปภาพ</label>
        <input type="file" name="image" id="image" accept="image/*">
    </div>
    <div>
        <button type="submit" class="adminit-btn" style="background:#22c55e;color:#fff;padding:0.5rem 1.5rem;border-radius:6px;">บันทึก</button>
        <a href="problems.php" class="adminit-btn" style="background:#888;color:#fff;padding:0.5rem 1.5rem;border-radius:6px;text-decoration:none;">ยกเลิก</a>
    </div>
</form>

<?php include_once __DIR__ . '/../inc/footer.php'; ?>