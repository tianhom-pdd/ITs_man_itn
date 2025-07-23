<?php
require_once __DIR__ . '/../inc/db.php';
include_once __DIR__ . '/../inc/admin_header.php';

// ดึงหมวดหมู่ทั้งหมด
$titles = [];
$res = $db->query("SELECT id, title FROM title_it ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $titles[] = $row;

// รับค่า title_id และ selected_parent_id จาก GET
$title_id = isset($_GET['title_id']) ? intval($_GET['title_id']) : 0;
$selected_parent_id = isset($_GET['parent_id']) ? intval($_GET['parent_id']) : 0;

// ดึง node เฉพาะของ title_id ที่เลือก
$nodes = [];
if ($title_id) {
    $res = $db->query("SELECT * FROM cause_it WHERE title_id = $title_id ORDER BY id ASC");
    while ($row = $res->fetch_assoc()) $nodes[] = $row;
}

// ดึง conclusion เฉพาะของ title_id ที่เลือก
$conclusions = [];
if ($title_id) {
    $res = $db->query("SELECT c.* FROM conclusion c INNER JOIN cause_it n ON c.cause_id = n.id WHERE n.title_id = $title_id");
    while ($row = $res->fetch_assoc()) $conclusions[] = $row;
}

// ฟังก์ชันหาลูก node
function get_child_nodes($nodes, $parent_id) {
    $children = [];
    foreach ($nodes as $n) {
        // จัดการทั้ง NULL และ 0 สำหรับ Level 1 nodes
        $node_parent_id = ($n['parent_id'] === null) ? 0 : (int)$n['parent_id'];
        if ($node_parent_id === (int)$parent_id) {
            $children[] = $n;
        }
    }
    return $children;
}

// ฟังก์ชันเช็คว่า node นี้เป็น node สรุปหรือไม่
function get_conclusion($conclusions, $node_id) {
    foreach ($conclusions as $c) {
        if ((int)$c['cause_id'] === (int)$node_id) return $c['summary'];
    }
    return null;
}

// --- ส่วนเพิ่ม node ใหม่แบบ inline ---
$add_node_parent = null; // กำหนดค่าเริ่มต้น
$add_node_error = ''; // กำหนดค่าเริ่มต้น

// ข้อความแจ้งเตือน
$error_message = isset($_GET['error']) ? $_GET['error'] : '';
$success_message = isset($_GET['success']) ? $_GET['success'] : '';

// Handle edit node
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_node'])) {
    $node_id = intval($_POST['node_id']);
    $new_title = trim($_POST['new_title']);
    $new_description = trim($_POST['new_description']);
    $current_parent_id = isset($_POST['current_parent_id']) ? intval($_POST['current_parent_id']) : 0;
    
    $image_path = '';
    
    // จัดการการอัพโหลดรูปภาพ
    if (isset($_FILES['node_image']) && $_FILES['node_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/nodes/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['node_image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = 'node_' . $node_id . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['node_image']['tmp_name'], $upload_path)) {
                $image_path = 'uploads/nodes/' . $new_filename;
            }
        }
    }
    
    if ($new_title && $node_id) {
        try {
            if ($image_path) {
                $stmt = $db->prepare("UPDATE cause_it SET title = ?, text = ?, image = ? WHERE id = ?");
                $stmt->bind_param('sssi', $new_title, $new_description, $image_path, $node_id);
            } else {
                $stmt = $db->prepare("UPDATE cause_it SET title = ?, text = ? WHERE id = ?");
                $stmt->bind_param('ssi', $new_title, $new_description, $node_id);
            }
            
            if ($stmt->execute()) {
                header("Location: editor.php?title_id=$title_id&parent_id=$current_parent_id&success=" . urlencode("แก้ไข Node สำเร็จ"));
                exit;
            } else {
                $error_message = 'เกิดข้อผิดพลาดในการแก้ไข';
            }
            $stmt->close();
        } catch (Exception $e) {
            $error_message = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
        }
    } else {
        $error_message = 'กรุณากรอกชื่อ Node';
    }
}

// Handle add summary
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_summary'])) {
    $cause_id = intval($_POST['cause_id']);
    $summary = trim($_POST['summary']);
    $current_parent_id = isset($_POST['current_parent_id']) ? intval($_POST['current_parent_id']) : 0;
    
    if ($summary && $cause_id) {
        try {
            // ตรวจสอบว่ามี summary อยู่แล้วหรือไม่
            $check_stmt = $db->prepare("SELECT id FROM conclusion WHERE cause_id = ?");
            $check_stmt->bind_param('i', $cause_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            if ($result->num_rows > 0) {
                // อัพเดท summary ที่มีอยู่
                $stmt = $db->prepare("UPDATE conclusion SET summary = ? WHERE cause_id = ?");
                $stmt->bind_param('si', $summary, $cause_id);
            } else {
                // เพิ่ม summary ใหม่
                $stmt = $db->prepare("INSERT INTO conclusion (cause_id, summary) VALUES (?, ?)");
                $stmt->bind_param('is', $cause_id, $summary);
            }
            
            if ($stmt->execute()) {
                header("Location: editor.php?title_id=$title_id&parent_id=$current_parent_id&success=" . urlencode("เพิ่มข้อสรุปสำเร็จ"));
                exit;
            } else {
                $error_message = 'เกิดข้อผิดพลาดในการเพิ่มข้อสรุป';
            }
            
            $check_stmt->close();
            $stmt->close();
        } catch (Exception $e) {
            $error_message = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
        }
    } else {
        $error_message = 'กรุณากรอกข้อสรุป';
    }
}

// Handle add child node
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_child_node'])) {
    $parent_id = intval($_POST['parent_id']);
    $child_title = trim($_POST['child_title']);
    $child_description = trim($_POST['child_description']);
    $current_parent_id = isset($_POST['current_parent_id']) ? intval($_POST['current_parent_id']) : 0;
    
    // Debug log
    error_log("Add Child Node: parent_id=$parent_id, title='$child_title', title_id=$title_id");
    
    $image_path = '';
    
    // จัดการการอัพโหลดรูปภาพ
    if (isset($_FILES['child_image']) && $_FILES['child_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/nodes/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['child_image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = 'node_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['child_image']['tmp_name'], $upload_path)) {
                $image_path = 'uploads/nodes/' . $new_filename;
            }
        }
    }
    
    if ($child_title && $title_id) {
        try {
            // จัดการ parent_id: ถ้าเป็น 0 ให้ใช้ NULL สำหรับ Level 1 nodes
            $db_parent_id = ($parent_id == 0) ? null : $parent_id;
            
            // เตรียม SQL statement
            $sql = "INSERT INTO cause_it (title, description, image_path, title_id, parent_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $db->error);
            }
            
            // Bind parameters
            if ($db_parent_id === null) {
                // สำหรับ NULL parent_id (Level 1 nodes)
                $stmt->bind_param('sssis', $child_title, $child_description, $image_path, $title_id, $db_parent_id);
            } else {
                // สำหรับ parent_id ที่มีค่า
                $stmt->bind_param('sssii', $child_title, $child_description, $image_path, $title_id, $db_parent_id);
            }
            
            $result = $stmt->execute();
            
            if (!$result) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $new_id = $db->insert_id;
            $stmt->close();
            
            error_log("Insert successful: new node ID = $new_id");
            
            header("Location: editor.php?title_id=$title_id&parent_id=$current_parent_id&success=" . urlencode("เพิ่ม Node สำเร็จ"));
            exit;
            
        } catch (Exception $e) {
            $error_message = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
            error_log("Database error: " . $e->getMessage());
        }
    } else {
        $error_message = 'กรุณากรอกชื่อ Node ลูก';
        error_log("Validation error: child_title='$child_title', title_id=$title_id");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_node_inline'])) {
    $parent_id = intval($_POST['parent_id']);
    $title = trim($_POST['title']);
    $current_parent_id = isset($_POST['current_parent_id']) ? intval($_POST['current_parent_id']) : 0;
    
    // Debug: แสดงค่าที่ได้รับ
    error_log("Debug: POST data - parent_id: $parent_id, title: '$title', title_id: $title_id");
    
    if ($title && $title_id) {
        try {
            // ตรวจสอบการเชื่อมต่อ database
            if (!$db) {
                throw new Exception("Database connection failed");
            }
            
            // จัดการ parent_id: ถ้าเป็น 0 ให้ใช้ NULL สำหรับ Level 1 nodes
            $db_parent_id = ($parent_id == 0) ? null : $parent_id;
            
            // เตรียม SQL statement
            $sql = "INSERT INTO cause_it (title, description, image_path, title_id, parent_id) VALUES (?, ?, ?, ?, ?)";
            error_log("Debug: SQL - $sql");
            error_log("Debug: Values - title: '$title', title_id: $title_id, parent_id: " . ($db_parent_id === null ? 'NULL' : $db_parent_id));
            
            $stmt = $db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $db->error);
            }
            
            // ใช้ parameter type ที่เหมาะสม
            if ($db_parent_id === null) {
                $stmt->bind_param('sssis', $title, '', '', $title_id, $db_parent_id);
            } else {
                $stmt->bind_param('sssii', $title, '', '', $title_id, $db_parent_id);
            }
            
            $result = $stmt->execute();
            
            if (!$result) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $new_id = $db->insert_id;
            error_log("Debug: Insert successful, new ID: $new_id");
            
            $stmt->close();
            
            header("Location: editor.php?title_id=$title_id&parent_id=$current_parent_id&success=" . urlencode("เพิ่ม Node สำเร็จ"));
            exit;
            
        } catch (Exception $e) {
            $add_node_error = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
            error_log("Database error: " . $e->getMessage());
        }
    } else {
        $add_node_error = 'กรุณากรอกชื่อ node';
        error_log("Validation error: title='$title', title_id=$title_id");
    }
    $add_node_parent = $parent_id;
}
?>

<style>
/* ใช้สีเดียวกับหน้าแอดมินอื่น */
body {
    background-color: #0f172a; /* slate-900 */
    font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
    color: #f1f5f9; /* slate-100 */
    margin: 0;
    padding: 20px;
}

.flowchart-uniq-container {
    max-width: 1200px;
    margin: 0 auto;
    background: #1e293b; /* slate-800 */
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    padding: 30px;
    border: 1px solid #475569;
}

.flowchart-uniq-select-title {
    text-align: center;
    margin-bottom: 30px;
    padding: 20px;
    background: #334155; /* slate-700 */
    border-radius: 5px;
    border: 1px solid #475569;
}

.flowchart-uniq-select-title label {
    font-weight: bold;
    color: #f1f5f9;
    margin-right: 15px;
    font-size: 16px;
}

.flowchart-uniq-select-title select {
    padding: 10px 15px;
    font-size: 14px;
    border: 1px solid #475569;
    border-radius: 4px;
    background: #1e293b;
    color: #f1f5f9;
    min-width: 250px;
}

.flowchart-uniq-select-title select:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.flowchart-uniq-title {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 30px;
    color: #f1f5f9;
}

.current-level-header {
    text-align: center;
    margin-bottom: 20px;
    padding: 15px;
    background: #334155;
    border-radius: 5px;
    border: 1px solid #475569;
}

.current-level-header h3 {
    font-size: 18px;
    font-weight: bold;
    color: #f1f5f9;
    margin: 0;
}

.flowchart-uniq-level {
    display: flex;
    gap: 15px;
    overflow-x: auto;
    padding: 20px;
    margin-bottom: 20px;
    background: #1e293b;
    border-radius: 5px;
    border: 1px solid #475569;
    min-height: 120px;
    align-items: stretch;
}

.flowchart-uniq-node {
    background: #334155;
    border: 2px solid #475569;
    border-radius: 5px;
    min-width: 200px;
    max-width: 250px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.2s;
}

.flowchart-uniq-node:hover {
    border-color: #4f46e5;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    transform: translateY(-2px);
}

.flowchart-uniq-node-title {
    font-weight: bold;
    font-size: 14px;
    text-align: center;
    color: #f1f5f9;
    margin-bottom: 10px;
    word-break: break-word;
}

.parent-reference {
    background: #1e293b;
    border: 1px solid #475569;
    border-radius: 3px;
    padding: 5px 8px;
    font-size: 12px;
    text-align: center;
    margin-bottom: 8px;
    color: #94a3b8;
}

.selected-badge {
    background: #eab308; /* warning-color */
    color: #0f172a;
    font-weight: bold;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 12px;
    margin-bottom: 8px;
    text-align: center;
}

.flowchart-uniq-node-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-top: 10px;
}

.flowchart-uniq-node-actions.has-summary {
    grid-template-columns: 1fr;
}

.flowchart-uniq-btn {
    background: #475569;
    color: #f1f5f9;
    border: none;
    border-radius: 3px;
    padding: 8px 12px;
    cursor: pointer;
    font-size: 12px;
    text-decoration: none;
    text-align: center;
    transition: background-color 0.2s;
}

.flowchart-uniq-btn:hover {
    background: #64748b;
    color: #f1f5f9;
}

.flowchart-uniq-btn-edit {
    background: #eab308;
    color: #0f172a;
}

.flowchart-uniq-btn-edit:hover {
    background: #d97706;
    color: #0f172a;
}

.flowchart-uniq-btn-del {
    background: #ef4444;
}

.flowchart-uniq-btn-del:hover {
    background: #dc2626;
}

.flowchart-uniq-btn-add {
    background: #22c55e;
    grid-column: 1 / -1;
}

.flowchart-uniq-btn-add:hover {
    background: #16a34a;
}

.flowchart-uniq-btn-summary {
    background: #06b6d4;
    grid-column: 1 / -1;
}

.flowchart-uniq-btn-summary:hover {
    background: #0891b2;
}

.flowchart-uniq-btn-view {
    background: #4f46e5;
    grid-column: 1 / -1;
}

.flowchart-uniq-btn-view:hover {
    background: #4338ca;
}

.flowchart-uniq-btn-cancel {
    background: #f97316;
    grid-column: 1 / -1;
}

.flowchart-uniq-btn-cancel:hover {
    background: #ea580c;
}

.flowchart-uniq-node-summary {
    border-color: #22c55e !important;
    background: #1e293b !important;
}

.flowchart-uniq-summary-label {
    background: #22c55e;
    color: #0f172a;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 3px;
    margin-bottom: 8px;
    text-align: center;
}

.selected-node {
    border-color: #eab308 !important;
    background: #1e293b !important;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
    border: 1px solid;
}

.alert-error {
    background: #1e293b;
    border-color: #ef4444;
    color: #fecaca;
}

.alert-success {
    background: #1e293b;
    border-color: #22c55e;
    color: #bbf7d0;
}

.add-first-node-section {
    text-align: center;
    margin: 40px 0;
}

.add-first-node-container {
    background: #334155;
    border: 2px dashed #4f46e5;
    border-radius: 8px;
    padding: 40px;
    max-width: 500px;
    margin: 0 auto;
}

.add-first-node-container h3 {
    font-size: 20px;
    margin-bottom: 10px;
    color: #f1f5f9;
}

.add-first-node-container p {
    color: #94a3b8;
    margin-bottom: 20px;
}

.first-node-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    align-items: center;
}

.first-node-form input[type="text"] {
    padding: 12px;
    border: 1px solid #475569;
    border-radius: 4px;
    font-size: 14px;
    width: 300px;
    max-width: 100%;
    background: #1e293b;
    color: #f1f5f9;
}

.first-node-form input[type="text"]:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.first-node-form button,
.start-flowchart-btn {
    background: #4f46e5;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
}

.first-node-form button:hover,
.start-flowchart-btn:hover {
    background: #4338ca;
}

.flowchart-uniq-add-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 10px;
    padding: 15px;
    background: #1e293b;
    border: 1px solid #475569;
    border-radius: 4px;
}

.flowchart-uniq-add-form input[type="text"] {
    padding: 8px;
    border: 1px solid #475569;
    border-radius: 3px;
    font-size: 13px;
    background: #334155;
    color: #f1f5f9;
}

.flowchart-uniq-add-form input[type="text"]:focus {
    outline: none;
    border-color: #4f46e5;
}

.flowchart-uniq-add-form button {
    background: #4f46e5;
    color: white;
    border: none;
    padding: 8px;
    border-radius: 3px;
    font-size: 13px;
    cursor: pointer;
}

.flowchart-uniq-add-form button:hover {
    background: #4338ca;
}

.flowchart-uniq-error {
    color: #fecaca;
    font-size: 12px;
    margin-top: 8px;
    padding: 8px;
    background: #1e293b;
    border: 1px solid #ef4444;
    border-radius: 3px;
}

.empty-state {
    text-align: center;
    color: #94a3b8;
    padding: 40px;
    background: #334155;
    border-radius: 5px;
    border: 1px solid #475569;
    margin: 20px 0;
}

.empty-state h3 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #f1f5f9;
}

.empty-state p {
    font-size: 14px;
    color: #94a3b8;
}

/* เพิ่ม Smooth Scroll และ User Experience */
html {
    scroll-behavior: smooth;
}

.flowchart-uniq-node {
    position: relative;
}

.flowchart-uniq-node.new-node {
    animation: newNodeHighlight 2s ease-in-out;
}

@keyframes newNodeHighlight {
    0% {
        background: #22c55e;
        transform: scale(1.05);
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.5);
    }
    100% {
        background: #334155;
        transform: scale(1);
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
}

.level-highlight {
    position: relative;
    animation: levelPulse 1s ease-in-out;
}

@keyframes levelPulse {
    0%, 100% {
        border-color: #475569;
    }
    50% {
        border-color: #4f46e5;
        box-shadow: 0 0 15px rgba(79, 70, 229, 0.3);
    }
}

.smooth-transition {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    backdrop-filter: blur(4px);
}

.modal-overlay.show {
    display: flex;
    animation: modalFadeIn 0.3s ease-out;
}

.modal-content {
    background: #1e293b;
    border-radius: 8px;
    padding: 30px;
    max-width: 500px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    border: 1px solid #475569;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    animation: modalSlideIn 0.3s ease-out;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #475569;
}

.modal-title {
    font-size: 20px;
    font-weight: bold;
    color: #f1f5f9;
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: all 0.2s;
}

.modal-close:hover {
    background: #334155;
    color: #f1f5f9;
}

.modal-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.modal-form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.modal-form-label {
    font-weight: 600;
    color: #f1f5f9;
    font-size: 14px;
}

.modal-form-input,
.modal-form-textarea {
    padding: 12px;
    border: 1px solid #475569;
    border-radius: 4px;
    background: #334155;
    color: #f1f5f9;
    font-size: 14px;
    transition: border-color 0.2s;
}

.modal-form-input:focus,
.modal-form-textarea:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.modal-form-textarea {
    min-height: 100px;
    resize: vertical;
}

.modal-form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 10px;
}

.modal-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    min-width: 80px;
}

.modal-btn-primary {
    background: #4f46e5;
    color: white;
}

.modal-btn-primary:hover {
    background: #4338ca;
}

.modal-btn-secondary {
    background: #475569;
    color: #f1f5f9;
}

.modal-btn-secondary:hover {
    background: #64748b;
}

.modal-btn-success {
    background: #22c55e;
    color: white;
}

.modal-btn-success:hover {
    background: #16a34a;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Image Preview Styles */
.image-preview-container {
    margin-top: 10px;
    text-align: center;
}

.image-preview {
    max-width: 200px;
    max-height: 150px;
    border-radius: 4px;
    border: 2px solid #475569;
    display: none;
}

.image-upload-info {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 5px;
}

.remove-image-btn {
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 3px;
    padding: 5px 10px;
    font-size: 12px;
    cursor: pointer;
    margin-top: 5px;
    display: none;
}

.remove-image-btn:hover {
    background: #dc2626;
}
</style>

<script>
// JavaScript สำหรับปรับปรุง UX
document.addEventListener('DOMContentLoaded', function() {
    // เก็บตำแหน่งการสกอลล์
    function saveScrollPosition() {
        sessionStorage.setItem('flowchart_scroll', window.pageYOffset);
    }
    
    // กู้คืนตำแหน่งการสกอลล์
    function restoreScrollPosition() {
        const savedPosition = sessionStorage.getItem('flowchart_scroll');
        if (savedPosition) {
            setTimeout(() => {
                window.scrollTo({
                    top: parseInt(savedPosition),
                    behavior: 'smooth'
                });
                sessionStorage.removeItem('flowchart_scroll');
            }, 100);
        }
    }
    
    // เก็บตำแหน่งก่อนส่งฟอร์ม
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', saveScrollPosition);
    });
    
    // เก็บตำแหน่งก่อนคลิกลิงก์
    const links = document.querySelectorAll('a[href*="editor.php"]');
    links.forEach(link => {
        link.addEventListener('click', saveScrollPosition);
    });
    
    // กู้คืนตำแหน่งเมื่อโหลดหน้า
    restoreScrollPosition();
    
    // Highlight node ใหม่ถ้ามี
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success')) {
        // หา level ที่มี node ใหม่และ highlight
        const levels = document.querySelectorAll('.flowchart-uniq-level');
        if (levels.length > 0) {
            const lastLevel = levels[levels.length - 1];
            lastLevel.classList.add('level-highlight');
            
            // เลื่อนไปที่ level นี้
            setTimeout(() => {
                lastLevel.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 200);
        }
    }
    
    // เพิ่ม smooth transition ให้ปุ่มต่างๆ
    const buttons = document.querySelectorAll('.flowchart-uniq-btn');
    buttons.forEach(button => {
        button.classList.add('smooth-transition');
    });
    
    // เพิ่ม loading indicator สำหรับการส่งฟอร์ม
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                const originalText = submitButton.textContent;
                submitButton.textContent = 'กำลังประมวลผล...';
                submitButton.disabled = true;
                
                // คืนค่าเดิมหากมีข้อผิดพลาด
                setTimeout(() => {
                    submitButton.textContent = originalText;
                    submitButton.disabled = false;
                }, 5000);
            }
        });
    });
    
    // เพิ่ม keyboard navigation
    document.addEventListener('keydown', function(e) {
        // กด Escape เพื่อกลับไป Level 1
        if (e.key === 'Escape') {
            const urlParams = new URLSearchParams(window.location.search);
            const titleId = urlParams.get('title_id');
            if (titleId && urlParams.get('parent_id')) {
                window.location.href = `editor.php?title_id=${titleId}`;
            }
        }
    });
    
    // เพิ่ม auto-focus สำหรับ input field ที่เปิดอยู่
    const activeInputs = document.querySelectorAll('.flowchart-uniq-add-form input[type="text"]');
    if (activeInputs.length > 0) {
        // Focus ที่ input แรกที่พบ
        activeInputs[0].focus();
        activeInputs[0].select();
    }
    
    // เพิ่ม confirmation ที่ดีขึ้นสำหรับการลบ
    const deleteLinks = document.querySelectorAll('a[href*="delete_node.php"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const nodeTitle = this.closest('.flowchart-uniq-node').querySelector('.flowchart-uniq-node-title').textContent;
            
            if (confirm(`คุณแน่ใจหรือไม่ที่จะลบ Node "${nodeTitle}"?\n\nการลบจะไม่สามารถยกเลิกได้`)) {
                saveScrollPosition();
                window.location.href = this.href;
            }
        });
    });
    
    // Modal functionality
    function openModal(modalId) {
        console.log('Opening modal:', modalId); // Debug
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            
            // Focus ที่ input แรก
            const firstInput = modal.querySelector('input:not([type="hidden"]), textarea');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 300);
            }
        } else {
            console.error('Modal not found:', modalId);
        }
    }
    
    function closeModal(modalId) {
        console.log('Closing modal:', modalId); // Debug
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    }
    
    // เพิ่ม event listeners สำหรับ modal
    document.addEventListener('click', function(e) {
        console.log('Document click detected:', e.target); // Debug
        
        // Close modal เมื่อคลิก overlay
        if (e.target.classList.contains('modal-overlay')) {
            e.target.classList.remove('show');
            document.body.style.overflow = '';
        }
        
        // Open edit modal
        if (e.target.closest('.edit-node-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.edit-node-btn');
            const nodeId = btn.dataset.nodeId;
            const nodeTitle = btn.dataset.nodeTitle;
            
            console.log('Edit button clicked:', { nodeId, nodeTitle }); // Debug
            
            // เซ็ตค่าใน modal
            document.getElementById('editNodeId').value = nodeId;
            document.getElementById('editNodeTitle').value = nodeTitle;
            document.getElementById('editNodeDescription').value = btn.dataset.nodeDescription || '';
            
            openModal('editNodeModal');
        }
        
        // Open summary modal
        if (e.target.closest('.add-summary-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.add-summary-btn');
            const nodeId = btn.dataset.nodeId;
            const nodeTitle = btn.dataset.nodeTitle;
            
            console.log('Summary button clicked:', { nodeId, nodeTitle }); // Debug
            
            // เซ็ตค่าใน modal
            document.getElementById('summaryNodeId').value = nodeId;
            document.getElementById('summaryNodeTitle').textContent = nodeTitle;
            
            openModal('addSummaryModal');
        }
        
        // Open add child node modal
        if (e.target.closest('.add-child-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.add-child-btn');
            const parentId = btn.dataset.parentId;
            const parentTitle = btn.dataset.parentTitle;
            
            console.log('Add child button clicked:', { parentId, parentTitle }); // Debug
            
            // เซ็ตค่าใน modal - ใช้ ID ที่ถูกต้อง
            const childParentIdEl = document.getElementById('childParentId');
            const childParentTitleEl = document.getElementById('childParentTitle');
            const childNodeTitleEl = document.getElementById('childNodeTitle');
            const childNodeDescEl = document.getElementById('childNodeDescription');
            
            console.log('Modal elements found:', {
                parentIdEl: !!childParentIdEl,
                parentTitleEl: !!childParentTitleEl,
                nodeTitleEl: !!childNodeTitleEl,
                nodeDescEl: !!childNodeDescEl
            });
            
            if (childParentIdEl) childParentIdEl.value = parentId;
            if (childParentTitleEl) childParentTitleEl.textContent = parentTitle;
            if (childNodeTitleEl) childNodeTitleEl.value = '';
            if (childNodeDescEl) childNodeDescEl.value = '';
            
            // Reset image preview
            const imagePreview = document.getElementById('childImagePreview');
            const removeBtn = document.getElementById('childRemoveImageBtn');
            if (imagePreview) {
                imagePreview.style.display = 'none';
                imagePreview.src = '';
            }
            if (removeBtn) {
                removeBtn.style.display = 'none';
            }
            
            openModal('addChildNodeModal');
        }
    });
    
    // Close modal เมื่อกด Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal-overlay.show');
            if (openModal) {
                openModal.classList.remove('show');
                document.body.style.overflow = '';
                return;
            }
            
            // Escape เดิมสำหรับการนำทางกลับ Level 1
            const urlParams = new URLSearchParams(window.location.search);
            const titleId = urlParams.get('title_id');
            if (titleId && urlParams.get('parent_id')) {
                window.location.href = `editor.php?title_id=${titleId}`;
            }
        }
    });
    
    // Handle form submissions
    document.getElementById('editNodeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        // แสดง loading
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'กำลังบันทึก...';
        submitBtn.disabled = true;
        
        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // รีเฟรชหน้า
            location.reload();
        })
        .catch(error => {
            alert('เกิดข้อผิดพลาด: ' + error.message);
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
    
    document.getElementById('addSummaryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        // แสดง loading
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'กำลังบันทึก...';
        submitBtn.disabled = true;
        
        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // รีเฟรชหน้า
            location.reload();
        })
        .catch(error => {
            alert('เกิดข้อผิดพลาด: ' + error.message);
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
    
    document.getElementById('addChildNodeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        // แสดง loading
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'กำลังเพิ่ม...';
        submitBtn.disabled = true;
        
        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // รีเฟรชหน้า
            location.reload();
        })
        .catch(error => {
            alert('เกิดข้อผิดพลาด: ' + error.message);
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
    
    // เพิ่ม global functions สำหรับใช้ใน HTML
    window.openModal = openModal;
    window.closeModal = closeModal;
    
    // Function สำหรับเปิด Add Child Modal แบบง่าย
    window.openAddChildModal = function(parentId, parentTitle) {
        console.log('openAddChildModal called:', { parentId, parentTitle });
        
        // เซ็ตค่าใน modal
        document.getElementById('childParentId').value = parentId;
        document.getElementById('childParentTitle').textContent = parentTitle;
        document.getElementById('childNodeTitle').value = '';
        document.getElementById('childNodeDescription').value = '';
        
        // Reset image preview
        const imagePreview = document.getElementById('childImagePreview');
        const removeBtn = document.getElementById('childRemoveImageBtn');
        if (imagePreview) {
            imagePreview.style.display = 'none';
            imagePreview.src = '';
        }
        if (removeBtn) {
            removeBtn.style.display = 'none';
        }
        
        openModal('addChildNodeModal');
    };
    
    // Image preview functionality
    function setupImagePreview(inputId, previewId, removeBtn) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        const removeButton = document.getElementById(removeBtn);
        
        if (!input || !preview) return;
        
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // ตรวจสอบประเภทไฟล์
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('กรุณาเลือกไฟล์รูปภาพ (JPG, PNG, GIF, WEBP)');
                    input.value = '';
                    return;
                }
                
                // ตรวจสอบขนาดไฟล์ (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('ขนาดไฟล์ต้องไม่เกิน 5MB');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (removeButton) {
                        removeButton.style.display = 'inline-block';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
        
        if (removeButton) {
            removeButton.addEventListener('click', function() {
                input.value = '';
                preview.style.display = 'none';
                removeButton.style.display = 'none';
            });
        }
    }
    
    // Setup image previews
    setupImagePreview('editNodeImage', 'editImagePreview', 'editRemoveImageBtn');
    setupImagePreview('childNodeImage', 'childImagePreview', 'childRemoveImageBtn');
});
</script>

<div class="flowchart-uniq-container">
    <div class="flowchart-uniq-select-title">
        <form method="get">
            <label for="title_id" style="font-weight:bold;">เลือกหมวดหมู่:</label>
            <select name="title_id" id="title_id" onchange="this.form.submit()">
                <option value="">-- เลือก --</option>
                <?php foreach($titles as $t): ?>
                    <option value="<?= $t['id'] ?>" <?= $title_id==$t['id']?'selected':'' ?>>
                        <?= htmlspecialchars($t['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if ($title_id): ?>
        <!-- แสดงข้อความแจ้งเตือน -->
        <?php if ($error_message): ?>
            <div class="alert alert-error" id="alertMessage">
                <span class="alert-icon">⚠️</span>
                <span class="alert-text"><?= htmlspecialchars($error_message) ?></span>
                <button onclick="this.parentElement.remove()" style="background:none;border:none;color:inherit;cursor:pointer;padding:0;margin-left:auto;font-size:18px;">×</button>
            </div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success" id="alertMessage">
                <span class="alert-icon">✅</span>
                <span class="alert-text"><?= htmlspecialchars($success_message) ?></span>
                <button onclick="this.parentElement.remove()" style="background:none;border:none;color:inherit;cursor:pointer;padding:0;margin-left:auto;font-size:18px;">×</button>
            </div>
        <?php endif; ?>
        
        <div class="flowchart-uniq-title">
            แก้ไข Flowchart: <span style="color:#4f46e5">
                <?= htmlspecialchars($titles[array_search($title_id, array_column($titles, 'id'))]['title']) ?>
            </span>
        </div>
        
        <!-- แสดงเส้นทางปัจจุบัน (Breadcrumb) -->
        <?php if ($selected_parent_id != 0): ?>
            <div style="background:#334155;padding:15px;border-radius:5px;margin-bottom:20px;border:1px solid #475569;">
                <div style="color:#94a3b8;font-size:14px;margin-bottom:5px;">📍 ตำแหน่งปัจจุบัน:</div>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <a href="?title_id=<?= $title_id ?>" 
                       style="color:#4f46e5;text-decoration:none;padding:5px 10px;background:#1e293b;border-radius:3px;border:1px solid #475569;font-size:13px;">
                        🏠 หน้าหลัก
                    </a>
                    <?php
                    // สร้าง breadcrumb path
                    $breadcrumb_path = [];
                    $temp_id = $selected_parent_id;
                    while ($temp_id != 0) {
                        foreach ($nodes as $n) {
                            if ($n['id'] == $temp_id) {
                                array_unshift($breadcrumb_path, $n);
                                $temp_id = ($n['parent_id'] === null) ? 0 : (int)$n['parent_id'];
                                break;
                            }
                        }
                    }
                    
                    foreach ($breadcrumb_path as $index => $breadcrumb_node): ?>
                        <span style="color:#94a3b8;">→</span>
                        <?php if ($index < count($breadcrumb_path) - 1): ?>
                            <a href="?title_id=<?= $title_id ?>&parent_id=<?= ($breadcrumb_node['parent_id'] === null) ? 0 : $breadcrumb_node['parent_id'] ?>" 
                               style="color:#4f46e5;text-decoration:none;padding:5px 10px;background:#1e293b;border-radius:3px;border:1px solid #475569;font-size:13px;">
                                <?= htmlspecialchars($breadcrumb_node['title']) ?>
                            </a>
                        <?php else: ?>
                            <span style="color:#f1f5f9;padding:5px 10px;background:#475569;border-radius:3px;font-size:13px;font-weight:bold;">
                                <?= htmlspecialchars($breadcrumb_node['title']) ?>
                            </span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    
                    <span style="margin-left:auto;">
                        <button onclick="window.history.back()" 
                                style="background:#f97316;color:white;border:none;padding:5px 12px;border-radius:3px;cursor:pointer;font-size:12px;">
                            ← ย้อนกลับ
                        </button>
                    </span>
                </div>
            </div>
        <?php endif; ?>
        
                <!-- ปุ่มเพิ่ม Node แรกใหม่ -->
        <?php if (empty($nodes)): ?>
        <div class="add-first-node-section">
            <div class="add-first-node-container">
                <h3>เริ่มสร้าง Flowchart</h3>
                <p>เพิ่ม Node แรกของหมวดหมู่นี้</p>
                <button 
                    class="start-flowchart-btn add-child-btn" 
                    data-parent-id="0" 
                    data-parent-title="เริ่มต้น"
                    onclick="openAddChildModal(0, 'เริ่มต้น')">
                    ✨ เริ่มสร้าง Flowchart
                </button>
            </div>
        </div>
        <?php endif; ?>
        <?php
        // สีสำหรับแยกกลุ่ม parent-child
        $parent_colors = [
            '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', 
            '#06b6d4', '#84cc16', '#f97316', '#ec4899', '#6366f1'
        ];
        
        // ดึง root nodes (level 1)
        $root_nodes = get_child_nodes($nodes, 0);
        
        // สร้าง path จาก root ลงมาตาม selected_parent_id
        $display_levels = [];
        
        // Level 1: แสดงเสมอ
        $display_levels[1] = [
            'nodes' => $root_nodes,
            'parent_id' => 0,
            'selected_node_id' => null
        ];
        
        // เมื่อมีการเลือก node ให้แสดงทุก level ตั้งแต่ root ลงมาถึง level ปัจจุบัน
        if ($selected_parent_id != 0) {
            // หา path จาก root ลงมาถึง selected_parent_id
            $path = [];
            $temp_id = $selected_parent_id;
            
            while ($temp_id != 0) {
                foreach ($nodes as $n) {
                    if ($n['id'] == $temp_id) {
                        array_unshift($path, $n);
                        // จัดการ parent_id ที่อาจเป็น NULL
                        $temp_id = ($n['parent_id'] === null) ? 0 : (int)$n['parent_id'];
                        break;
                    }
                }
            }
            
            // สร้าง levels ตาม path (แสดงทุก level ในเส้นทาง)
            foreach ($path as $index => $node) {
                $level = $index + 2; // เริ่มจาก level 2
                
                // ตั้งค่า selected node ใน level ก่อนหน้า
                $display_levels[$level - 1]['selected_node_id'] = $node['id'];
                
                // เพิ่ม level ใหม่แสดงลูกของ node นี้
                $children = get_child_nodes($nodes, $node['id']);
                if (!empty($children)) {
                    $display_levels[$level] = [
                        'nodes' => $children,
                        'parent_id' => $node['id'],
                        'selected_node_id' => null
                    ];
                }
            }
        }
        ?>
        
        <!-- แสดงทุก levels แบบ vertical flow -->
        <?php foreach ($display_levels as $level_num => $level_data): ?>
            <?php if (!empty($level_data['nodes']) || $level_num == 1): ?>
                
                <!-- Level Header -->
                <div class="current-level-header">
                    <h3>
                        📋 Level <?= $level_num ?> 
                        <?php if ($level_num == 1): ?>
                            - Node หลักทั้งหมด (<?= count($level_data['nodes']) ?> รายการ)
                        <?php else: ?>
                            <?php
                            // หาชื่อ parent node
                            $parent_node = null;
                            foreach ($nodes as $n) {
                                if ($n['id'] == $level_data['parent_id']) {
                                    $parent_node = $n;
                                    break;
                                }
                            }
                            ?>
                            - ลูกของ "<?= htmlspecialchars($parent_node['title']) ?>" (<?= count($level_data['nodes']) ?> รายการ)
                        <?php endif; ?>
                    </h3>
                    
                    <!-- แสดงสถิติย่อย -->
                    <?php if (!empty($level_data['nodes'])): ?>
                        <div style="margin-top:8px;font-size:12px;color:#94a3b8;display:flex;justify-content:center;gap:15px;">
                            <?php
                            $has_children_count = 0;
                            $summary_count = 0;
                            foreach ($level_data['nodes'] as $node) {
                                if (!empty(get_child_nodes($nodes, $node['id']))) $has_children_count++;
                                if (get_conclusion($conclusions, $node['id']) !== null) $summary_count++;
                            }
                            ?>
                            <span>📂 มีลูก: <?= $has_children_count ?></span>
                            <span>📝 สรุป: <?= $summary_count ?></span>
                            <span>🎯 ใบสุดท้าย: <?= count($level_data['nodes']) - $has_children_count ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="flowchart-uniq-level <?= $level_num == count($display_levels) ? 'current-display' : '' ?>">
                    <div class="flowchart-uniq-level-number"><?= $level_num ?></div>
                    
                    <?php foreach ($level_data['nodes'] as $index => $node): ?>
                        <?php
                        $is_summary = get_conclusion($conclusions, $node['id']);
                        $node_color = $parent_colors[$index % count($parent_colors)];
                        $has_children = !empty(get_child_nodes($nodes, $node['id']));
                        $is_selected = ($node['id'] == $level_data['selected_node_id']);
                        ?>
                        
                        <div class="flowchart-uniq-node<?= $is_summary !== null ? ' flowchart-uniq-node-summary' : '' ?><?= !$has_children ? ' no-children' : '' ?><?= $is_selected ? ' selected-node' : '' ?>" 
                             style="border-color: <?= $node_color ?>; background: linear-gradient(135deg, <?= htmlspecialchars($node_color) ?>15, <?= htmlspecialchars($node_color) ?>08);">
                            
                            <div class="flowchart-uniq-node-title"><?= htmlspecialchars($node['title']) ?></div>
                            
                            <!-- แสดงรูปภาพถ้ามี -->
                            <?php if (isset($node['image_path']) && !empty($node['image_path']) && file_exists(__DIR__ . '/../' . $node['image_path'])): ?>
                                <div style="margin-top:8px; text-align:center;">
                                    <img src="../<?= htmlspecialchars($node['image_path']) ?>" 
                                         alt="<?= htmlspecialchars($node['title']) ?>"
                                         style="max-width:100%; max-height:80px; border-radius:4px; border:1px solid #475569;">
                                </div>
                            <?php endif; ?>
                            
                            <!-- แสดงรายละเอียดถ้ามี -->
                            <?php if (isset($node['description']) && !empty($node['description'])): ?>
                                <div style="margin-top:8px; color:#cbd5e1; font-size:12px; line-height:1.4; background:#1e293b; padding:6px; border-radius:3px; border:1px solid #475569;">
                                    <?= htmlspecialchars($node['description']) ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- แสดง parent reference ถ้าไม่ใช่ level 1 -->
                            <?php if ($level_num > 1): ?>
                                <?php
                                $parent_node = null;
                                $search_parent_id = ($node['parent_id'] === null) ? 0 : (int)$node['parent_id'];
                                if ($search_parent_id != 0) {
                                    foreach ($nodes as $n) {
                                        if ($n['id'] == $search_parent_id) {
                                            $parent_node = $n;
                                            break;
                                        }
                                    }
                                }
                                ?>
                                <?php if ($parent_node): ?>
                                    <div class="parent-reference" style="color: <?= $node_color ?>;">
                                        จาก: <?= htmlspecialchars($parent_node['title']) ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- แสดงสถานะ selected -->
                            <?php if ($is_selected): ?>
                                <div class="selected-badge">✓ เลือกแล้ว</div>
                            <?php endif; ?>

                            <?php if ($is_summary !== null): ?>
                                <div class="flowchart-uniq-summary-label">Node สรุป</div>
                                <div style="margin-top:8px; color:#bbf7d0; font-size:13px; font-weight:500; line-height:1.4; background:#1e293b; padding:8px; border-radius:3px; border:1px solid #22c55e;">
                                    <?= htmlspecialchars($is_summary) ?>
                                </div>
                            <?php endif; ?>

                            <div class="flowchart-uniq-node-actions<?= $is_summary !== null ? ' has-summary' : '' ?>">
                                <!-- ปุ่มเลือกดูลูก (สำหรับ Level 1 และมีลูก) -->
                                <?php if ($level_num == 1 && $has_children): ?>
                                    <a href="?title_id=<?= $title_id ?>&parent_id=<?= $node['id'] ?>" 
                                       class="flowchart-uniq-btn flowchart-uniq-btn-view">
                                        ดูลูก (<?= count(get_child_nodes($nodes, $node['id'])) ?>)
                                    </a>
                                <?php endif; ?>
                                
                                <!-- ปุ่มเลือก (ถ้ามีลูกและยังไม่ได้เลือก และไม่ใช่ Level 1) -->
                                <?php if ($level_num > 1 && $has_children && !$is_selected): ?>
                                    <a href="?title_id=<?= $title_id ?>&parent_id=<?= $node['id'] ?>" 
                                       class="flowchart-uniq-btn flowchart-uniq-btn-view">
                                        เลือกดูลูก (<?= count(get_child_nodes($nodes, $node['id'])) ?>)
                                    </a>
                                <?php endif; ?>
                                
                                <!-- ปุ่มยกเลิกการเลือก (ถ้าถูกเลือกอยู่) -->
                                <?php if ($is_selected): ?>
                                    <?php
                                    // สร้าง URL สำหรับยกเลิกการเลือก (กลับไป parent ของ node นี้)
                                    $cancel_parent_id = 0;
                                    if ($level_num > 1) {
                                        // หา parent ของ level นี้
                                        foreach ($nodes as $n) {
                                            if ($n['id'] == $level_data['parent_id']) {
                                                $cancel_parent_id = ($n['parent_id'] === null) ? 0 : (int)$n['parent_id'];
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                                    <a href="?title_id=<?= $title_id ?>&parent_id=<?= $cancel_parent_id ?>" 
                                       class="flowchart-uniq-btn flowchart-uniq-btn-cancel">
                                        ยกเลิกการเลือก
                                    </a>
                                <?php endif; ?>
                                
                                <button 
                                    class="flowchart-uniq-btn flowchart-uniq-btn-edit edit-node-btn" 
                                    data-node-id="<?= $node['id'] ?>" 
                                    data-node-title="<?= htmlspecialchars($node['title']) ?>"
                                    data-node-description="<?= isset($node['description']) ? htmlspecialchars($node['description']) : '' ?>">
                                    แก้ไข
                                </button>
                                <a href="delete_node.php?id=<?= $node['id'] ?>" class="flowchart-uniq-btn flowchart-uniq-btn-del" 
                                   onclick="return confirm('ลบ Node นี้?');">ลบ</a>
                                
                                <!-- ปุ่มเพิ่ม node ลูกแบบ modal -->
                                <button 
                                    class="flowchart-uniq-btn flowchart-uniq-btn-add add-child-btn" 
                                    data-parent-id="<?= $node['id'] ?>" 
                                    data-parent-title="<?= htmlspecialchars($node['title']) ?>">
                                    + เพิ่ม Node ลูก
                                </button>
                                
                                <!-- ปุ่มทำเป็น Node สรุป -->
                                <?php if ($is_summary === null && !$has_children): ?>
                                    <button 
                                        class="flowchart-uniq-btn flowchart-uniq-btn-summary add-summary-btn" 
                                        data-node-id="<?= $node['id'] ?>" 
                                        data-node-title="<?= htmlspecialchars($node['title']) ?>">
                                        ทำเป็น Node สรุป
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- ปุ่มเพิ่ม Node ใหม่ใน Level 1 -->
                    <?php if ($level_num == 1): ?>
                        <div class="flowchart-uniq-node add-node-button-container">
                            <button 
                                class="flowchart-uniq-btn flowchart-uniq-btn-add add-child-btn" 
                                data-parent-id="0" 
                                data-parent-title="Level 1"
                                style="width: 100%; height: 100%; min-height: 120px; font-size: 1.1rem;">
                                ➕ เพิ่ม Node ใหม่
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
                
            <?php endif; ?>
        <?php endforeach; ?>
        
        <!-- แสดงข้อความถ้าไม่มี nodes -->
        <?php if (empty($root_nodes)): ?>
            <div class="empty-state">
                <h3>ยังไม่มี Node</h3>
                <p>เริ่มสร้าง Node แรกได้เลย</p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state" style="margin-top:2rem;">
            <svg style="width:80px;height:80px;margin:0 auto 1.5rem auto;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <h3 style="font-size:1.5rem;font-weight:600;color:#f1f5f9;margin-bottom:1rem;">เลือกหมวดหมู่</h3>
            <p style="color:#94a3b8;font-size:1.1rem;">กรุณาเลือกหมวดหมู่ที่ต้องการแก้ไข Flowchart</p>
        </div>
    <?php endif; ?>
</div>

<!-- Edit Node Modal -->
<div id="editNodeModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">แก้ไข Node</h2>
            <button type="button" class="modal-close" onclick="closeModal('editNodeModal')">&times;</button>
        </div>
        <form id="editNodeForm" class="modal-form" enctype="multipart/form-data">
            <input type="hidden" name="edit_node" value="1">
            <input type="hidden" name="node_id" id="editNodeId">
            <input type="hidden" name="current_parent_id" value="<?= $selected_parent_id ?>">
            
            <div class="modal-form-group">
                <label class="modal-form-label" for="editNodeTitle">ชื่อ Node:</label>
                <input type="text" id="editNodeTitle" name="new_title" class="modal-form-input" required>
            </div>
            
            <div class="modal-form-group">
                <label class="modal-form-label" for="editNodeDescription">รายละเอียด:</label>
                <textarea id="editNodeDescription" name="new_description" class="modal-form-textarea" 
                         placeholder="ป้อนรายละเอียดของ Node (ไม่บังคับ)"></textarea>
            </div>
            
            <div class="modal-form-group">
                <label class="modal-form-label" for="editNodeImage">รูปภาพ:</label>
                <input type="file" id="editNodeImage" name="node_image" class="modal-form-input" 
                       accept="image/*">
                <div class="image-upload-info">รองรับ: JPG, PNG, GIF, WEBP (ขนาดไม่เกิน 5MB)</div>
                <div class="image-preview-container">
                    <img id="editImagePreview" class="image-preview" alt="ตัวอย่างรูปภาพ">
                    <button type="button" id="editRemoveImageBtn" class="remove-image-btn">ลบรูปภาพ</button>
                </div>
            </div>
            
            <div class="modal-form-actions">
                <button type="button" class="modal-btn modal-btn-secondary" onclick="closeModal('editNodeModal')">
                    ยกเลิก
                </button>
                <button type="submit" class="modal-btn modal-btn-primary">
                    บันทึก
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Summary Modal -->
<div id="addSummaryModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">เพิ่มข้อสรุปสำหรับ Node</h2>
            <button type="button" class="modal-close" onclick="closeModal('addSummaryModal')">&times;</button>
        </div>
        <form id="addSummaryForm" class="modal-form">
            <input type="hidden" name="add_summary" value="1">
            <input type="hidden" name="cause_id" id="summaryNodeId">
            <input type="hidden" name="current_parent_id" value="<?= $selected_parent_id ?>">
            
            <div class="modal-form-group">
                <label class="modal-form-label">Node:</label>
                <div style="background:#334155;padding:10px;border-radius:4px;color:#f1f5f9;font-weight:500;" id="summaryNodeTitle"></div>
            </div>
            
            <div class="modal-form-group">
                <label class="modal-form-label" for="summaryText">ข้อสรุป:</label>
                <textarea id="summaryText" name="summary" class="modal-form-textarea" 
                         placeholder="ป้อนข้อสรุปสำหรับ Node นี้..." required></textarea>
            </div>
            
            <div class="modal-form-actions">
                <button type="button" class="modal-btn modal-btn-secondary" onclick="closeModal('addSummaryModal')">
                    ยกเลิก
                </button>
                <button type="submit" class="modal-btn modal-btn-success">
                    เพิ่มข้อสรุป
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Child Node Modal -->
<div id="addChildNodeModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">เพิ่ม Node ลูก</h2>
            <button type="button" class="modal-close" onclick="closeModal('addChildNodeModal')">&times;</button>
        </div>
        <form id="addChildNodeForm" class="modal-form" enctype="multipart/form-data">
            <input type="hidden" name="add_child_node" value="1">
            <input type="hidden" name="parent_id" id="childParentId">
            <input type="hidden" name="current_parent_id" value="<?= $selected_parent_id ?>">
            
            <div class="modal-form-group">
                <label class="modal-form-label">Parent Node:</label>
                <div style="background:#334155;padding:10px;border-radius:4px;color:#f1f5f9;font-weight:500;" id="childParentTitle"></div>
            </div>
            
            <div class="modal-form-group">
                <label class="modal-form-label" for="childNodeTitle">ชื่อ Node ลูก:</label>
                <input type="text" id="childNodeTitle" name="child_title" class="modal-form-input" 
                       placeholder="ป้อนชื่อ Node ลูกใหม่..." required>
            </div>
            
            <div class="modal-form-group">
                <label class="modal-form-label" for="childNodeDescription">รายละเอียด:</label>
                <textarea id="childNodeDescription" name="child_description" class="modal-form-textarea" 
                         placeholder="ป้อนรายละเอียดของ Node ลูก (ไม่บังคับ)"></textarea>
            </div>
            
            <div class="modal-form-group">
                <label class="modal-form-label" for="childNodeImage">รูปภาพ:</label>
                <input type="file" id="childNodeImage" name="child_image" class="modal-form-input" 
                       accept="image/*">
                <div class="image-upload-info">รองรับ: JPG, PNG, GIF, WEBP (ขนาดไม่เกิน 5MB)</div>
                <div class="image-preview-container">
                    <img id="childImagePreview" class="image-preview" alt="ตัวอย่างรูปภาพ">
                    <button type="button" id="childRemoveImageBtn" class="remove-image-btn">ลบรูปภาพ</button>
                </div>
            </div>
            
            <div class="modal-form-actions">
                <button type="button" class="modal-btn modal-btn-secondary" onclick="closeModal('addChildNodeModal')">
                    ยกเลิก
                </button>
                <button type="submit" class="modal-btn modal-btn-success">
                    เพิ่ม Node ลูก
                </button>
            </div>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../inc/footer.php'; ?>