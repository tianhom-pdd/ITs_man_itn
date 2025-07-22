<?php
require_once __DIR__ . '/../inc/db.php';

// ดึงหมวดหมู่
$titles = [];
$res = $db->query("SELECT id, title, color, icon AS iconName FROM title_it ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $titles[] = $row;

// ดึงหัวข้อปัญหา
$causes = [];
$res = $db->query("SELECT id, title, text, title_id, image, type, parent_id FROM cause_it ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $causes[] = $row;

// ดึง conclusion
$conclusions = [];
$res = $db->query("SELECT id, cause_id, summary FROM conclusion ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $conclusions[] = $row;

// ส่งข้อมูลไป JS
$dbData = [
    'titles' => $titles,
    'causes' => $causes,
    'conclusions' => $conclusions
];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แผงควบคุมสำหรับผู้ดูแล - IT Support</title>
    
</head>
<body>

    <!-- SVG Icon Definitions -->
    <svg width="0" height="0" style="display:none;">
        <defs>
            <symbol id="icon-dashboard" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></symbol>
            <symbol id="icon-categories" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></symbol>
            <symbol id="icon-problems" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></symbol>
            <symbol id="icon-flowchart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14l-4-4 4-4"></path><path d="M4 4v1h12a2 2 0 0 1 2 2v2H4"></path><path d="M4 12v2a2 2 0 0 0 2 2h12v1"></path></symbol>
            <symbol id="icon-wrench" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></symbol>
            <symbol id="icon-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></symbol>
            <symbol id="icon-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></symbol>
            <symbol id="icon-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></symbol>
            <symbol id="icon-save" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></symbol>
            <symbol id="icon-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></symbol>
            <symbol id="icon-brain" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v0A2.5 2.5 0 0 1 9.5 7h-3A2.5 2.5 0 0 1 4 4.5v0A2.5 2.5 0 0 1 6.5 2h3m10.5 0A2.5 2.5 0 0 1 18 4.5v0A2.5 2.5 0 0 1 15.5 7h-3A2.5 2.5 0 0 1 10 4.5v0A2.5 2.5 0 0 1 12.5 2h3M9 11v-1M15 11v-1M6.5 16.5A2.5 2.5 0 0 1 4 19v0a2.5 2.5 0 0 1 2.5-2.5h3A2.5 2.5 0 0 1 12 19v0a2.5 2.5 0 0 1-2.5 2.5h-3M17.5 16.5A2.5 2.5 0 0 1 15 19v0a2.5 2.5 0 0 1 2.5-2.5h3A2.5 2.5 0 0 1 23 19v0a2.5 2.5 0 0 1-2.5 2.5h-3M9 18v1M15 18v1M12 7.5a2.5 2.5 0 0 1-2.5 2.5v0A2.5 2.5 0 0 1 7 7.5h5a2.5 2.5 0 0 1 2.5 2.5v0A2.5 2.5 0 0 1 12 12.5v0a2.5 2.5 0 0 1-2.5-2.5M12 12.5a2.5 2.5 0 0 0 2.5 2.5v0a2.5 2.5 0 0 0 2.5-2.5h-5a2.5 2.5 0 0 0-2.5 2.5v0A2.5 2.5 0 0 0 12 15.5v0a2.5 2.5 0 0 0 2.5-2.5"></path></symbol>
            <symbol id="icon-phone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></symbol>
            
            <!-- Lucide Icons as SVG Symbols -->
            <symbol id="icon-globe" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></symbol>
            <symbol id="icon-database" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></symbol>
            <symbol id="icon-cog" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20v-4"></path><path d="M12 8V4"></path><path d="M4.93 4.93l2.83 2.83"></path><path d="M16.24 16.24l2.83 2.83"></path><path d="M2 12h4"></path><path d="M18 12h4"></path><path d="M4.93 19.07l2.83-2.83"></path><path d="M16.24 7.76l2.83-2.83"></path><circle cx="12" cy="12" r="3"></circle></symbol>
            <symbol id="icon-printer" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></symbol>
            <symbol id="icon-code" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></symbol>
            <symbol id="icon-mouse" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="7"></rect><path d="M12 6v4"></path></symbol>
            <symbol id="icon-mail" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><polyline points="22,6 12,13 2,6"></polyline></symbol>
            <symbol id="icon-power" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></symbol>
            <symbol id="icon-monitor" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></symbol>
            <symbol id="icon-server" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></symbol>
            
            <!-- NEW ICONS -->
            <symbol id="icon-wifi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"></path><path d="M1.42 9a16 16 0 0 1 21.16 0"></path><path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path><line x1="12" y1="20" x2="12.01" y2="20"></line></symbol>
            <symbol id="icon-laptop" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 16V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v9m16 0H4m16 0 1.28 2.55A1 1 0 0 1 20.28 20H3.72a1 1 0 0 1-.98-1.45L4 16"></path></symbol>
            <symbol id="icon-smartphone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></symbol>
            <symbol id="icon-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></symbol>
            <symbol id="icon-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></symbol>
            <symbol id="icon-key" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></symbol>
            <symbol id="icon-hard-drive" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="12" x2="2" y2="12"></line><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path><line x1="6" y1="16" x2="6.01" y2="16"></line><line x1="10" y1="16" x2="10.01" y2="16"></line></symbol>
            <symbol id="icon-cloud" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path></symbol>
            <symbol id="icon-camera" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></symbol>
            <symbol id="icon-share" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></symbol>
        </defs>
    </svg>

    <div class="adminit-admin-container">
        <!-- Sidebar -->
        <aside class="adminit-sidebar">
            <div class="adminit-sidebar-header">
                <svg class="adminit-icon" width="28" height="28"><use href="#icon-wrench"></use></svg>
                <h2>IT Support Admin</h2>
            </div>
            <nav id="main-nav">
                <a href="#dashboard" class="nav-link active">
                    <svg width="20" height="20"><use href="#icon-dashboard"></use></svg>
                    <span>แดชบอร์ด</span>
                </a>
                <a href="#categories" class="nav-link">
                    <svg width="20" height="20"><use href="#icon-categories"></use></svg>
                    <span>จัดการหมวดหมู่</span>
                </a>
                <a href="#problems" class="nav-link">
                    <svg width="20" height="20"><use href="#icon-problems"></use></svg>
                    <span>จัดการปัญหา</span>
                </a>
                <a href="#editor" class="nav-link">
                    <svg width="20" height="20"><use href="#icon-flowchart"></use></svg>
                    <span>เครื่องมือแก้ไขโฟลว์ชาร์ต</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="adminit-main-content">
            <!-- Dashboard Page -->
            <div id="page-dashboard" class="adminit-page adminit-active">
                <h1 class="mb-6">แดชบอร์ด</h1>
                <div id="dashboard-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <!-- Stat cards will be injected here -->
                </div>
                <div class="adminit-card" style="margin-top: 2rem;">
                    <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">ยินดีต้อนรับสู่แผงควบคุมผู้ดูแล</h2>
                    <p style="color: var(--text-light-secondary);">
                        นี่คือศูนย์กลางสำหรับจัดการข้อมูลโฟลว์ชาร์ตของฝ่าย IT Support ใช้เมนูด้านซ้ายเพื่อ:
                    </p>
                    <ul style="list-style: disc; padding-left: 1.5rem; color: var(--text-light-secondary); margin-top: 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
                        <li><span style="font-weight: 600; color: var(--text-light-primary);">จัดการหมวดหมู่:</span> เพิ่ม, แก้ไข, หรือลบหมวดหมู่หลักของปัญหา เช่น "Network" หรือ "SAP" พร้อมกำหนดสีและไอคอน</li>
                        <li><span style="font-weight: 600; color: var(--text-light-primary);">จัดการปัญหา:</span> กำหนดหัวข้อปัญหาที่เฉพาะเจาะจงภายใต้แต่ละหมวดหมู่ เช่น "เชื่อมต่อ Wi-Fi ไม่ได้"</li>
                        <li><span style="font-weight: 600; color: var(--text-light-primary);">เครื่องมือแก้ไขโฟลว์ชาร์ต:</span> สร้างขั้นตอนการแก้ไขปัญหาแบบ step-by-step สำหรับแต่ละหัวข้อปัญหา</li>
                    </ul>
                </div>
            </div>

            <!-- Categories Page -->
            <div id="page-categories" class="adminit-page">
                <div class="adminit-page-header">
                    <h1>จัดการหมวดหมู่</h1>
                    <button id="btn-new-category" class="adminit-btn adminit-btn-primary">
                        <svg width="20" height="20"><use href="#icon-plus"></use></svg>
                        เพิ่มหมวดหมู่ใหม่
                    </button>
                </div>
                <div class="adminit-table-container">
                    <table>
                        <thead>
                            <tr><th>สี</th><th>ไอคอน</th><th>ชื่อหมวดหมู่</th><th style="text-align: right;">การดำเนินการ</th></tr>
                        </thead>
                        <tbody id="categories-table-body">
                            <!-- Category rows will be injected here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Problems Page -->
            <div id="page-problems" class="adminit-page">
                <div class="adminit-page-header">
                    <h1>จัดการปัญหา</h1>
                    <button id="btn-new-problem" class="adminit-btn adminit-btn-primary">
                        <svg width="20" height="20"><use href="#icon-plus"></use></svg>
                        เพิ่มปัญหาใหม่
                    </button>
                </div>
                <div class="adminit-table-container">
                    <table>
                        <thead>
                            <tr><th>หัวข้อปัญหา</th><th>คำอธิบาย</th><th>หมวดหมู่</th><th style="text-align: right;">การดำเนินการ</th></tr>
                        </thead>
                        <tbody id="problems-table-body">
                            <!-- Problem rows will be injected here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Flowchart Editor Page -->
            <div id="page-editor" class="adminit-page">
                <h1>เครื่องมือแก้ไขโฟลว์ชาร์ต</h1>
                <div class="adminit-form-group" style="margin-bottom: 1.5rem; max-width: 400px;">
                    <label for="flowchart-problem-select">เลือกปัญหาที่ต้องการแก้ไขโฟลว์ชาร์ต</label>
                    <select id="flowchart-problem-select">
                        <!-- Options will be injected here -->
                    </select>
                </div>
                <div id="flowchart-editor-container" class="adminit-flowchart-container">
                    <!-- Flowchart will be rendered here -->
                </div>
            </div>
        </main>
    </div>

    <!-- Category Modal -->
    <div id="category-modal" class="adminit-modal-overlay">
        <div class="adminit-modal-content">
            <div class="adminit-modal-header">
                <h3 id="category-modal-title">หัวข้อ Modal</h3>
                <button class="btn-close-modal">
                    <svg width="24" height="24"><use href="#icon-x"></use></svg>
                </button>
            </div>
            <div class="adminit-modal-body">
                <input type="hidden" id="category-id-input">
                <div class="adminit-form-group">
                    <label for="category-title-input">ชื่อหมวดหมู่</label>
                    <input type="text" id="category-title-input">
                </div>
                <div class="adminit-form-group">
                    <label>เลือกสี</label>
                    <div id="category-color-picker" class="adminit-color-picker"></div>
                </div>
                <div class="adminit-form-group">
                    <label>เลือกไอคอน</label>
                    <div id="category-icon-picker" class="adminit-icon-picker"></div>
                </div>
            </div>
            <div class="adminit-modal-footer">
                <button id="btn-save-category" class="adminit-btn adminit-btn-success">
                    <svg width="20" height="20"><use href="#icon-save"></use></svg>
                    บันทึกการเปลี่ยนแปลง
                </button>
            </div>
        </div>
    </div>
    
    <!-- Problem Modal -->
    <div id="problem-modal" class="adminit-modal-overlay">
        <div class="adminit-modal-content">
            <div class="adminit-modal-header">
                <h3 id="problem-modal-title">หัวข้อ Modal</h3>
                <button class="btn-close-modal">
                    <svg width="24" height="24"><use href="#icon-x"></use></svg>
                </button>
            </div>
            <div class="adminit-modal-body">
                <input type="hidden" id="problem-id-input">
                <div class="adminit-form-group">
                    <label for="problem-title-input">หัวข้อปัญหา</label>
                    <input type="text" id="problem-title-input">
                </div>
                <div class="adminit-form-group">
                    <label for="problem-text-input">คำอธิบาย</label>
                    <textarea id="problem-text-input" rows="3"></textarea>
                </div>
                <div class="adminit-form-group">
                    <label for="problem-category-select">หมวดหมู่</label>
                    <select id="problem-category-select"></select>
                </div>
                <div class="adminit-form-group">
                    <label for="problem-image-input">URL รูปภาพ (ถ้ามี)</label>
                    <input type="text" id="problem-image-input">
                </div>
            </div>
            <div class="adminit-modal-footer">
                <button id="btn-save-problem" class="adminit-btn adminit-btn-success">
                    <svg width="20" height="20"><use href="#icon-save"></use></svg>
                    บันทึกการเปลี่ยนแปลง
                </button>
            </div>
        </div>
    </div>
    
    <!-- Flowchart Node Modal -->
    <div id="node-modal" class="adminit-modal-overlay">
        <div class="adminit-modal-content">
            <div class="adminit-modal-header">
                <h3 id="node-modal-title">หัวข้อ Modal</h3>
                <button class="btn-close-modal">
                    <svg width="24" height="24"><use href="#icon-x"></use></svg>
                </button>
            </div>
            <div class="adminit-modal-body">
                <input type="hidden" id="node-id-input">
                <input type="hidden" id="node-parent-id-input">
                <div class="adminit-form-group">
                    <label for="node-type-select">ประเภทขั้นตอน</label>
                    <select id="node-type-select">
                        <option value="question">คำถาม (เพื่อไปยังขั้นตอนถัดไป)</option>
                        <option value="solution">วิธีแก้ไข (ขั้นตอนสุดท้าย)</option>
                        <option value="contact">ติดต่อเจ้าหน้าที่</option>
                    </select>
                </div>
                <div class="adminit-form-group">
                    <label for="node-title-input">หัวข้อ</label>
                    <input type="text" id="node-title-input">
                </div>
                <div class="adminit-form-group">
                    <label for="node-text-input">ข้อความ / คำอธิบาย</label>
                    <textarea id="node-text-input" rows="3"></textarea>
                </div>
                <div class="adminit-form-group">
                    <label for="node-image-input">URL รูปภาพ (ถ้ามี)</label>
                    <input type="text" id="node-image-input">
                </div>
            </div>
            <div class="adminit-modal-footer">
                <button id="btn-save-node" class="adminit-btn adminit-btn-success">
                    <svg width="20" height="20"><use href="#icon-save"></use></svg>
                    บันทึกขั้นตอน
                </button>
            </div>
        </div>
    </div>


    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- DATA STORE จากฐานข้อมูลจริง ---
        let dbData = <?php echo json_encode($dbData, JSON_UNESCAPED_UNICODE); ?>;

        // --- DOM Elements ---
        const mainNav = document.getElementById('main-nav');
        const pages = document.querySelectorAll('.adminit-page');
        const modals = document.querySelectorAll('.adminit-modal-overlay');

        // --- Render Functions ---
        const renderDashboard = () => {
            const container = document.getElementById('dashboard-stats');
            container.innerHTML = `
                <div class="card">
                    <div style="display:flex; align-items:center; gap:1rem;">
                        <div style="padding:0.75rem; border-radius:0.5rem; background-color: #3b82f620; color: #3b82f6;">
                            <svg width="24" height="24"><use href="#icon-categories"></use></svg>
                        </div>
                        <div>
                            <p style="color:var(--text-light-secondary); font-size: 0.9rem;">หมวดหมู่ปัญหา</p>
                            <p style="font-size: 1.5rem; font-weight: bold;">${dbData.titles.length}</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div style="display:flex; align-items:center; gap:1rem;">
                        <div style="padding:0.75rem; border-radius:0.5rem; background-color: #eab30820; color: #eab308;">
                            <svg width="24" height="24"><use href="#icon-problems"></use></svg>
                        </div>
                        <div>
                            <p style="color:var(--text-light-secondary); font-size: 0.9rem;">หัวข้อปัญหาทั้งหมด</p>
                            <p style="font-size: 1.5rem; font-weight: bold;">${dbData.causes.length}</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div style="display:flex; align-items:center; gap:1rem;">
                        <div style="padding:0.75rem; border-radius:0.5rem; background-color: #22c55e20; color: #22c55e;">
                            <svg width="24" height="24"><use href="#icon-flowchart"></use></svg>
                        </div>
                        <div>
                            <p style="color:var(--text-light-secondary); font-size: 0.9rem;">โฟลว์การแก้ไขปัญหา</p>
                            <p style="font-size: 1.5rem; font-weight: bold;">${dbData.solutions.length}</p>
                        </div>
                    </div>
                </div>
            `;
        };

        const renderCategories = () => {
            const tableBody = document.getElementById('categories-table-body');
            tableBody.innerHTML = dbData.titles.map(cat => `
                <tr>
                    <td><div style="width: 24px; height: 24px; border-radius: 50%; background-color: ${cat.color};"></div></td>
                    <td><svg width="24" height="24"><use href="#icon-${cat.iconName}"></use></svg></td>
                    <td>${cat.title}</td>
                    <td class="adminit-action-buttons" style="text-align: right;">
                        <button class="adminit-btn-edit-category" data-id="${cat.id}"><svg width="18" height="18"><use href="#icon-edit"></use></svg></button>
                        <button class="adminit-btn-delete-category" data-id="${cat.id}"><svg width="18" height="18"><use href="#icon-trash"></use></svg></button>
                    </td>
                </tr>
            `).join('');
            
            // Re-attach event listeners
            document.querySelectorAll('.btn-edit-category').forEach(btn => btn.addEventListener('click', (e) => openCategoryModal(e.currentTarget.dataset.id)));
            document.querySelectorAll('.btn-delete-category').forEach(btn => btn.addEventListener('click', (e) => deleteCategory(e.currentTarget.dataset.id)));
        };
        
        const renderProblems = () => {
            const tableBody = document.getElementById('problems-table-body');
            tableBody.innerHTML = dbData.causes.map(cause => {
                const category = dbData.titles.find(t => t.id === cause.title_id);
                return `
                    <tr>
                        <td>${cause.title}</td>
                        <td>${cause.text}</td>
                        <td>
                            ${category ? `<span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; border-radius: 9999px; background-color: ${category.color}20; color: ${category.color};">${category.title}</span>` : 'N/A'}
                        </td>
                        <td class="adminit-action-buttons" style="text-align: right;">
                            <button class="btn-edit-problem" data-id="${cause.id}"><svg width="18" height="18"><use href="#icon-edit"></use></svg></button>
                            <button class="btn-delete-problem" data-id="${cause.id}"><svg width="18" height="18"><use href="#icon-trash"></use></svg></button>
                        </td>
                    </tr>
                `;
            }).join('');
            
            // Re-attach event listeners
            document.querySelectorAll('.btn-edit-problem').forEach(btn => btn.addEventListener('click', (e) => openProblemModal(e.currentTarget.dataset.id)));
            document.querySelectorAll('.btn-delete-problem').forEach(btn => btn.addEventListener('click', (e) => deleteProblem(e.currentTarget.dataset.id)));
        };
        
        const renderFlowchartEditor = () => {
            const select = document.getElementById('flowchart-problem-select');
            const currentVal = select.value;
            select.innerHTML = dbData.causes.map(c => `<option value="${c.id}">${c.title}</option>`).join('');
            select.value = currentVal && dbData.causes.some(c=> c.id == currentVal) ? currentVal : (dbData.causes[0]?.id || '');
            renderFlowchartTree();
        };

        const renderFlowchartTree = () => {
            const container = document.getElementById('flowchart-editor-container');
            const selectedCauseId = parseInt(document.getElementById('flowchart-problem-select').value);
            const rootSolution = dbData.solutions.find(s => s.cause_id === selectedCauseId);

            if (rootSolution) {
                container.innerHTML = generateNodeHTML(rootSolution, null);
            } else {
                container.innerHTML = `
                    <div style="text-align: center; color: var(--text-light-secondary); padding: 4rem 0;">
                        <p>ไม่พบโฟลว์ชาร์ตสำหรับปัญหานี้</p>
                        <button id="btn-new-root-node" class="btn btn-primary" style="margin-top: 1rem;">
                             <svg width="20" height="20"><use href="#icon-plus"></use></svg>
                             สร้างขั้นตอนแรก
                        </button>
                    </div>
                `;
                if(document.getElementById('btn-new-root-node')) {
                    document.getElementById('btn-new-root-node').addEventListener('click', () => openNodeModal(null, null));
                }
            }
            
            // Re-attach event listeners
            container.querySelectorAll('.btn-edit-node').forEach(btn => btn.addEventListener('click', (e) => openNodeModal(e.currentTarget.dataset.id, e.currentTarget.dataset.parentId)));
            container.querySelectorAll('.btn-delete-node').forEach(btn => btn.addEventListener('click', (e) => deleteNode(e.currentTarget.dataset.id, e.currentTarget.dataset.parentId)));
            container.querySelectorAll('.btn-add-child-node').forEach(btn => btn.addEventListener('click', (e) => openNodeModal(null, e.currentTarget.dataset.parentId)));
        };
        
        const generateNodeHTML = (node, parentId) => {
            const nodeIcons = {
                question: 'icon-brain',
                solution: 'icon-wrench',
                contact: 'icon-phone'
            };
            
            let childrenHTML = '';
            if (node.next_level_nodes && node.next_level_nodes.length > 0) {
                childrenHTML = `<div class="adminit-flowchart-node-children">${node.next_level_nodes.map(child => generateNodeHTML(child, node.id)).join('')}</div>`;
            }

            return `
                <div class="adminit-flowchart-node" data-type="${node.type}">
                    <div class="adminit-flowchart-node-header">
                        <div class="adminit-flowchart-node-content">
                            <div class="adminit-flowchart-node-icon">
                                <svg width="24" height="24" style="color: var(--text-light-secondary);"><use href="#${nodeIcons[node.type] || 'icon-brain'}"></use></svg>
                            </div>
                            <div class="adminit-flowchart-node-text">
                                <h4>${node.title}</h4>
                                <p>${node.text}</p>
                            </div>
                        </div>
                        <div class="adminit-action-buttons">
                            <button class="adminit-btn-edit-node" data-id="${node.id}" data-parent-id="${parentId || ''}"><svg width="16" height="16"><use href="#icon-edit"></use></svg></button>
                            <button class="adminit-btn-delete-node" data-id="${node.id}" data-parent-id="${parentId || ''}"><svg width="16" height="16"><use href="#icon-trash"></use></svg></button>
                            ${node.type === 'question' ? `<button class="adminit-btn-add-child-node" data-parent-id="${node.id}"><svg width="16" height="16"><use href="#icon-plus"></use></svg></button>` : ''}
                        </div>
                    </div>
                </div>
                ${childrenHTML}
            `;
        };

        // --- Page Navigation ---
        const switchPage = (hash) => {
            const pageId = 'page-' + (hash.substring(1) || 'dashboard');
            pages.forEach(p => p.classList.remove('adminit-active'));
            document.getElementById(pageId)?.classList.add('adminit-active');

            mainNav.querySelectorAll('a').forEach(a => a.classList.remove('adminit-active'));
            mainNav.querySelector(`a[href="${hash || '#dashboard'}"]`)?.classList.add('adminit-active');
            
            // Re-render content for the active page
            switch(pageId) {
                case 'page-dashboard': renderDashboard(); break;
                case 'page-categories': renderCategories(); break;
                case 'page-problems': renderProblems(); break;
                case 'page-editor': renderFlowchartEditor(); break;
            }
        };

        // --- Modal Logic ---
        const openModal = (modalId) => document.getElementById(modalId)?.classList.add('show');
        const closeModal = (modalId) => document.getElementById(modalId)?.classList.remove('show');
        
        modals.forEach(modal => {
           modal.querySelector('.btn-close-modal')?.addEventListener('click', () => closeModal(modal.id));
           modal.addEventListener('click', (e) => {
               if (e.target === modal) closeModal(modal.id);
           });
        });

        // --- Categories CRUD ---
        const openCategoryModal = (id = null) => {
            const modal = document.getElementById('category-modal');
            const title = modal.querySelector('#category-modal-title');
            const idInput = modal.querySelector('#category-id-input');
            const titleInput = modal.querySelector('#category-title-input');
            const colorPicker = modal.querySelector('#category-color-picker');
            const iconPicker = modal.querySelector('#category-icon-picker');

            colorPicker.innerHTML = availableColors.map(c => `<button data-color="${c}" style="background-color: ${c};"></button>`).join('');
            iconPicker.innerHTML = availableIcons.map(i => `<button data-name="${i.name}"><svg width="20" height="20"><use href="#${i.svgId}"></use></svg></button>`).join('');

            if (id) {
                const category = dbData.titles.find(c => c.id == id);
                title.textContent = 'แก้ไขหมวดหมู่';
                idInput.value = category.id;
                titleInput.value = category.title;
                colorPicker.querySelector(`button[data-color="${category.color}"]`)?.classList.add('selected');
                iconPicker.querySelector(`button[data-name="${category.iconName}"]`)?.classList.add('selected');
            } else {
                title.textContent = 'สร้างหมวดหมู่ใหม่';
                idInput.value = '';
                titleInput.value = '';
                colorPicker.querySelector(`button`)?.classList.add('selected');
                iconPicker.querySelector(`button`)?.classList.add('selected');
            }
            
            colorPicker.querySelectorAll('button').forEach(btn => btn.addEventListener('click', (e) => {
                colorPicker.querySelector('.selected')?.classList.remove('selected');
                e.currentTarget.classList.add('selected');
            }));
            iconPicker.querySelectorAll('button').forEach(btn => btn.addEventListener('click', (e) => {
                iconPicker.querySelector('.selected')?.classList.remove('selected');
                e.currentTarget.classList.add('selected');
            }));

            openModal('category-modal');
        };

        const saveCategory = () => {
            const modal = document.getElementById('category-modal');
            const id = modal.querySelector('#category-id-input').value;
            const title = modal.querySelector('#category-title-input').value;
            const color = modal.querySelector('#category-color-picker .selected').dataset.color;
            const iconName = modal.querySelector('#category-icon-picker .selected').dataset.name;

            if (id) { // Update
                const index = dbData.titles.findIndex(c => c.id == id);
                dbData.titles[index] = { ...dbData.titles[index], title, color, iconName };
            } else { // Create
                const newId = Date.now();
                dbData.titles.push({ id: newId, title, color, iconName });
            }
            renderCategories();
            closeModal('category-modal');
        };
        
        const deleteCategory = (id) => {
            if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบหมวดหมู่นี้? การกระทำนี้จะลบปัญหาและโฟลว์ชาร์ตที่เกี่ยวข้องทั้งหมด')) {
                const causesToDelete = dbData.causes.filter(c => c.title_id == id).map(c => c.id);
                dbData.titles = dbData.titles.filter(t => t.id != id);
                dbData.causes = dbData.causes.filter(c => c.title_id != id);
                dbData.solutions = dbData.solutions.filter(s => !causesToDelete.includes(s.cause_id));
                renderCategories();
                renderProblems();
                renderFlowchartEditor();
            }
        };

        // --- Problems CRUD ---
        const openProblemModal = (id = null) => {
            const modal = document.getElementById('problem-modal');
            const title = modal.querySelector('#problem-modal-title');
            const idInput = modal.querySelector('#problem-id-input');
            const titleInput = modal.querySelector('#problem-title-input');
            const textInput = modal.querySelector('#problem-text-input');
            const categorySelect = modal.querySelector('#problem-category-select');
            const imageInput = modal.querySelector('#problem-image-input');

            categorySelect.innerHTML = dbData.titles.map(t => `<option value="${t.id}">${t.title}</option>`).join('');

            if (id) {
                const problem = dbData.causes.find(p => p.id == id);
                title.textContent = 'แก้ไขปัญหา';
                idInput.value = problem.id;
                titleInput.value = problem.title;
                textInput.value = problem.text;
                categorySelect.value = problem.title_id;
                imageInput.value = problem.image || '';
            } else {
                title.textContent = 'สร้างปัญหาใหม่';
                idInput.value = '';
                titleInput.value = '';
                textInput.value = '';
                categorySelect.value = dbData.titles[0]?.id || '';
                imageInput.value = '';
            }
            openModal('problem-modal');
        };
        
        const saveProblem = () => {
             const modal = document.getElementById('problem-modal');
             const id = modal.querySelector('#problem-id-input').value;
             const title = modal.querySelector('#problem-title-input').value;
             const text = modal.querySelector('#problem-text-input').value;
             const title_id = parseInt(modal.querySelector('#problem-category-select').value);
             const image = modal.querySelector('#problem-image-input').value;

             if (id) { // Update
                const index = dbData.causes.findIndex(p => p.id == id);
                dbData.causes[index] = { ...dbData.causes[index], title, text, title_id, image };
             } else { // Create
                dbData.causes.push({ id: Date.now(), title, text, title_id, image });
             }
             renderProblems();
             renderFlowchartEditor();
             closeModal('problem-modal');
        };
        
        const deleteProblem = (id) => {
            if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบปัญหานี้? การกระทำนี้จะลบโฟลว์ชาร์ตการแก้ไขปัญหาทั้งหมดที่เกี่ยวข้อง')) {
                dbData.causes = dbData.causes.filter(p => p.id != id);
                dbData.solutions = dbData.solutions.filter(s => s.cause_id != id);
                renderProblems();
                renderFlowchartEditor();
            }
        };
        
        // --- Flowchart Node CRUD ---
        const findNode = (nodeId) => {
            let found = null;
            let parent = null;
            function search(nodes, p) {
                for (const node of nodes) {
                    if (node.id == nodeId) {
                        found = node;
                        parent = p;
                        return;
                    }
                    if (node.next_level_nodes) {
                        search(node.next_level_nodes, node);
                    }
                }
            }
            search(dbData.solutions, null);
            return { node: found, parent };
        };

        const openNodeModal = (nodeId, parentId) => {
            const modal = document.getElementById('node-modal');
            const titleEl = modal.querySelector('#node-modal-title');
            const idInput = modal.querySelector('#node-id-input');
            const parentIdInput = modal.querySelector('#node-parent-id-input');
            const typeSelect = modal.querySelector('#node-type-select');
            const titleInput = modal.querySelector('#node-title-input');
            const textInput = modal.querySelector('#node-text-input');
            const imageInput = modal.querySelector('#node-image-input');
            
            if (nodeId) { // Editing existing node
                const { node } = findNode(nodeId);
                titleEl.textContent = 'แก้ไขขั้นตอน';
                idInput.value = node.id;
                parentIdInput.value = parentId || '';
                typeSelect.value = node.type;
                titleInput.value = node.title;
                textInput.value = node.text;
                imageInput.value = node.image || '';
            } else { // Creating new node
                titleEl.textContent = 'เพิ่มขั้นตอนใหม่';
                idInput.value = '';
                parentIdInput.value = parentId || '';
                typeSelect.value = 'question';
                titleInput.value = '';
                textInput.value = '';
                imageInput.value = '';
            }
            openModal('node-modal');
        };
        
        const saveNode = () => {
            const modal = document.getElementById('node-modal');
            const id = parseInt(modal.querySelector('#node-id-input').value);
            const parentId = parseInt(modal.querySelector('#node-parent-id-input').value);
            const newNodeData = {
                id: id || Date.now(),
                type: modal.querySelector('#node-type-select').value,
                title: modal.querySelector('#node-title-input').value,
                text: modal.querySelector('#node-text-input').value,
                image: modal.querySelector('#node-image-input').value,
            };

            if (id) { // Update existing node
                const { node } = findNode(id);
                Object.assign(node, newNodeData);
            } else { // Create new node
                if (parentId) {
                    const { node: parentNode } = findNode(parentId);
                    if (!parentNode.next_level_nodes) {
                        parentNode.next_level_nodes = [];
                    }
                    parentNode.next_level_nodes.push(newNodeData);
                } else { // Create new root node
                    newNodeData.cause_id = parseInt(document.getElementById('flowchart-problem-select').value);
                    dbData.solutions.push(newNodeData);
                }
            }
            renderFlowchartTree();
            closeModal('node-modal');
        };

        const deleteNode = (nodeId, parentId) => {
             if (!confirm("คุณแน่ใจหรือไม่ว่าต้องการลบขั้นตอนนี้และขั้นตอนย่อยทั้งหมด?")) return;
             
             if (parentId) {
                 const { node: parentNode } = findNode(parentId);
                 parentNode.next_level_nodes = parentNode.next_level_nodes.filter(n => n.id != nodeId);
             } else { // Deleting a root node
                 dbData.solutions = dbData.solutions.filter(s => s.id != nodeId);
             }
             renderFlowchartTree();
        };


        // --- Initial Setup & Event Listeners ---
        mainNav.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link) {
                e.preventDefault();
                switchPage(link.hash);
            }
        });
        
        document.getElementById('btn-new-category').addEventListener('click', () => openCategoryModal());
        document.getElementById('btn-save-category').addEventListener('click', saveCategory);
        
        document.getElementById('btn-new-problem').addEventListener('click', () => openProblemModal());
        document.getElementById('btn-save-problem').addEventListener('click', saveProblem);
        
        document.getElementById('flowchart-problem-select').addEventListener('change', renderFlowchartTree);
        document.getElementById('btn-save-node').addEventListener('click', saveNode);

        // Initial render
        switchPage(window.location.hash);
    });
    </script>
</body>
</html>
