<?php
require_once __DIR__ . '/../inc/db.php';
include_once __DIR__ . '/../inc/admin_header.php';

// ดึง node ปัญหาทั้งหมด
$nodes = [];
$res = $db->query("SELECT id, title FROM cause_it ORDER BY title ASC");
while ($row = $res->fetch_assoc()) $nodes[] = $row;

// เมื่อ submit ฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cause_id = intval($_POST['cause_id'] ?? 0);
    $summary = trim($_POST['summary'] ?? '');

    if ($cause_id && $summary !== '') {
        $stmt = $db->prepare("INSERT INTO conclusion (cause_id, summary) VALUES (?, ?)");
        $stmt->bind_param('is', $cause_id, $summary);
        $stmt->execute();
        header('Location: editor.php');
        exit;
    } else {
        $error = 'กรุณาเลือก Node และกรอกสรุป';
    }
}
?>

<h1 style="margin-bottom:2rem;">เพิ่มโหนดสรุป</h1>
<?php if (!empty($error)): ?>
    <div style="color:#dc2626; margin-bottom:1rem;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" style="max-width:500px;">
    <div style="margin-bottom:1.2rem;">
        <label for="cause_id" style="display:block; margin-bottom:0.5rem;">เลือก Node ปัญหา <span style="color:#dc2626">*</span></label>
        <select name="cause_id" id="cause_id" class="adminit-input" style="width:100%;padding:0.5rem;" required>
            <option value="">-- เลือก Node --</option>
            <?php foreach($nodes as $n): ?>
                <option value="<?= $n['id'] ?>"><?= htmlspecialchars($n['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div style="margin-bottom:1.2rem;">
        <label for="summary" style="display:block; margin-bottom:0.5rem;">สรุป <span style="color:#dc2626">*</span></label>
        <textarea name="summary" id="summary" class="adminit-input" style="width:100%;padding:0.5rem;" rows="3" required></textarea>
    </div>
    <div>
        <button type="submit" class="adminit-btn" style="background:#22c55e;color:#fff;padding:0.5rem 1.5rem;border-radius:6px;">บันทึก</button>
        <a href="editor.php" class="adminit-btn" style="background:#888;color:#fff;padding:0.5rem 1.5rem;border-radius:6px;text-decoration:none;">ยกเลิก</a>
    </div>
</form>

<?php include_once __DIR__ . '/../inc/footer.php'; ?>