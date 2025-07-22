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

if(!$has_description) {
    echo "\nAdding description column...\n";
    $db->query('ALTER TABLE cause_it ADD COLUMN description TEXT');
    echo "Description column added.\n";
}

if(!$has_image_path) {
    echo "\nAdding image_path column...\n";
    $db->query('ALTER TABLE cause_it ADD COLUMN image_path VARCHAR(255)');
    echo "Image_path column added.\n";
}

echo "\nDone!\n";
?>
