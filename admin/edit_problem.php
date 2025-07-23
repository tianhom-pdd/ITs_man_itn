<?php
require_once __DIR__ . '/../inc/db.php';
include_once __DIR__ . '/../inc/admin_header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error_message = '';
$success_message = '';

// ดึงข้อมูลปัญหาเดิม
$problem = null;
if ($id) {
    $res = $db->query("SELECT * FROM cause_it WHERE id = $id");
    if ($res && $res->num_rows > 0) {
        $problem = $res->fetch_assoc();
    }
}

// ดึงหมวดหมู่ทั้งหมด
$titles = [];
$res = $db->query("SELECT id, title FROM title_it ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $titles[] = $row;

// handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_problem') {
    $title = trim($_POST['title']);
    $text = isset($_POST['text']) ? trim($_POST['text']) : '';
    $image = trim($_POST['image']);
    $title_id = intval($_POST['title_id']);
    $parent_id = intval($_POST['parent_id']);
    if ($title && $title_id) {
        $stmt = $db->prepare("UPDATE cause_it SET title = ?, text = ?, image = ?, title_id = ?, parent_id = ? WHERE id = ?");
        $stmt->bind_param('sssiii', $title, $text, $image, $title_id, $parent_id, $id);
        if ($stmt->execute()) {
            header("Location: problems.php?success=" . urlencode("แก้ไขปัญหาสำเร็จ"));
            exit;
        } else {
            $error_message = 'เกิดข้อผิดพลาดในการแก้ไข';
        }
        $stmt->close();
    } else {
        $error_message = 'กรุณากรอกข้อมูลให้ครบถ้วน (ชื่อปัญหาและหมวดหมู่)';
    }
}
?>

<h1 style="margin-bottom:2rem;">แก้ไขปัญหา</h1>
<?php if ($error_message): ?>
    <div class="alert alert-error">
        <span>⚠️ <?= htmlspecialchars($error_message) ?></span>
    </div>
<?php endif; ?>
<form method="POST">
    <input type="hidden" name="action" value="edit_problem">
    <div style="margin-bottom:1rem;">
        <label>ชื่อปัญหา:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($problem['title'] ?? '') ?>" required>
    </div>
    <div style="margin-bottom:1rem;">
        <label>รายละเอียด:</label>
        <textarea name="text"><?= htmlspecialchars($problem['text'] ?? '') ?></textarea>
    </div>
    <div style="margin-bottom:1rem;">
        <label>รูปภาพ (URL):</label>
        <input type="text" name="image" value="<?= htmlspecialchars($problem['image'] ?? '') ?>">
    </div>
    <div style="margin-bottom:1rem;">
        <label>หมวดหมู่:</label>
        <select name="title_id" required>
            <?php foreach($titles as $t): ?>
                <option value="<?= $t['id'] ?>" <?= ($problem['title_id'] ?? 0) == $t['id'] ? 'selected' : '' ?>><?= htmlspecialchars($t['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php
    // ดึง node ทั้งหมดสำหรับ dropdown parent
    $parent_nodes = [];
    $res = $db->query("SELECT id, title FROM cause_it ORDER BY id ASC");
    while ($row = $res->fetch_assoc()) $parent_nodes[] = $row;
    ?>
    <div style="margin-bottom:1rem;">
        <label>Parent Node:</label>
        <select name="parent_id">
            <option value="0">-- ไม่มี --</option>
            <?php foreach($parent_nodes as $node): ?>
                <option value="<?= $node['id'] ?>" <?= ($problem['parent_id'] ?? 0) == $node['id'] ? 'selected' : '' ?>><?= htmlspecialchars($node['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" style="background:#eab308;color:#fff;padding:0.5rem 1.2rem;border-radius:6px;border:none;cursor:pointer;">บันทึกการแก้ไข</button>
    <a href="problems.php" style="margin-left:1rem;">กลับ</a>
</form>

<?php include_once __DIR__ . '/../inc/footer.php'; ?>
