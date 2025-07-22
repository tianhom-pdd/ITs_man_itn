<?php
require_once __DIR__ . '/../inc/db.php';
include_once __DIR__ . '/../inc/admin_header.php';

// ดึงจำนวนหมวดหมู่
$res = $db->query("SELECT COUNT(*) AS total FROM title_it");
$row = $res->fetch_assoc();
$total_titles = $row['total'] ?? 0;

// ดึงจำนวนหัวข้อปัญหา
$res = $db->query("SELECT COUNT(*) AS total FROM cause_it");
$row = $res->fetch_assoc();
$total_causes = $row['total'] ?? 0;

// ดึงจำนวนสรุป/วิธีแก้ไข
$res = $db->query("SELECT COUNT(*) AS total FROM conclusion");
$row = $res->fetch_assoc();
$total_conclusions = $row['total'] ?? 0;
?>


<body>
    <main class="itsmainmn-page-container" style="max-width:900px;margin:auto;">
        <h1 style="margin-bottom:2rem;">แดชบอร์ดผู้ดูแลระบบ</h1>
        <div style="display:flex; gap:2rem; flex-wrap:wrap;">
            <div class="card" style="flex:1; min-width:220px; padding:1.5rem;">
                <div style="display:flex; align-items:center; gap:1rem;">
                    <div style="padding:0.75rem; border-radius:0.5rem; background-color: #eab30820; color: #eab308;">
                        <svg width="32" height="32"><use href="#icon-category"></use></svg>
                    </div>
                    <div>
                        <p style="color:var(--text-secondary); font-size: 0.95rem;">หมวดหมู่ทั้งหมด</p>
                        <p style="font-size: 1.7rem; font-weight: bold;"><?php echo $total_titles; ?></p>
                    </div>
                </div>
            </div>
            <div class="card" style="flex:1; min-width:220px; padding:1.5rem;">
                <div style="display:flex; align-items:center; gap:1rem;">
                    <div style="padding:0.75rem; border-radius:0.5rem; background-color: #dc262620; color: #dc2626;">
                        <svg width="32" height="32"><use href="#icon-problem"></use></svg>
                    </div>
                    <div>
                        <p style="color:var(--text-secondary); font-size: 0.95rem;">หัวข้อปัญหาทั้งหมด</p>
                        <p style="font-size: 1.7rem; font-weight: bold;"><?php echo $total_causes; ?></p>
                    </div>
                </div>
            </div>
            <div class="card" style="flex:1; min-width:220px; padding:1.5rem;">
                <div style="display:flex; align-items:center; gap:1rem;">
                    <div style="padding:0.75rem; border-radius:0.5rem; background-color: #22c55e20; color: #22c55e;">
                        <svg width="32" height="32"><use href="#icon-flowchart"></use></svg>
                    </div>
                    <div>
                        <p style="color:var(--text-secondary); font-size: 0.95rem;">สรุป/วิธีแก้ไขปัญหา</p>
                        <p style="font-size: 1.7rem; font-weight: bold;"><?php echo $total_conclusions; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include_once __DIR__ . '/../inc/footer.php'; ?>
</body>
</html>