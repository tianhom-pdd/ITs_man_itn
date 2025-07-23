<?php
require_once __DIR__ . '/../inc/db.php';
$nodes = [];
$res = $db->query("SELECT id, title FROM cause_it ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $nodes[] = $row;
header('Content-Type: application/json');
echo json_encode($nodes);
