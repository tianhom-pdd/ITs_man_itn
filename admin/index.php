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
        <h1 class="adminit-dashboard-header">แดชบอร์ดผู้ดูแลระบบ IT Support</h1>
        <section class="adminit-card" style="margin-bottom:2.5rem;">
            <h2 class="adminit-dashboard-section-title">วิธีการใช้งานระบบสำหรับผู้ดูแล</h2>
            <ol class="adminit-dashboard-ol">
                <li><b>จัดการหมวดหมู่:</b> เพิ่ม/แก้ไข/ลบหมวดหมู่หลัก เช่น "Network", "SAP" โดยกำหนดสีและไอคอนให้แต่ละหมวดหมู่ เพื่อให้แยกประเภทปัญหาได้ง่าย</li>
                <li><b>จัดการหัวข้อปัญหา:</b> เพิ่ม/แก้ไข/ลบหัวข้อปัญหาในแต่ละหมวดหมู่ เช่น "เชื่อมต่อ Wi-Fi ไม่ได้" พร้อมคำอธิบายและรูปภาพประกอบ</li>
                <li><b>สร้างและแก้ไขโฟลว์ชาร์ต:</b> กำหนดขั้นตอนการแก้ไขปัญหาแบบ step-by-step สำหรับแต่ละหัวข้อปัญหา สามารถเพิ่ม "คำถาม", "วิธีแก้ไข", หรือ "ติดต่อเจ้าหน้าที่" ได้ตามลำดับ</li>
                <li><b>ดูสถิติ:</b> ตรวจสอบจำนวนหมวดหมู่, หัวข้อปัญหา, และวิธีแก้ไขที่มีในระบบ เพื่อวางแผนปรับปรุงข้อมูล</li>
            </ol>
            <div class="adminit-dashboard-note">
                <b>หมายเหตุ:</b> ทุกการเปลี่ยนแปลงจะมีผลกับระบบ IT Support ของผู้ใช้งานทันที กรุณาตรวจสอบข้อมูลก่อนบันทึกทุกครั้ง<br>
                หากต้องการความช่วยเหลือเพิ่มเติม กรุณาติดต่อทีม IT Support
            </div>
        </section>
        <section>
            <h2 class="adminit-dashboard-section-title">สถิติข้อมูลในระบบ</h2>
            <div style="display:flex; gap:2rem; flex-wrap:wrap;">
                <div class="adminit-card" style="flex:1; min-width:220px; display:flex; align-items:center; gap:1rem;">
                    <div style="width:56px; height:56px; border-radius:50%; background:rgba(234,179,8,0.12); display:flex; align-items:center; justify-content:center;">
                        <svg width="32" height="32" style="color:#eab308;"><use href="#icon-category"></use></svg>
                    </div>
                    <div>
                        <p style="color:var(--text-secondary); font-size: 0.95rem;">หมวดหมู่ทั้งหมด</p>
                        <p style="font-size: 1.7rem; font-weight: bold;"><?php echo $total_titles; ?></p>
                    </div>
                </div>
                <div class="adminit-card" style="flex:1; min-width:220px; display:flex; align-items:center; gap:1rem;">
                    <div style="width:56px; height:56px; border-radius:50%; background:rgba(220,38,38,0.12); display:flex; align-items:center; justify-content:center;">
                        <svg width="32" height="32" style="color:#dc2626;"><use href="#icon-problem"></use></svg>
                    </div>
                    <div>
                        <p style="color:var(--text-secondary); font-size: 0.95rem;">หัวข้อปัญหาทั้งหมด</p>
                        <p style="font-size: 1.7rem; font-weight: bold;"><?php echo $total_causes; ?></p>
                    </div>
                </div>
                <div class="adminit-card" style="flex:1; min-width:220px; display:flex; align-items:center; gap:1rem;">
                    <div style="width:56px; height:56px; border-radius:50%; background:rgba(34,197,94,0.12); display:flex; align-items:center; justify-content:center;">
                        <svg width="32" height="32" style="color:#22c55e;"><use href="#icon-flowchart"></use></svg>
                    </div>
                    <div>
                        <p style="color:var(--text-secondary); font-size: 0.95rem;">สรุป/วิธีแก้ไขปัญหา</p>
                        <p style="font-size: 1.7rem; font-weight: bold;"><?php echo $total_conclusions; ?></p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include_once __DIR__ . '/../inc/footer.php'; ?>
</body>
</html>