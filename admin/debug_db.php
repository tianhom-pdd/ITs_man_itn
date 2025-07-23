<?php
require_once __DIR__ . '/../inc/db.php';

echo "<h2>ตรวจสอบโครงสร้างตาราง cause_it</h2>";

// ตรวจสอบโครงสร้างตาราง
$result = $db->query("DESCRIBE cause_it");
if ($result) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['Field']}</td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Key']}</td>";
        echo "<td>{$row['Default']}</td>";
        echo "<td>{$row['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . $db->error;
}

echo "<br><br>";

// ตรวจสอบข้อมูลในตาราง
echo "<h2>ข้อมูลในตาราง cause_it (5 รายการล่าสุด)</h2>";
$result = $db->query("SELECT * FROM cause_it ORDER BY id DESC LIMIT 5");
if ($result) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Title</th><th>Title_ID</th><th>Parent_ID</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['title']}</td>";
        echo "<td>{$row['title_id']}</td>";
        echo "<td>{$row['parent_id']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . $db->error;
}

echo "<br><br>";

// ตรวจสอบตาราง title_it
echo "<h2>ข้อมูลในตาราง title_it</h2>";
$result = $db->query("SELECT * FROM title_it ORDER BY id ASC");
if ($result) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Title</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['title']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . $db->error;
}

echo "<br><br>";

// ทดสอบการ insert
echo "<h2>ทดสอบการ Insert</h2>";
$test_title = "Test Node " . date('H:i:s');
$test_title_id = 1; // ใช้ title_id แรกในระบบ
$test_parent_id = 0;

// ทดสอบ insert ด้วย schema ล่าสุด
$test_text = "Test Text";
$test_image = "";
$test_type = "cause";
$stmt = $db->prepare("INSERT INTO cause_it (title, text, image, type, title_id, parent_id) VALUES (?, ?, ?, ?, ?, ?)");
if ($stmt) {
    $stmt->bind_param('ssssii', $test_title, $test_text, $test_image, $test_type, $test_title_id, $test_parent_id);
    $result = $stmt->execute();
    if ($result) {
        $new_id = $db->insert_id;
        echo "<p style='color: green;'>✅ การ Insert สำเร็จ! ID ใหม่: $new_id</p>";
        // ลบข้อมูลทดสอบ
        $db->query("DELETE FROM cause_it WHERE id = $new_id");
        echo "<p>🗑️ ลบข้อมูลทดสอบแล้ว</p>";
    } else {
        echo "<p style='color: red;'>❌ การ Insert ล้มเหลว: " . $stmt->error . "</p>";
    }
    $stmt->close();
} else {
    echo "<p style='color: red;'>❌ Prepare statement ล้มเหลว: " . $db->error . "</p>";
}
?>
