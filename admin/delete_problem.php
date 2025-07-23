<?php
require_once __DIR__ . '/../inc/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id) {
    $res = $db->query("SELECT id FROM cause_it WHERE id = $id");
    if ($res && $res->num_rows > 0) {
        $db->query("DELETE FROM cause_it WHERE id = $id");
        header("Location: problems.php?success=" . urlencode("ลบปัญหาสำเร็จ"));
        exit;
    } else {
        header("Location: problems.php?error=" . urlencode("ไม่พบปัญหาที่ต้องการลบ"));
        exit;
    }
} else {
    header("Location: problems.php?error=" . urlencode("ไม่พบปัญหาที่ต้องการลบ"));
    exit;
}
?>
