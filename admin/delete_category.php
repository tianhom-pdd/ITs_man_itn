<?php
require_once __DIR__ . '/../inc/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id) {
    // ตรวจสอบว่ามี category นี้จริง
    $res = $db->query("SELECT id FROM title_it WHERE id = $id");
    if ($res && $res->num_rows > 0) {
        // ลบ category
        $db->query("DELETE FROM title_it WHERE id = $id");
        header("Location: categories.php?success=" . urlencode("ลบหมวดหมู่สำเร็จ"));
        exit;
    } else {
        header("Location: categories.php?error=" . urlencode("ไม่พบหมวดหมู่ที่ต้องการลบ"));
        exit;
    }
} else {
    header("Location: categories.php?error=" . urlencode("ไม่พบหมวดหมู่ที่ต้องการลบ"));
    exit;
}
?>
