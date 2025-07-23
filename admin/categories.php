<?php
require_once __DIR__ . '/../inc/db.php';
include_once __DIR__ . '/../inc/admin_header.php';

// ข้อความแจ้งเตือน
$error_message = isset($_GET['error']) ? $_GET['error'] : '';
$success_message = isset($_GET['success']) ? $_GET['success'] : '';

// Handle add category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add_category') {
    $title = trim($_POST['name']);
    $color = trim($_POST['color']);
    $icon = trim($_POST['icon']);
    
    if ($title) {
        try {
            $stmt = $db->prepare("INSERT INTO title_it (title, color, icon) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $title, $color, $icon);
            
            if ($stmt->execute()) {
                header("Location: categories.php?success=" . urlencode("เพิ่มหมวดหมู่สำเร็จ"));
                exit;
            } else {
                $error_message = 'เกิดข้อผิดพลาดในการเพิ่มหมวดหมู่';
            }
            $stmt->close();
        } catch (Exception $e) {
            $error_message = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
        }
    } else {
        $error_message = 'กรุณากรอกชื่อหมวดหมู่';
    }
}

// Handle edit category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'edit_category') {
    $category_id = intval($_POST['id']);
    $title = trim($_POST['name']);
    $color = trim($_POST['color']);
    $icon = trim($_POST['icon']);
    
    if ($title && $category_id) {
        try {
            $stmt = $db->prepare("UPDATE title_it SET title = ?, color = ?, icon = ? WHERE id = ?");
            $stmt->bind_param('sssi', $title, $color, $icon, $category_id);
            
            if ($stmt->execute()) {
                header("Location: categories.php?success=" . urlencode("แก้ไขหมวดหมู่สำเร็จ"));
                exit;
            } else {
                $error_message = 'เกิดข้อผิดพลาดในการแก้ไข';
            }
            $stmt->close();
        } catch (Exception $e) {
            $error_message = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
        }
    } else {
        $error_message = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    }
}

// ดึงหมวดหมู่ทั้งหมด
$titles = [];
$res = $db->query("SELECT id, title, color, icon FROM title_it ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $titles[] = $row;
?>

<style>
/* Modal Styles สำหรับ Categories */
.category-modal-overlay {
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

.category-modal-overlay.show {
    display: flex;
    animation: modalFadeIn 0.3s ease-out;
}

.category-modal-content {
    background: #1e293b;
    border-radius: 8px;
    padding: 30px;
    max-width: 400px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    border: 1px solid #475569;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    animation: modalSlideIn 0.3s ease-out;
}

.category-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #475569;
}

.category-modal-title {
    font-size: 20px;
    font-weight: bold;
    color: #f1f5f9;
    margin: 0;
}

.category-modal-close {
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

.category-modal-close:hover {
    background: #334155;
    color: #f1f5f9;
}

.category-modal-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.category-form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.category-form-label {
    font-weight: 600;
    color: #f1f5f9;
    font-size: 14px;
}

.category-form-input {
    padding: 12px;
    border: 1px solid #475569;
    border-radius: 4px;
    background: #334155;
    color: #f1f5f9;
    font-size: 14px;
    transition: border-color 0.2s;
}

.category-form-input:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.category-form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 10px;
}

.category-modal-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    min-width: 80px;
}

.category-btn-primary {
    background: #4f46e5;
    color: white;
}

.category-btn-primary:hover {
    background: #4338ca;
}

.category-btn-secondary {
    background: #475569;
    color: #f1f5f9;
}

.category-btn-secondary:hover {
    background: #64748b;
}

.category-btn-success {
    background: #22c55e;
    color: white;
}

.category-btn-success:hover {
    background: #16a34a;
}

.color-preview {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid #475569;
    margin-top: 5px;
}

.icon-preview {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 5px;
}

.icon-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
    gap: 8px;
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #475569;
    border-radius: 4px;
    padding: 10px;
    background: #1e293b;
}

.icon-option {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #475569;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
    background: #334155;
}

.icon-option:hover,
.icon-option.selected {
    border-color: #4f46e5;
    background: #4f46e5;
}

@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
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
</style>

<!-- แสดงข้อความแจ้งเตือน -->
<?php if ($error_message): ?>
    <div class="alert alert-error">
        <span>⚠️ <?= htmlspecialchars($error_message) ?></span>
    </div>
<?php endif; ?>

<?php if ($success_message): ?>
    <div class="alert alert-success">
        <span>✅ <?= htmlspecialchars($success_message) ?></span>
    </div>
<?php endif; ?>

<h1 style="margin-bottom:2rem;">จัดการหมวดหมู่</h1>
<div style="margin-bottom:2rem;">
    <button onclick="openCategoryModal('add')" class="adminit-btn" style="background:#22c55e;color:#fff;padding:0.5rem 1.2rem;border-radius:6px;border:none;cursor:pointer;">+ เพิ่มหมวดหมู่ใหม่</button>
</div>
<table style="width:100%;border-collapse:collapse;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="padding:0.75rem;">สี</th>
            <th style="padding:0.75rem;">ไอคอน</th>
            <th style="padding:0.75rem;">ชื่อหมวดหมู่</th>
            <th style="padding:0.75rem;text-align:right;">จัดการ</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($titles as $cat): ?>
        <tr>
            <td style="padding:0.75rem;">
                <div style="width: 24px; height: 24px; border-radius: 50%; background-color: <?= htmlspecialchars($cat['color']) ?>;"></div>
            </td>
            <td style="padding:0.75rem;">
                <svg width="24" height="24"><use href="#icon-<?= htmlspecialchars($cat['icon']) ?>"></use></svg>
            </td>
            <td style="padding:0.75rem;"><?= htmlspecialchars($cat['title']) ?></td>
            <td style="padding:0.75rem;text-align:right;">
                <?php
                    $jsData = json_encode([
                        'id' => $cat['id'],
                        'name' => $cat['title'],
                        'color' => $cat['color'],
                        'icon' => $cat['icon']
                    ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
                ?>
                <button onclick='openCategoryModal("edit", <?= $jsData ?>)' class="adminit-btn" style="background:#eab308;color:#fff;padding:0.3rem 0.8rem;border-radius:6px;border:none;cursor:pointer;">แก้ไข</button>
                <a href="delete_category.php?id=<?= $cat['id'] ?>" class="adminit-btn" style="background:#dc2626;color:#fff;padding:0.3rem 0.8rem;border-radius:6px;text-decoration:none;" onclick="return confirm('ลบหมวดหมู่นี้?');">ลบ</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Category Modal -->
<div id="categoryModal" class="category-modal-overlay">
    <div class="category-modal-content">
        <div class="category-modal-header">
            <h3 id="categoryModalTitle" class="category-modal-title">เพิ่มหมวดหมู่ใหม่</h3>
            <button class="category-modal-close" onclick="closeCategoryModal()">&times;</button>
        </div>
        
        <form id="categoryForm" class="category-modal-form" method="POST">
            <input type="hidden" id="categoryAction" name="action" value="add_category">
            <input type="hidden" id="categoryId" name="id" value="">
            
            <div class="category-form-group">
                <label class="category-form-label" for="categoryName">ชื่อหมวดหมู่:</label>
                <input type="text" id="categoryName" name="name" class="category-form-input" required>
            </div>
            
            <div class="category-form-group">
                <label class="category-form-label" for="categoryColor">สี:</label>
                <input type="color" id="categoryColor" name="color" class="category-form-input" value="#4f46e5">
                <div id="colorPreview" class="color-preview" style="background-color: #4f46e5;"></div>
            </div>
            
            <div class="category-form-group">
                <label class="category-form-label">ไอคอน:</label>
                <input type="hidden" id="categoryIcon" name="icon" value="folder">
                <div class="icon-preview">
                    <svg id="selectedIconPreview" width="24" height="24" fill="currentColor">
                        <use href="#icon-folder"></use>
                    </svg>
                    <span id="selectedIconName">folder</span>
                </div>
                
                <div class="icon-list">
                    <div class="icon-option selected" data-icon="folder" onclick="selectIcon('folder')"><svg width="20" height="20" fill="currentColor"><use href="#icon-folder"></use></svg></div>
                    <div class="icon-option" data-icon="star" onclick="selectIcon('star')"><svg width="20" height="20" fill="currentColor"><use href="#icon-star"></use></svg></div>
                    <div class="icon-option" data-icon="heart" onclick="selectIcon('heart')"><svg width="20" height="20" fill="currentColor"><use href="#icon-heart"></use></svg></div>
                    <div class="icon-option" data-icon="tag" onclick="selectIcon('tag')"><svg width="20" height="20" fill="currentColor"><use href="#icon-tag"></use></svg></div>
                    <div class="icon-option" data-icon="bookmark" onclick="selectIcon('bookmark')"><svg width="20" height="20" fill="currentColor"><use href="#icon-bookmark"></use></svg></div>
                    <div class="icon-option" data-icon="document" onclick="selectIcon('document')"><svg width="20" height="20" fill="currentColor"><use href="#icon-document"></use></svg></div>
                    <div class="icon-option" data-icon="archive" onclick="selectIcon('archive')"><svg width="20" height="20" fill="currentColor"><use href="#icon-archive"></use></svg></div>
                    <div class="icon-option" data-icon="clipboard" onclick="selectIcon('clipboard')"><svg width="20" height="20" fill="currentColor"><use href="#icon-clipboard"></use></svg></div>
                    <div class="icon-option" data-icon="home" onclick="selectIcon('home')"><svg width="20" height="20" fill="currentColor"><use href="#icon-home"></use></svg></div>
                    <div class="icon-option" data-icon="user" onclick="selectIcon('user')"><svg width="20" height="20" fill="currentColor"><use href="#icon-user"></use></svg></div>
                    <div class="icon-option" data-icon="settings" onclick="selectIcon('settings')"><svg width="20" height="20" fill="currentColor"><use href="#icon-settings"></use></svg></div>
                    <div class="icon-option" data-icon="bell" onclick="selectIcon('bell')"><svg width="20" height="20" fill="currentColor"><use href="#icon-bell"></use></svg></div>
                    <div class="icon-option" data-icon="calendar" onclick="selectIcon('calendar')"><svg width="20" height="20" fill="currentColor"><use href="#icon-calendar"></use></svg></div>
                    <div class="icon-option" data-icon="chat" onclick="selectIcon('chat')"><svg width="20" height="20" fill="currentColor"><use href="#icon-chat"></use></svg></div>
                    <div class="icon-option" data-icon="cloud" onclick="selectIcon('cloud')"><svg width="20" height="20" fill="currentColor"><use href="#icon-cloud"></use></svg></div>
                    <div class="icon-option" data-icon="lock" onclick="selectIcon('lock')"><svg width="20" height="20" fill="currentColor"><use href="#icon-lock"></use></svg></div>
                    <div class="icon-option" data-icon="search" onclick="selectIcon('search')"><svg width="20" height="20" fill="currentColor"><use href="#icon-search"></use></svg></div>
                    <div class="icon-option" data-icon="wifi" onclick="selectIcon('wifi')"><svg width="20" height="20" fill="currentColor"><use href="#icon-wifi"></use></svg></div>
                    <div class="icon-option" data-icon="printer" onclick="selectIcon('printer')"><svg width="20" height="20" fill="currentColor"><use href="#icon-printer"></use></svg></div>
                    <div class="icon-option" data-icon="mail" onclick="selectIcon('mail')"><svg width="20" height="20" fill="currentColor"><use href="#icon-mail"></use></svg></div>
                    <div class="icon-option" data-icon="power" onclick="selectIcon('power')"><svg width="20" height="20" fill="currentColor"><use href="#icon-power"></use></svg></div>
                    <div class="icon-option" data-icon="smartphone" onclick="selectIcon('smartphone')"><svg width="20" height="20" fill="currentColor"><use href="#icon-smartphone"></use></svg></div>
                    <div class="icon-option" data-icon="shield" onclick="selectIcon('shield')"><svg width="20" height="20" fill="currentColor"><use href="#icon-shield"></use></svg></div>
                    <div class="icon-option" data-icon="cog" onclick="selectIcon('cog')"><svg width="20" height="20" fill="currentColor"><use href="#icon-cog"></use></svg></div>
                    <div class="icon-option" data-icon="wrench" onclick="selectIcon('wrench')"><svg width="20" height="20" fill="currentColor"><use href="#icon-wrench"></use></svg></div>
                    <div class="icon-option" data-icon="trash" onclick="selectIcon('trash')"><svg width="20" height="20" fill="currentColor"><use href="#icon-trash"></use></svg></div>
                    <div class="icon-option" data-icon="save" onclick="selectIcon('save')"><svg width="20" height="20" fill="currentColor"><use href="#icon-save"></use></svg></div>
                    <div class="icon-option" data-icon="edit" onclick="selectIcon('edit')"><svg width="20" height="20" fill="currentColor"><use href="#icon-edit"></use></svg></div>
                    <div class="icon-option" data-icon="plus" onclick="selectIcon('plus')"><svg width="20" height="20" fill="currentColor"><use href="#icon-plus"></use></svg></div>
                    <div class="icon-option" data-icon="x" onclick="selectIcon('x')"><svg width="20" height="20" fill="currentColor"><use href="#icon-x"></use></svg></div>
                    <div class="icon-option" data-icon="database" onclick="selectIcon('database')"><svg width="20" height="20" fill="currentColor"><use href="#icon-database"></use></svg></div>
                </div>
            </div>
            
            <div class="category-form-actions">
                <button type="button" class="category-modal-btn category-btn-secondary" onclick="closeCategoryModal()">ยกเลิก</button>
                <button type="submit" id="categorySubmitBtn" class="category-modal-btn category-btn-success">เพิ่มหมวดหมู่</button>
            </div>
        </form>
    </div>
</div>

<!-- Icon Definitions -->
<svg style="display: none;">
    <defs>
        <symbol id="icon-folder" viewBox="0 0 24 24"><path d="M10 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2h-8l-2-2z"/></symbol>
        <symbol id="icon-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></symbol>
        <symbol id="icon-heart" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></symbol>
        <symbol id="icon-tag" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><circle cx="7" cy="7" r="1"/></symbol>
        <symbol id="icon-bookmark" viewBox="0 0 24 24"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></symbol>
        <symbol id="icon-document" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/></symbol>
        <symbol id="icon-archive" viewBox="0 0 24 24"><polyline points="21,8 21,21 3,21 3,8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></symbol>
        <symbol id="icon-clipboard" viewBox="0 0 24 24"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></symbol>
        <symbol id="icon-home" viewBox="0 0 24 24"><path d="M3 9.5V21h6v-6h6v6h6V9.5L12 3z"/></symbol>
        <symbol id="icon-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20v-2a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v2"/></symbol>
        <symbol id="icon-settings" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2h0a2 2 0 0 1-2-2v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2h0a2 2 0 0 1 2-2h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.09a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51h.09a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2h0a2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></symbol>
        <symbol id="icon-bell" viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></symbol>
        <symbol id="icon-calendar" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></symbol>
        <symbol id="icon-chat" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></symbol>
        <symbol id="icon-cloud" viewBox="0 0 24 24"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/></symbol>
        <symbol id="icon-lock" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></symbol>
        <symbol id="icon-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></symbol>
        <symbol id="icon-wifi" viewBox="0 0 24 24"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></symbol>
        <symbol id="icon-printer" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></symbol>
        <symbol id="icon-mail" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="22,6 12,13 2,6"/></symbol>
        <symbol id="icon-power" viewBox="0 0 24 24"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"/><line x1="12" y1="2" x2="12" y2="12"/></symbol>
        <symbol id="icon-smartphone" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></symbol>
        <symbol id="icon-shield" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></symbol>
        <symbol id="icon-cog" viewBox="0 0 24 24"><path d="M12 20v-4"/><path d="M12 8V4"/><path d="M4.93 4.93l2.83 2.83"/><path d="M16.24 16.24l2.83 2.83"/><path d="M2 12h4"/><path d="M18 12h4"/><path d="M4.93 19.07l2.83-2.83"/><path d="M16.24 7.76l2.83-2.83"/><circle cx="12" cy="12" r="3"/></symbol>
        <symbol id="icon-wrench" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></symbol>
        <symbol id="icon-trash" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></symbol>
        <symbol id="icon-save" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></symbol>
        <symbol id="icon-edit" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></symbol>
        <symbol id="icon-plus" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></symbol>
        <symbol id="icon-x" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></symbol>
        <symbol id="icon-database" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></symbol>
    </defs>
</svg>

<script>
// Global variables for category modal
let currentCategoryMode = 'add';
let currentCategoryData = null;

// Open Category Modal
function openCategoryModal(mode, data = null) {
    currentCategoryMode = mode;
    currentCategoryData = data;
    
    const modal = document.getElementById('categoryModal');
    const title = document.getElementById('categoryModalTitle');
    const submitBtn = document.getElementById('categorySubmitBtn');
    const form = document.getElementById('categoryForm');
    const actionInput = document.getElementById('categoryAction');
    const idInput = document.getElementById('categoryId');
    
    // Reset form
    form.reset();
    
    if (mode === 'add') {
        title.textContent = 'เพิ่มหมวดหมู่ใหม่';
        submitBtn.textContent = 'เพิ่มหมวดหมู่';
        submitBtn.className = 'category-modal-btn category-btn-success';
        actionInput.value = 'add_category';
        idInput.value = '';
        
        // Set default values
        document.getElementById('categoryColor').value = '#4f46e5';
        document.getElementById('colorPreview').style.backgroundColor = '#4f46e5';
        selectIcon('folder');
        
    } else if (mode === 'edit') {
        title.textContent = 'แก้ไขหมวดหมู่';
        submitBtn.textContent = 'บันทึกการแก้ไข';
        submitBtn.className = 'category-modal-btn category-btn-primary';
        actionInput.value = 'edit_category';
        idInput.value = data.id;
        
        // Populate form with existing data
        document.getElementById('categoryName').value = data.name;
        document.getElementById('categoryColor').value = data.color;
        document.getElementById('colorPreview').style.backgroundColor = data.color;
        selectIcon(data.icon);
    }
    
    modal.classList.add('show');
    
    // Auto-focus name field
    setTimeout(() => {
        document.getElementById('categoryName').focus();
    }, 300);
}

// Close Category Modal
function closeCategoryModal() {
    const modal = document.getElementById('categoryModal');
    modal.classList.remove('show');
    currentCategoryMode = 'add';
    currentCategoryData = null;
}

// Select Icon
function selectIcon(iconName) {
    // Update hidden input
    document.getElementById('categoryIcon').value = iconName;
    
    // Update preview (use DOM API for SVG <use> reliability)
    const preview = document.getElementById('selectedIconPreview');
    // Remove all children
    while (preview.firstChild) preview.removeChild(preview.firstChild);
    const useElem = document.createElementNS('http://www.w3.org/2000/svg', 'use');
    useElem.setAttributeNS('http://www.w3.org/1999/xlink', 'href', `#icon-${iconName}`);
    preview.appendChild(useElem);
    document.getElementById('selectedIconName').textContent = iconName;
    
    // Update UI selection
    document.querySelectorAll('.icon-option').forEach(option => {
        option.classList.remove('selected');
        if (option.dataset.icon === iconName) {
            option.classList.add('selected');
        }
    });
}

// Color picker event listener
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('categoryColor');
    const colorPreview = document.getElementById('colorPreview');
    
    colorInput.addEventListener('input', function() {
        colorPreview.style.backgroundColor = this.value;
    });
    
    // Close modal when clicking outside
    document.getElementById('categoryModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCategoryModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('categoryModal').classList.contains('show')) {
            closeCategoryModal();
        }
    });
});
</script>

<?php include_once __DIR__ . '/../inc/footer.php'; ?>