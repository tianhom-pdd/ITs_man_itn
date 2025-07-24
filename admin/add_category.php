<?php
require_once __DIR__ . '/../inc/db.php';
include_once __DIR__ . '/../inc/admin_header.php';

// ถ้ามีการ submit ฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $color = trim($_POST['color'] ?? '#22c55e');
    $icon = trim($_POST['icon'] ?? 'category');
    $svg_icon = trim($_POST['svg_icon'] ?? '');
    if ($svg_icon !== '') {
        $icon = $svg_icon;
    }

    if ($title !== '') {
        $stmt = $db->prepare("INSERT INTO title_it (title, color, icon) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $title, $color, $icon);
        $stmt->execute();
        header('Location: categories.php');
        exit;
    } else {
        $error = 'กรุณากรอกชื่อหมวดหมู่';
    }
}
?>

<h1 style="margin-bottom:2rem;">เพิ่มหมวดหมู่ใหม่</h1>

<?php if (!empty($error)): ?>
    <div style="color:#dc2626; margin-bottom:1rem;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" style="max-width:400px;">
    <div style="margin-bottom:1.2rem;">
        <label for="title" style="display:block; margin-bottom:0.5rem;">ชื่อหมวดหมู่ <span style="color:#dc2626">*</span></label>
        <input type="text" name="title" id="title" class="adminit-input" style="width:100%;padding:0.5rem;" required>
    </div>
    <div style="margin-bottom:1.2rem;">
        <label for="color" style="display:block; margin-bottom:0.5rem;">สี</label>
        <input type="color" name="color" id="color" value="#22c55e" style="width:50px;height:34px;border:none;">
    </div>
    <div style="margin-bottom:1.2rem;">
        <label for="icon" style="display:block; margin-bottom:0.5rem;">ชื่อไอคอน (icon)</label>
        <input type="text" name="icon" id="icon" class="adminit-input" style="width:100%;padding:0.5rem;" value="category">
        <div style="font-size:0.95rem;color:#888;">* ใส่ชื่อ icon ที่มีในระบบ เช่น <b>category</b>, <b>server</b>, <b>monitor</b> เป็นต้น</div>
    </div>
    <div style="margin-bottom:1.2rem;">
        <label for="svg_icon" style="display:block; margin-bottom:0.5rem;">หรือวาง SVG icon ที่นี่</label>
        <textarea name="svg_icon" id="svg_icon" style="width:100%;height:60px;resize:vertical;" placeholder="Paste SVG code here"></textarea>
        <div id="svgPreview" style="margin-top:8px;"></div>
        <div style="margin-top:10px;">
            <label style="display:block; margin-bottom:0.5rem;">เลือกไอคอนจากชุดระบบ</label>
            <div id="iconPicker" style="display:grid;grid-template-columns:repeat(6, 1fr);gap:8px;background:#f3f4f6;padding:10px;border-radius:6px;">
                <div class="icon-option" title="folder" onclick="pickSystemIcon('folder')"><svg width="24" height="24"><use href="#icon-folder"></use></svg></div>
                <div class="icon-option" title="star" onclick="pickSystemIcon('star')"><svg width="24" height="24"><use href="#icon-star"></use></svg></div>
                <div class="icon-option" title="heart" onclick="pickSystemIcon('heart')"><svg width="24" height="24"><use href="#icon-heart"></use></svg></div>
                <div class="icon-option" title="tag" onclick="pickSystemIcon('tag')"><svg width="24" height="24"><use href="#icon-tag"></use></svg></div>
                <div class="icon-option" title="bookmark" onclick="pickSystemIcon('bookmark')"><svg width="24" height="24"><use href="#icon-bookmark"></use></svg></div>
                <div class="icon-option" title="document" onclick="pickSystemIcon('document')"><svg width="24" height="24"><use href="#icon-document"></use></svg></div>
                <div class="icon-option" title="archive" onclick="pickSystemIcon('archive')"><svg width="24" height="24"><use href="#icon-archive"></use></svg></div>
                <div class="icon-option" title="clipboard" onclick="pickSystemIcon('clipboard')"><svg width="24" height="24"><use href="#icon-clipboard"></use></svg></div>
                <div class="icon-option" title="home" onclick="pickSystemIcon('home')"><svg width="24" height="24"><use href="#icon-home"></use></svg></div>
                <div class="icon-option" title="user" onclick="pickSystemIcon('user')"><svg width="24" height="24"><use href="#icon-user"></use></svg></div>
                <div class="icon-option" title="settings" onclick="pickSystemIcon('settings')"><svg width="24" height="24"><use href="#icon-settings"></use></svg></div>
                <div class="icon-option" title="bell" onclick="pickSystemIcon('bell')"><svg width="24" height="24"><use href="#icon-bell"></use></svg></div>
                <div class="icon-option" title="calendar" onclick="pickSystemIcon('calendar')"><svg width="24" height="24"><use href="#icon-calendar"></use></svg></div>
                <div class="icon-option" title="chat" onclick="pickSystemIcon('chat')"><svg width="24" height="24"><use href="#icon-chat"></use></svg></div>
                <div class="icon-option" title="cloud" onclick="pickSystemIcon('cloud')"><svg width="24" height="24"><use href="#icon-cloud"></use></svg></div>
                <div class="icon-option" title="lock" onclick="pickSystemIcon('lock')"><svg width="24" height="24"><use href="#icon-lock"></use></svg></div>
                <div class="icon-option" title="search" onclick="pickSystemIcon('search')"><svg width="24" height="24"><use href="#icon-search"></use></svg></div>
                <div class="icon-option" title="wifi" onclick="pickSystemIcon('wifi')"><svg width="24" height="24"><use href="#icon-wifi"></use></svg></div>
                <div class="icon-option" title="printer" onclick="pickSystemIcon('printer')"><svg width="24" height="24"><use href="#icon-printer"></use></svg></div>
                <div class="icon-option" title="mail" onclick="pickSystemIcon('mail')"><svg width="24" height="24"><use href="#icon-mail"></use></svg></div>
                <div class="icon-option" title="power" onclick="pickSystemIcon('power')"><svg width="24" height="24"><use href="#icon-power"></use></svg></div>
                <div class="icon-option" title="smartphone" onclick="pickSystemIcon('smartphone')"><svg width="24" height="24"><use href="#icon-smartphone"></use></svg></div>
                <div class="icon-option" title="shield" onclick="pickSystemIcon('shield')"><svg width="24" height="24"><use href="#icon-shield"></use></svg></div>
                <div class="icon-option" title="cog" onclick="pickSystemIcon('cog')"><svg width="24" height="24"><use href="#icon-cog"></use></svg></div>
                <div class="icon-option" title="wrench" onclick="pickSystemIcon('wrench')"><svg width="24" height="24"><use href="#icon-wrench"></use></svg></div>
                <div class="icon-option" title="trash" onclick="pickSystemIcon('trash')"><svg width="24" height="24"><use href="#icon-trash"></use></svg></div>
                <div class="icon-option" title="save" onclick="pickSystemIcon('save')"><svg width="24" height="24"><use href="#icon-save"></use></svg></div>
                <div class="icon-option" title="edit" onclick="pickSystemIcon('edit')"><svg width="24" height="24"><use href="#icon-edit"></use></svg></div>
                <div class="icon-option" title="plus" onclick="pickSystemIcon('plus')"><svg width="24" height="24"><use href="#icon-plus"></use></svg></div>
                <div class="icon-option" title="x" onclick="pickSystemIcon('x')"><svg width="24" height="24"><use href="#icon-x"></use></svg></div>
                <div class="icon-option" title="database" onclick="pickSystemIcon('database')"><svg width="24" height="24"><use href="#icon-database"></use></svg></div>
            </div>
        </div>
    </div>
    <div>
        <button type="submit" class="adminit-btn" style="background:#22c55e;color:#fff;padding:0.5rem 1.5rem;border-radius:6px;">บันทึก</button>
        <a href="categories.php" class="adminit-btn" style="background:#888;color:#fff;padding:0.5rem 1.5rem;border-radius:6px;text-decoration:none;">ยกเลิก</a>
    </div>
</form>

<!-- Icon Definitions -->
<svg style="display: none;">
    <defs>
        <!-- Basic Category Icons -->
        <symbol id="icon-home" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9l9-7z"/>
            <path d="M9 22V12h6v10"/>
        </symbol>
        <symbol id="icon-heart" viewBox="0 0 24 24" fill="currentColor">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
        </symbol>
        <symbol id="icon-power" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2v10"/>
            <path d="M18.4 6.6a9 9 0 1 1-12.77.04"/>
        </symbol>
        <symbol id="icon-network" viewBox="0 0 24 24" fill="currentColor">
            <rect x="3" y="3" width="6" height="6" rx="1"/>
            <rect x="15" y="3" width="6" height="6" rx="1"/>
            <rect x="9" y="15" width="6" height="6" rx="1"/>
            <path d="M6 9v1a2 2 0 0 0 2 2h1"/>
            <path d="M15 9v1a2 2 0 0 1-2 2h-1"/>
            <path d="M12 15v-4"/>
        </symbol>
        <symbol id="icon-server" viewBox="0 0 24 24" fill="currentColor">
            <rect x="2" y="3" width="20" height="4" rx="1"/>
            <rect x="2" y="9" width="20" height="4" rx="1"/>
            <rect x="2" y="15" width="20" height="4" rx="1"/>
            <path d="M6 6h.01"/>
            <path d="M6 12h.01"/>
            <path d="M6 18h.01"/>
        </symbol>
        <symbol id="icon-wifi" viewBox="0 0 24 24" fill="currentColor">
            <path d="M1.42 9a16 16 0 0 1 21.16 0"/>
            <path d="M5 12.55a11 11 0 0 1 14.08 0"/>
            <path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
            <path d="M12 20h.01"/>
        </symbol>
        <symbol id="icon-computer" viewBox="0 0 24 24" fill="currentColor">
            <rect x="2" y="4" width="20" height="12" rx="2"/>
            <path d="M8 20h8"/>
            <path d="M12 16v4"/>
        </symbol>
        <symbol id="icon-database" viewBox="0 0 24 24" fill="currentColor">
            <ellipse cx="12" cy="5" rx="9" ry="3"/>
            <path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/>
            <path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3"/>
        </symbol>
        <symbol id="icon-settings" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"/>
        </symbol>
        
        <!-- Additional Category Icons -->
        <symbol id="icon-folder" viewBox="0 0 24 24" fill="currentColor">
            <path d="M10 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2h-8l-2-2z"/>
        </symbol>
        <symbol id="icon-star" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </symbol>
        <symbol id="icon-tag" viewBox="0 0 24 24" fill="currentColor">
            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
            <circle cx="7" cy="7" r="1"/>
        </symbol>
        <symbol id="icon-bookmark" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
        </symbol>
        <symbol id="icon-document" viewBox="0 0 24 24" fill="currentColor">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14,2 14,8 20,8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <polyline points="10,9 9,9 8,9"/>
        </symbol>
        <symbol id="icon-archive" viewBox="0 0 24 24" fill="currentColor">
            <polyline points="21,8 21,21 3,21 3,8"/>
            <rect x="1" y="3" width="22" height="5"/>
            <line x1="10" y1="12" x2="14" y2="12"/>
        </symbol>
        <symbol id="icon-clipboard" viewBox="0 0 24 24" fill="currentColor">
            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
        </symbol>
        <symbol id="icon-user" viewBox="0 0 24 24" fill="currentColor">
            <circle cx="12" cy="8" r="4"/>
            <path d="M4 20v-2a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v2"/>
        </symbol>
        <symbol id="icon-bell" viewBox="0 0 24 24" fill="currentColor">
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </symbol>
        <symbol id="icon-calendar" viewBox="0 0 24 24" fill="currentColor">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
        </symbol>
        <symbol id="icon-chat" viewBox="0 0 24 24" fill="currentColor">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </symbol>
        <symbol id="icon-cloud" viewBox="0 0 24 24" fill="currentColor">
            <path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/>
        </symbol>
        <symbol id="icon-lock" viewBox="0 0 24 24" fill="currentColor">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </symbol>
        <symbol id="icon-search" viewBox="0 0 24 24" fill="currentColor">
            <circle cx="11" cy="11" r="8"/>
            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </symbol>
        <symbol id="icon-printer" viewBox="0 0 24 24" fill="currentColor">
            <polyline points="6 9 6 2 18 2 18 9"/>
            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
            <rect x="6" y="14" width="12" height="8"/>
        </symbol>
        <symbol id="icon-mail" viewBox="0 0 24 24" fill="currentColor">
            <rect x="2" y="4" width="20" height="16" rx="2"/>
            <polyline points="22,6 12,13 2,6"/>
        </symbol>
        <symbol id="icon-smartphone" viewBox="0 0 24 24" fill="currentColor">
            <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
            <line x1="12" y1="18" x2="12.01" y2="18"/>
        </symbol>
        <symbol id="icon-shield" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        </symbol>
        <symbol id="icon-cog" viewBox="0 0 24 24" fill="currentColor">
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2h0a2 2 0 0 1-2-2v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2h0a2 2 0 0 1 2-2h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.09a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51h.09a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2h0a2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
        </symbol>
        <symbol id="icon-wrench" viewBox="0 0 24 24" fill="currentColor">
            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
        </symbol>
        <symbol id="icon-trash" viewBox="0 0 24 24" fill="currentColor">
            <polyline points="3 6 5 6 21 6"/>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
            <line x1="10" y1="11" x2="10" y2="17"/>
            <line x1="14" y1="11" x2="14" y2="17"/>
        </symbol>
        <symbol id="icon-save" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/>
            <polyline points="7 3 7 8 15 8"/>
        </symbol>
        <symbol id="icon-edit" viewBox="0 0 24 24" fill="currentColor">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </symbol>
        <symbol id="icon-plus" viewBox="0 0 24 24" fill="currentColor">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </symbol>
        <symbol id="icon-x" viewBox="0 0 24 24" fill="currentColor">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
        </symbol>
    </defs>
</svg>

<script>
document.getElementById('svg_icon').addEventListener('input', function() {
    document.getElementById('svgPreview').innerHTML = this.value;
});
function pickSystemIcon(iconName) {
    // สร้าง SVG string จากชื่อไอคอน
    const svg = `<svg width='24' height='24' fill='currentColor'><use href='#icon-${iconName}'></use></svg>`;
    document.getElementById('svg_icon').value = svg;
    document.getElementById('svgPreview').innerHTML = svg;
}
</script>

<style>
.icon-option {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    cursor: pointer;
    background: #fff;
    transition: all 0.2s;
}
.icon-option:hover {
    border-color: #4f46e5;
    background: #e0e7ff;
}
#iconPicker {
    margin-top: 5px;
}
</style>

<!-- SVG Icon Definitions -->
<svg style="display: none;" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <symbol id="icon-home" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9l9-7z"/>
            <path d="M9 22V12h6v10"/>
        </symbol>
        <symbol id="icon-heart" viewBox="0 0 24 24" fill="currentColor">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
        </symbol>
        <symbol id="icon-power" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2v10"/>
            <path d="M18.4 6.6a9 9 0 1 1-12.77.04"/>
        </symbol>
        <symbol id="icon-network" viewBox="0 0 24 24" fill="currentColor">
            <rect x="3" y="3" width="6" height="6" rx="1"/>
            <rect x="15" y="3" width="6" height="6" rx="1"/>
            <rect x="9" y="15" width="6" height="6" rx="1"/>
            <path d="M6 9v1a2 2 0 0 0 2 2h1"/>
            <path d="M15 9v1a2 2 0 0 1-2 2h-1"/>
            <path d="M12 15v-4"/>
        </symbol>
        <symbol id="icon-server" viewBox="0 0 24 24" fill="currentColor">
            <rect x="2" y="3" width="20" height="4" rx="1"/>
            <rect x="2" y="9" width="20" height="4" rx="1"/>
            <rect x="2" y="15" width="20" height="4" rx="1"/>
            <path d="M6 6h.01"/>
            <path d="M6 12h.01"/>
            <path d="M6 18h.01"/>
        </symbol>
        <symbol id="icon-wifi" viewBox="0 0 24 24" fill="currentColor">
            <path d="M1.42 9a16 16 0 0 1 21.16 0"/>
            <path d="M5 12.55a11 11 0 0 1 14.08 0"/>
            <path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
            <path d="M12 20h.01"/>
        </symbol>
        <symbol id="icon-computer" viewBox="0 0 24 24" fill="currentColor">
            <rect x="2" y="4" width="20" height="12" rx="2"/>
            <path d="M8 20h8"/>
            <path d="M12 16v4"/>
        </symbol>
        <symbol id="icon-database" viewBox="0 0 24 24" fill="currentColor">
            <ellipse cx="12" cy="5" rx="9" ry="3"/>
            <path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/>
            <path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3"/>
        </symbol>
        <symbol id="icon-settings" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"/>
        </symbol>
    </defs>
</svg>

<?php include_once __DIR__ . '/../inc/footer.php'; ?>