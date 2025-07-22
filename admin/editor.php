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
/* กรอบกลาง */
.flowchart-uniq-container {
    max-width: 1100px;
    margin: 2rem auto 2rem auto;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 12px #0001;
    padding: 2rem 1.5rem 2rem 1.5rem;
    min-height: 400px;
    border: 1px solid #e5e7eb;
}
.flowchart-uniq-select-title {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
}
.flowchart-uniq-select-title select {
    padding: 0.45rem 1.1rem;
    font-size: 1rem;
    border-radius: 7px;
    border: 1px solid #d1d5db;
    background: #f9fafb;
    color: #222;
    margin-left: 0.7rem;
}
.flowchart-uniq-title {
    text-align: center;
    font-size: 1.35rem;
    font-weight: 600;
    margin-bottom: 2.2rem;
    color: var(--text-primary, #222);
    letter-spacing: 0.5px;
}
.flowchart-uniq-level {
    display: flex;
    gap: 1.2rem;
    overflow-x: auto;
    justify-content: center;
    align-items: center;
    padding: 1.2rem 0 1.5rem 0;
    margin-bottom: 2.5rem;
    border-radius: 12px;
    border-bottom: 2px solid #bae6fd;
    background: var(--level-bg, #f1f5f9);
    position: relative;
    box-shadow: 0 2px 8px #0001;
    min-height: 120px;
}
.flowchart-uniq-level-label {
    position: absolute;
    left: -75px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.08rem;
    color: #38bdf8;
    font-weight: bold;
    opacity: 0.85;
    z-index: 2;
    pointer-events: none;
    background: #fff;
    border-radius: 8px;
    padding: 0.4rem 1.1rem;
    box-shadow: 0 1px 4px #0001;
    border: 1.5px solid #bae6fd;
    letter-spacing: 1px;
    min-width: 60px;
    text-align: center;
}
.flowchart-uniq-level.level-odd { background: #f1f5f9; }
.flowchart-uniq-level.level-even { background: #e0f2fe; }
.flowchart-uniq-node {
    background: #f8fafc;
    border: 2px solid #e0e7ef;
    border-radius: 10px;
    min-width: 180px;
    max-width: 260px;
    height: 120px; /* บังคับให้ node ทุกตัวสูงเท่ากัน */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    box-shadow: 0 2px 8px #0001;
    position: relative;
    transition: border 0.2s, background 0.2s;
    overflow: hidden; /* ถ้าเนื้อหาเกินจะถูกตัด */
}
.flowchart-uniq-node:hover {
    border-color: #38bdf8;
    background: #e0f2fe;
}
.flowchart-uniq-node-summary {
    border-color: #22c55e !important;
    background: #e7fbe9 !important;
}
.flowchart-uniq-node-title {
    font-weight: 600;
    font-size: 1.08rem;
    margin-bottom: 0.7rem;
    text-align: center;
    word-break: break-all;
    color: var(--text-primary, #222);
}
.flowchart-uniq-node-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.7rem;
    flex-wrap: wrap;
    justify-content: center;
}
.flowchart-uniq-btn {
    background: #f1f5f9;
    color: #222;
    border: none;
    border-radius: 5px;
    padding: 0.28rem 0.95rem;
    cursor: pointer;
    font-size: 0.97rem;
    transition: background 0.18s, color 0.18s;
    text-decoration: none;
    display: inline-block;
    border: 1px solid #e0e7ef;
}
.flowchart-uniq-btn-edit { background: #fde68a; color: #b45309; border-color: #fbbf24; }
.flowchart-uniq-btn-del { background: #fecaca; color: #b91c1c; border-color: #f87171; }
.flowchart-uniq-btn-add { background: #38bdf8; color: #fff; border-color: #38bdf8; }
.flowchart-uniq-btn-summary { background: #22c55e; color: #fff; border-color: #22c55e; }
.flowchart-uniq-btn:hover { filter: brightness(0.96); }
.flowchart-uniq-summary-label {
    color: #22c55e;
    font-weight: bold;
    margin-top: 0.7rem;
    font-size: 1rem;
}
.flowchart-uniq-add-form input[type="text"] {
    padding: 0.22rem 0.6rem;
    border-radius: 5px;
    border: 1px solid #cbd5e1;
    font-size: 1rem;
    margin-right: 0.4rem;
    margin-bottom: 0.2rem;
    background: #fff;
}
.flowchart-uniq-add-form button {
    padding: 0.22rem 0.9rem;
    border-radius: 5px;
    border: none;
    background: #38bdf8;
    color: #fff;
    font-size: 0.97rem;
    margin-left: 0.2rem;
}
.flowchart-uniq-add-form button:hover {
    background: #0ea5e9;
}
.flowchart-uniq-error {
    color: #dc2626;
    font-size: 0.93rem;
    margin-top: 0.3rem;
}
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
                foreach ($children as $node) {
                    $is_summary = get_conclusion($conclusions, $node['id']);
                    echo '<div class="flowchart-uniq-node'.($is_summary ? ' flowchart-uniq-node-summary' : '').'">';
                        echo '<div class="flowchart-uniq-node-title">'.htmlspecialchars($node['title']).'</div>';

                        if ($is_summary) {
                            echo '<div class="flowchart-uniq-summary-label">Node สรุป</div>';
                            echo '<div style="margin-top:0.5rem; color:#22c55e; font-size:0.98rem;">'.htmlspecialchars($is_summary).'</div>';
                        }

                        echo '<div class="flowchart-uniq-node-actions">';
                            echo '<a href="edit_node.php?id='.$node['id'].'" class="flowchart-uniq-btn flowchart-uniq-btn-edit">แก้ไข</a>';
                            echo '<a href="delete_node.php?id='.$node['id'].'" class="flowchart-uniq-btn flowchart-uniq-btn-del" onclick="return confirm(\'ลบ Node นี้?\');">ลบ</a>';
                            // ปุ่มเพิ่ม node ลูกแบบ inline
                            if ($add_node_parent == $node['id']) {
                                echo '<form method="post" class="flowchart-uniq-add-form" style="display:inline-block;margin-left:0.5rem;">';
                                echo '<input type="hidden" name="parent_id" value="'.$node['id'].'">';
                                echo '<input type="text" name="title" placeholder="ชื่อ node ลูก">';
                                echo '<button type="submit" name="add_node_inline">บันทึก</button>';
                                echo '</form>';
                                if ($add_node_error) echo '<div class="flowchart-uniq-error">'.$add_node_error.'</div>';
                            } else {
                                echo '<form method="post" style="display:inline;">';
                                echo '<input type="hidden" name="parent_id" value="'.$node['id'].'">';
                                echo '<button type="submit" name="add_node_inline" value="1" class="flowchart-uniq-btn flowchart-uniq-btn-add">+ เพิ่ม node ลูก</button>';
                                echo '</form>';
                            }
                            if (!$is_summary && !get_child_nodes($nodes, $node['id'])) {
                                echo '<a href="add_conclusion.php?cause_id='.$node['id'].'" class="flowchart-uniq-btn flowchart-uniq-btn-summary">ทำเป็น node สรุป</a>';
                            }
                        echo '</div>';

                        // recursive ลูก node
                        render_nodes($nodes, $conclusions, $node['id'], $level+1, $add_node_parent, $add_node_error);

                    echo '</div>'; // .flowchart-uniq-node
                }
                echo '</div>'; // .flowchart-uniq-level
            } else if ($level == 1) {
                // ถ้า root ไม่มี node เลย ไม่ต้องแสดงปุ่มเพิ่ม node
                echo '<div class="flowchart-uniq-level" style="z-index:'.(100-$level).';">';
                echo '<div style="color:#bbb; margin:0 auto;">ยังไม่มี node เริ่มต้น</div>';
                echo '</div>';
            }
        }

        render_nodes($nodes, $conclusions, 0, 1, $add_node_parent ?? null, $add_node_error ?? null);
        ?>
    <?php else: ?>
        <div style="text-align:center; color:#888;">กรุณาเลือกหมวดหมู่ก่อน</div>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../inc/footer.php'; ?>