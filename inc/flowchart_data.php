<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';

function fetchAll($db, $sql) {
    $result = $db->query($sql);
    if ($result === false) {
        http_response_code(500);
        echo json_encode([
            'error' => 'SQL Error',
            'sql' => $sql,
            'mysqli_error' => $db->error
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

$db->set_charset('utf8');

$titles = fetchAll($db, "SELECT id, title, color, icon FROM title_it ORDER BY id");
$causes = fetchAll($db, "SELECT id, title, text, image, type, title_id, parent_id FROM cause_it ORDER BY id");
$conclusions = fetchAll($db, "SELECT id, cause_id, summary FROM conclusion ORDER BY id");

$data = [
    'titles' => $titles,
    'causes' => $causes,
    'conclusions' => $conclusions
];

echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$db->close();
