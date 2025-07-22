<?php
require_once __DIR__ . '/../inc/db.php';
include_once __DIR__ . '/../inc/admin_header.php';

// ดึงหมวดหมู่ทั้งหมด
$titles = [];
$res = $db->query("SELECT id, title FROM title_it ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $titles[] = $row;

// รับค่า title_id จาก GET
$title_id = isset($_GET['title_id']) ? intval($_GET['title_id']) : 0;

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
        if ((int)$n['parent_id'] === (int)$parent_id) $children[] = $n;
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_node_inline'])) {
    $parent_id = intval($_POST['parent_id']);
    $title = trim($_POST['title']);
    if ($title && $title_id) {
        $stmt = $db->prepare("INSERT INTO cause_it (title, title_id, parent_id) VALUES (?, ?, ?)");
        $stmt->bind_param('sii', $title, $title_id, $parent_id);
        $stmt->execute();
        header("Location: editor.php?title_id=$title_id");
        exit;
    }
    $add_node_error = 'กรุณากรอกชื่อ node';
    $add_node_parent = $parent_id;
}
?>

<style>
/* สี Theme หลัก */
:root {
    --primary-color: #3b82f6;
    --primary-hover: #2563eb;
    --secondary-color: #6366f1;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --light-bg: #f8fafc;
    --white: #ffffff;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Background สำหรับทั้งหน้า */
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

/* กรอบหลัก */
.flowchart-uniq-container {
    max-width: 1200px;
    margin: 1.5rem auto;
    background: var(--white);
    border-radius: 20px;
    box-shadow: var(--shadow-xl);
    padding: 2.5rem;
    min-height: 600px;
    border: 1px solid var(--gray-200);
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

.flowchart-uniq-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--success-color));
}

/* Header Section */
.flowchart-uniq-select-title {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 3rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, var(--gray-50), var(--white));
    border-radius: 16px;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.flowchart-uniq-select-title label {
    font-weight: 600;
    color: var(--gray-700);
    margin-right: 1rem;
    font-size: 1.1rem;
}

.flowchart-uniq-select-title select {
    padding: 0.75rem 1.25rem;
    font-size: 1rem;
    border-radius: 12px;
    border: 2px solid var(--gray-300);
    background: var(--white);
    color: var(--gray-800);
    min-width: 250px;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: var(--shadow-sm);
}

.flowchart-uniq-select-title select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    transform: translateY(-1px);
}

.flowchart-uniq-select-title select:hover {
    border-color: var(--primary-hover);
}

/* Title */
.flowchart-uniq-title {
    text-align: center;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 3rem;
    color: var(--gray-800);
    letter-spacing: -0.025em;
    position: relative;
}

.flowchart-uniq-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    border-radius: 2px;
}

/* Level Container */
.flowchart-uniq-level {
    display: flex;
    gap: 2rem;
    overflow-x: auto;
    justify-content: center;
    align-items: stretch;
    padding: 2rem 1rem 2rem 5rem;
    margin-bottom: 3rem;
    border-radius: 20px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(99, 102, 241, 0.05));
    position: relative;
    box-shadow: var(--shadow-md);
    min-height: 160px;
    border: 1px solid rgba(59, 130, 246, 0.1);
}

.flowchart-uniq-level::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    border-radius: 20px 20px 0 0;
}

/* Level Number Label */
.flowchart-uniq-level-number {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.125rem;
    box-shadow: var(--shadow-lg);
    border: 3px solid white;
    z-index: 10;
}

.flowchart-uniq-level:nth-child(odd) {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(59, 130, 246, 0.05));
}

.flowchart-uniq-level:nth-child(even) {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.05), rgba(236, 72, 153, 0.05));
}

/* Node Styling */
.flowchart-uniq-node {
    background: var(--white);
    border: 2px solid var(--gray-200);
    border-radius: 16px;
    min-width: 220px;
    max-width: 280px;
    min-height: 140px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    box-shadow: var(--shadow-lg);
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    padding: 1.5rem;
    cursor: pointer;
}

.flowchart-uniq-node::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.flowchart-uniq-node:hover {
    border-color: var(--primary-color);
    transform: translateY(-8px) scale(1.02);
    box-shadow: var(--shadow-xl);
}

.flowchart-uniq-node:hover::before {
    transform: scaleX(1);
}

.flowchart-uniq-node-summary {
    border-color: var(--success-color) !important;
    background: linear-gradient(135deg, #ecfdf5, #f0fdf4) !important;
}

.flowchart-uniq-node-summary::before {
    background: linear-gradient(90deg, var(--success-color), #059669) !important;
}

/* Node Title */
.flowchart-uniq-node-title {
    font-weight: 600;
    font-size: 1.125rem;
    text-align: center;
    color: var(--gray-800);
    line-height: 1.4;
    margin-bottom: 1rem;
    word-break: break-word;
}

/* Node Actions */
.flowchart-uniq-node-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
    width: 100%;
    margin-top: 1rem;
}

.flowchart-uniq-node-actions.has-summary {
    grid-template-columns: 1fr;
}

/* Buttons */
.flowchart-uniq-btn {
    background: var(--gray-100);
    color: var(--gray-700);
    border: none;
    border-radius: 10px;
    padding: 0.625rem 1rem;
    cursor: pointer;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 2px solid transparent;
    min-height: 38px;
    position: relative;
    overflow: hidden;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.flowchart-uniq-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
}

.flowchart-uniq-btn:hover::before {
    left: 100%;
}

.flowchart-uniq-btn:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: var(--shadow-lg);
}

.flowchart-uniq-btn-edit {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: white;
    border-color: #f59e0b;
    position: relative;
}

.flowchart-uniq-btn-edit:hover {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
    border-color: #d97706;
}

.flowchart-uniq-btn-del {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border-color: #dc2626;
}

.flowchart-uniq-btn-del:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    box-shadow: 0 10px 30px rgba(239, 68, 68, 0.4);
    border-color: #b91c1c;
}

.flowchart-uniq-btn-add {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
    color: var(--white);
    border-color: var(--primary-color);
    grid-column: 1 / -1;
}

.flowchart-uniq-btn-add:hover {
    background: linear-gradient(135deg, var(--primary-hover), #1d4ed8);
    box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
    border-color: #1d4ed8;
}

.flowchart-uniq-btn-summary {
    background: linear-gradient(135deg, var(--success-color), #059669);
    color: var(--white);
    border-color: var(--success-color);
    grid-column: 1 / -1;
}

.flowchart-uniq-btn-summary:hover {
    background: linear-gradient(135deg, #059669, #047857);
    box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
    border-color: #047857;
}

/* Summary Label */
.flowchart-uniq-summary-label {
    color: var(--success-color);
    font-weight: 700;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
    padding: 0.25rem 0.75rem;
    background: rgba(16, 185, 129, 0.1);
    border-radius: 20px;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

/* Form Styling */
.flowchart-uniq-add-form {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: 1rem;
    padding: 1.25rem;
    background: linear-gradient(135deg, var(--white), var(--gray-50));
    border-radius: 12px;
    border: 2px solid var(--primary-color);
    box-shadow: var(--shadow-lg);
}

.flowchart-uniq-add-form input[type="text"] {
    padding: 0.875rem 1.125rem;
    border-radius: 10px;
    border: 2px solid var(--gray-300);
    font-size: 0.9rem;
    background: var(--white);
    transition: all 0.3s ease;
    min-width: 200px;
    font-weight: 500;
}

.flowchart-uniq-add-form input[type="text"]:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    transform: translateY(-2px);
}

.flowchart-uniq-add-form button {
    padding: 0.875rem 1.5rem;
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
    color: var(--white);
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    position: relative;
    overflow: hidden;
}

.flowchart-uniq-add-form button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
}

.flowchart-uniq-add-form button:hover::before {
    left: 100%;
}

.flowchart-uniq-add-form button:hover {
    background: linear-gradient(135deg, var(--primary-hover), #1d4ed8);
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
}

/* Add Root Node Button */
.add-root-node-btn {
    display: flex;
    justify-content: center;
    margin: 2rem 0;
}

.add-root-node-btn button {
    background: linear-gradient(135deg, var(--success-color), #059669);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-lg);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    position: relative;
    overflow: hidden;
}

.add-root-node-btn button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
}

.add-root-node-btn button:hover::before {
    left: 100%;
}

.add-root-node-btn button:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
}

/* Error Message */
.flowchart-uniq-error {
    color: var(--danger-color);
    font-size: 0.875rem;
    font-weight: 500;
    margin-top: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 8px;
}

/* Empty State */
.empty-state {
    text-align: center;
    color: var(--gray-500);
    font-size: 1.125rem;
    font-weight: 500;
    padding: 3rem;
    background: var(--gray-50);
    border-radius: 16px;
    border: 2px dashed var(--gray-300);
}

/* Responsive Design */
@media (max-width: 768px) {
    .flowchart-uniq-container {
        margin: 1rem;
        padding: 1.5rem;
    }
    
    .flowchart-uniq-level {
        gap: 1rem;
        padding: 1rem;
    }
    
    .flowchart-uniq-node {
        min-width: 180px;
        max-width: 220px;
    }
    
    .flowchart-uniq-select-title {
        flex-direction: column;
        gap: 1rem;
    }
    
    .flowchart-uniq-select-title select {
        min-width: 200px;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.flowchart-uniq-container {
    animation: fadeInUp 0.6s ease-out;
}

.flowchart-uniq-node {
    animation: fadeInUp 0.6s ease-out;
    animation-fill-mode: both;
}

.flowchart-uniq-node:nth-child(1) { animation-delay: 0.1s; }
.flowchart-uniq-node:nth-child(2) { animation-delay: 0.2s; }
.flowchart-uniq-node:nth-child(3) { animation-delay: 0.3s; }
.flowchart-uniq-node:nth-child(4) { animation-delay: 0.4s; }
.flowchart-uniq-node:nth-child(5) { animation-delay: 0.5s; }
</style>

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
        <div class="flowchart-uniq-title">
            แก้ไข Flowchart: <span style="color:#38bdf8">
                <?= htmlspecialchars($titles[array_search($title_id, array_column($titles, 'id'))]['title']) ?>
            </span>
        </div>
        <?php
        // ฟังก์ชันแสดง node และลูก node แบบ recursive (inline add)
        function render_nodes($nodes, $conclusions, $parent_id = 0, $level = 1, $add_node_parent = null, $add_node_error = null) {
            $children = get_child_nodes($nodes, $parent_id);
            if (!$children && $level > 1) return;

            if ($children) {
                echo '<div class="flowchart-uniq-level" style="z-index:'.(100-$level).';">';
                echo '<div class="flowchart-uniq-level-number">'.$level.'</div>';
                foreach ($children as $node) {
                    $is_summary = get_conclusion($conclusions, $node['id']);
                    echo '<div class="flowchart-uniq-node'.($is_summary ? ' flowchart-uniq-node-summary' : '').'">';
                        echo '<div class="flowchart-uniq-node-title">'.htmlspecialchars($node['title']).'</div>';

                        if ($is_summary) {
                            echo '<div class="flowchart-uniq-summary-label">Node สรุป</div>';
                            echo '<div style="margin-top:0.5rem; color:#22c55e; font-size:0.98rem;">'.htmlspecialchars($is_summary).'</div>';
                        }

                        echo '<div class="flowchart-uniq-node-actions'.($is_summary ? ' has-summary' : '').'">';
                            echo '<a href="edit_node.php?id='.$node['id'].'" class="flowchart-uniq-btn flowchart-uniq-btn-edit">แก้ไข</a>';
                            echo '<a href="delete_node.php?id='.$node['id'].'" class="flowchart-uniq-btn flowchart-uniq-btn-del" onclick="return confirm(\'ลบ Node นี้?\');">ลบ</a>';
                            
                            // ปุ่มเพิ่ม node ลูกแบบ inline
                            if ($add_node_parent == $node['id']) {
                                echo '<form method="post" class="flowchart-uniq-add-form">';
                                echo '<input type="hidden" name="parent_id" value="'.$node['id'].'">';
                                echo '<input type="text" name="title" placeholder="ป้อนชื่อ Node ลูก..." required>';
                                echo '<button type="submit" name="add_node_inline">+ เพิ่ม Node</button>';
                                echo '</form>';
                                if ($add_node_error) echo '<div class="flowchart-uniq-error">'.$add_node_error.'</div>';
                            } else {
                                echo '<form method="post" style="display:contents;">';
                                echo '<input type="hidden" name="parent_id" value="'.$node['id'].'">';
                                echo '<button type="submit" name="add_node_inline" value="1" class="flowchart-uniq-btn flowchart-uniq-btn-add">+ เพิ่ม Node ลูก</button>';
                                echo '</form>';
                            }
                            
                            if (!$is_summary && !get_child_nodes($nodes, $node['id'])) {
                                echo '<a href="add_conclusion.php?cause_id='.$node['id'].'" class="flowchart-uniq-btn flowchart-uniq-btn-summary">ทำเป็น Node สรุป</a>';
                            }
                        echo '</div>';

                    echo '</div>'; // .flowchart-uniq-node
                }
                echo '</div>'; // .flowchart-uniq-level
                
                // recursive ลูก node แยกออกมาเป็นระดับใหม่
                foreach ($children as $node) {
                    render_nodes($nodes, $conclusions, $node['id'], $level+1, $add_node_parent, $add_node_error);
                }
            } else if ($level == 1) {
                // ถ้า root ไม่มี node เลย แสดง empty state และปุ่มเพิ่ม
                echo '<div class="flowchart-uniq-level">';
                echo '<div class="flowchart-uniq-level-number">1</div>';
                echo '<div class="empty-state">';
                echo '<svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:64px;height:64px;margin:0 auto 1rem auto;color:var(--gray-400);">';
                echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>';
                echo '</svg>';
                echo '<h3 style="font-size:1.25rem;font-weight:600;color:var(--gray-600);margin-bottom:0.5rem;">ยังไม่มี Node เริ่มต้น</h3>';
                echo '<p style="color:var(--gray-500);margin-bottom:1.5rem;">เริ่มสร้าง Flowchart โดยการเพิ่ม Node แรก</p>';
                
                // ปุ่มเพิ่ม node แรก
                echo '<div class="add-root-node-btn">';
                echo '<form method="post">';
                echo '<input type="hidden" name="parent_id" value="0">';
                if (isset($add_node_parent) && $add_node_parent == 0) {
                    echo '<div class="flowchart-uniq-add-form" style="margin-top:0;">';
                    echo '<input type="text" name="title" placeholder="ป้อนชื่อ Node แรก..." required>';
                    echo '<button type="submit" name="add_node_inline">+ สร้าง Node แรก</button>';
                    echo '</div>';
                    if ($add_node_error) echo '<div class="flowchart-uniq-error">'.$add_node_error.'</div>';
                } else {
                    echo '<button type="submit" name="add_node_inline" value="1">+ เพิ่ม Node แรก</button>';
                }
                echo '</form>';
                echo '</div>';
                
                echo '</div>';
                echo '</div>';
            }
        }

        render_nodes($nodes, $conclusions, 0, 1, $add_node_parent ?? null, $add_node_error ?? null);
        ?>
    <?php else: ?>
        <div class="empty-state" style="margin-top:2rem;">
            <svg style="width:80px;height:80px;margin:0 auto 1.5rem auto;color:var(--gray-400);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <h3 style="font-size:1.5rem;font-weight:600;color:var(--gray-600);margin-bottom:1rem;">เลือกหมวดหมู่</h3>
            <p style="color:var(--gray-500);font-size:1.1rem;">กรุณาเลือกหมวดหมู่ที่ต้องการแก้ไข Flowchart</p>
        </div>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../inc/footer.php'; ?>