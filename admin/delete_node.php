<?php
require_once __DIR__ . '/../inc/db.php';

// รับค่า id ของ node ที่จะลบ
$node_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$node_id) {
    header("Location: editor.php");
    exit;
}

// ดึงข้อมูล node
$stmt = $db->prepare("SELECT * FROM cause_it WHERE id = ?");
$stmt->bind_param('i', $node_id);
$stmt->execute();
$result = $stmt->get_result();
$node = $result->fetch_assoc();

if (!$node) {
    header("Location: editor.php");
    exit;
}

$title_id = $node['title_id'];
$parent_id = $node['parent_id'];

// ตรวจสอบว่ามี node ลูกหรือไม่
$stmt = $db->prepare("SELECT COUNT(*) as child_count FROM cause_it WHERE parent_id = ?");
$stmt->bind_param('i', $node_id);
$stmt->execute();
$result = $stmt->get_result();
$child_count = $result->fetch_assoc()['child_count'];

if ($child_count > 0) {
    // มี node ลูก ลบไม่ได้
    $error_message = "ไม่สามารถลบได้เพราะมี Node ลูก " . $child_count . " รายการ กรุณาลบ Node ลูกก่อน";
    header("Location: editor.php?title_id=$title_id&parent_id=$parent_id&error=" . urlencode($error_message));
    exit;
}

// ลบ conclusion ที่เกี่ยวข้องก่อน (ถ้ามี)
$stmt = $db->prepare("DELETE FROM conclusion WHERE cause_id = ?");
$stmt->bind_param('i', $node_id);
$stmt->execute();

// ลบ node
$stmt = $db->prepare("DELETE FROM cause_it WHERE id = ?");
$stmt->bind_param('i', $node_id);
$stmt->execute();

// กลับไปหน้าเดิมพร้อมข้อความสำเร็จ
$success_message = "ลบ Node สำเร็จ";
header("Location: editor.php?title_id=$title_id&parent_id=$parent_id&success=" . urlencode($success_message));
exit;
?>
