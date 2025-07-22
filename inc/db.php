<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'it_support';

// เปลี่ยนชื่อ $conn เป็น $db เพื่อให้ไฟล์อื่นเรียกใช้ได้
$db = new mysqli($host, $user, $pass, $dbname);
if ($db->connect_error) {
    die('Database connection failed: ' . $db->connect_error);
}
$db->set_charset('utf8');
