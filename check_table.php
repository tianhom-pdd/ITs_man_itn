<?php
require_once 'inc/db.php';

echo "Checking cause_it table structure:\n";
$result = $db->query('DESCRIBE cause_it');
while($row = $result->fetch_assoc()) {
    echo $row['Field'] . ' - ' . $row['Type'] . "\n";
}

echo "\nChecking if description and image_path columns exist:\n";
$has_description = false;
$has_image_path = false;

$result = $db->query('DESCRIBE cause_it');
while($row = $result->fetch_assoc()) {
    if($row['Field'] == 'description') $has_description = true;
    if($row['Field'] == 'image_path') $has_image_path = true;
}

echo "Has description column: " . ($has_description ? 'YES' : 'NO') . "\n";
echo "Has image_path column: " . ($has_image_path ? 'YES' : 'NO') . "\n";



echo "\nDone!\n";
?>
