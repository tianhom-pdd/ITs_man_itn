
    
<?php include_once __DIR__ . '/inc/header.php'; ?>


<main class="itsmainmn-page-container">
        <div id="itsmainmn-flowchart-main-container">
            <svg id="itsmainmn-connector-svg"></svg>
            </div>
    </main>

    <div class="itsmainmn-modal-overlay" id="itsmainmn-solution-modal">
        <div class="itsmainmn-modal-content">
            <div class="itsmainmn-modal-header">
                <h2 class="itsmainmn-modal-title">
                    <span class="itsmainmn-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </span>
                    <span id="solution-title">วิธีแก้ไขปัญหา</span>
                </h2>
                <button class="itsmainmn-close-btn">&times;</button>
            </div>
            <div class="itsmainmn-modal-body" id="itsmainmn-solution-text"></div>
        </div>
    </div>


    <div class="itsmainmn-modal-overlay" id="itsmainmn-contact-modal">
        <div class="itsmainmn-modal-content">
            <div class="itsmainmn-modal-header">
                <h2 class="itsmainmn-modal-title">
                    <span class="itsmainmn-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 1a5 5 0 0 0-5 5v1h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a6 6 0 1 1 12 0v6a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1V6a5 5 0 0 0-5-5z"/>
                        </svg>
                    </span>
                    <span>ติดต่อแผนก IT Support</span>
                </h2>
                <button class="itsmainmn-close-btn">&times;</button>
            </div>
            <div class="itsmainmn-modal-body">
                <ul class="itsmainmn-contact-info-list">
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                        </svg>
                        <span><strong>เบอร์โทรศัพท์:</strong> 02-123-4567 ต่อ 1234, 1235</span>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                        </svg>
                        <span><strong>อีเมล:</strong> itsupport@example.co.th</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <button id="itsmainmn-contact-fab" title="ติดต่อ IT Support">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 1a5 5 0 0 0-5 5v1h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a6 6 0 1 1 12 0v6a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1V6a5 5 0 0 0-5-5z"/>
        </svg>
    </button>
    
    <div class="itsmainmn-image-popup-overlay" id="itsmainmn-image-popup">
        <div class="itsmainmn-image-popup-content">
            <img src="" alt="Problem Preview">
        </div>
    </div>
 <script>
    document.addEventListener('DOMContentLoaded', () => {

        // --- Data Store ---
        const dbData = {
            titles: [ 
                { id: 1, title: 'Network', color: '#ef4444', icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 01-9-9 9 9 0 019-9m9 9a9 9 0 01-9 9m-9-9h18m-9 9a9 9 0 009-9m-9 9V3m9 18V3" /></svg>'}, 
                { id: 2, title: 'SAP', color: '#eab308', icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7a8 8 0 0116 0" /></svg>'}, 
                { id: 3, title: 'CIMCO', color: '#22c55e', icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>'}, 
                { id: 4, title: 'Printer', color: '#3b82f6', icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>'},
                { id: 5, title: 'Software', color: '#8b5cf6', icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" /></svg>'},
                { id: 6, title: 'Hardware', color: '#ec4899', icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-1.621-.871a3 3 0 01-.879-2.122v-1.007m-5.14.007a3 3 0 016 0M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'},
                { id: 7, title: 'Email', color: '#f97316', icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>'}
            ],
            causes: [ 
                { id: 1, title: 'สายหลุด/หลวม', text: 'ตรวจสอบการเชื่อมต่อสายเคเบิล', title_id: 1, image: 'https://placehold.co/600x400/ef4444/FFFFFF?text=Check+LAN+Cable' }, 
                { id: 2, title: 'ไม่ได้เชื่อมต่อ Wi-Fi', text: 'ตรวจสอบสถานะการเชื่อมต่อไร้สาย', title_id: 1, image: 'https://placehold.co/600x400/ef4444/FFFFFF?text=Wi-Fi+Icon' }, 
                { id: 14, title: 'IP Address ชนกัน', text: 'ไม่สามารถรับ IP Address ได้', title_id: 1, image: 'https://placehold.co/600x400/ef4444/FFFFFF?text=IP+Conflict' },
                { id: 21, title: 'Router ไม่มีไฟ', text: 'ไฟสถานะบน Router ดับทั้งหมด', title_id: 1, image: 'https://placehold.co/600x400/ef4444/FFFFFF?text=Router+Lights+Off' },
                { id: 6, title: 'Login ไม่ได้', text: 'รหัสผ่านผิดหรือ User ถูกล็อค', title_id: 2, image: 'https://placehold.co/600x400/eab308/FFFFFF?text=SAP+Login+Screen' }, 
                { id: 7, title: 'โปรแกรมช้า/ค้าง', text: 'การประมวลผลใช้เวลานานผิดปกติ', title_id: 2, image: 'https://placehold.co/600x400/eab308/FFFFFF?text=Loading+Cursor' },
                { id: 15, title: 'Authorization Error', text: 'ไม่มีสิทธิ์เข้าถึง Transaction', title_id: 2, image: 'https://placehold.co/600x400/eab308/FFFFFF?text=Authorization+Error' },
                { id: 8, title: 'ส่งโปรแกรมไม่เข้าเครื่องจักร', text: 'การเชื่อมต่อกับเครื่อง CNC มีปัญหา', title_id: 3, image: 'https://placehold.co/600x400/22c55e/FFFFFF?text=CNC+Fail' },
                { id: 16, title: 'License หมดอายุ', text: 'โปรแกรมแจ้งเตือน License', title_id: 3, image: 'https://placehold.co/600x400/22c55e/FFFFFF?text=License+Expired' },
                { id: 9, title: 'สั่งพิมพ์ไม่ออก', text: 'เครื่องพิมพ์ Offline หรือกระดาษติด', title_id: 4, image: 'https://placehold.co/600x400/3b82f6/FFFFFF?text=Paper+Jam' }, 
                { id: 10, title: 'พิมพ์เป็นตัวอักษรแปลกๆ', text: 'ไดรเวอร์เครื่องพิมพ์มีปัญหา', title_id: 4, image: 'https://placehold.co/600x400/3b82f6/FFFFFF?text=Garbled+Print' },
                { id: 17, title: 'หมึกพิมพ์หมด', text: 'สีซีดจาง หรือมีแจ้งเตือน', title_id: 4, image: 'https://placehold.co/600x400/3b82f6/FFFFFF?text=Low+Ink' },
                { id: 11, title: 'โปรแกรมเปิดไม่ได้', text: 'ไฟล์โปรแกรมอาจเสียหาย', title_id: 5, image: 'https://placehold.co/600x400/8b5cf6/FFFFFF?text=Program+Wont+Open' },
                { id: 18, title: 'โปรแกรมต้องการอัปเดต', text: 'มีแจ้งเตือนให้อัปเดตเวอร์ชัน', title_id: 5, image: 'https://placehold.co/600x400/8b5cf6/FFFFFF?text=Update+Required' },
                { id: 22, title: 'Error "DLL Not Found"', text: 'โปรแกรมฟ้องว่าหาไฟล์ .dll ไม่เจอ', title_id: 5, image: 'https://placehold.co/600x400/8b5cf6/FFFFFF?text=DLL+Error' },
                { id: 12, title: 'เมาส์/คีย์บอร์ดไม่ทำงาน', text: 'การเชื่อมต่อหรือพลังงานมีปัญหา', title_id: 6, image: 'https://placehold.co/600x400/ec4899/FFFFFF?text=Mouse/Keyboard+Fail' },
                { id: 19, title: 'คอมพิวเตอร์เปิดไม่ติด', text: 'ไม่มีไฟเข้าเครื่อง', title_id: 6, image: 'https://placehold.co/600x400/ec4899/FFFFFF?text=No+Power' },
                { id: 23, title: 'หน้าจอไม่แสดงผล', text: 'คอมพิวเตอร์ทำงานแต่จอภาพมืด', title_id: 6, image: 'https://placehold.co/600x400/ec4899/FFFFFF?text=Blank+Screen' },
                { id: 13, title: 'ไม่สามารถส่ง/รับอีเมลได้', text: 'การตั้งค่าหรืออินเทอร์เน็ตขัดข้อง', title_id: 7, image: 'https://placehold.co/600x400/f97316/FFFFFF?text=Email+Fail' },
                { id: 20, title: 'พื้นที่จัดเก็บอีเมลเต็ม', text: 'มีแจ้งเตือนพื้นที่ใกล้เต็ม', title_id: 7, image: 'https://placehold.co/600x400/f97316/FFFFFF?text=Email+Storage+Full' }
            ],
            solutions: [
                 { 
                    id: 1, cause_id: 1, type: 'question', title: 'STEP 1: ตรวจสอบสาย LAN', text: 'ตรวจสอบว่าสาย LAN เสียบแน่นทั้งสองฝั่ง (คอมพิวเตอร์และ Router)',
                    image: 'https://placehold.co/600x400/ef4444/FFFFFF?text=Step+1:+Check+Cable',
                    next_level_nodes: [
                        { id: 1001, type: 'solution', title: 'หาย', text: 'ปัญหาเกิดจากสาย LAN หลวม' },
                        { id: 1002, type: 'question', title: 'ไม่หาย', text: 'STEP 2: ลองเปลี่ยนช่องเสียบ LAN', image: 'https://placehold.co/600x400/ef4444/FFFFFF?text=Step+2:+Change+Port',
                            next_level_nodes: [
                                { id: 1003, type: 'solution', title: 'หาย', text: 'ปัญหาเกิดจาก Port เดิมของ Router/Switch เสีย' },
                                { id: 1004, type: 'question', title: 'ไม่หาย', text: 'STEP 3: ลองเปลี่ยนสาย LAN', image: 'https://placehold.co/600x400/ef4444/FFFFFF?text=Step+3:+New+Cable',
                                    next_level_nodes: [
                                        { id: 1005, type: 'solution', title: 'หาย', text: 'ปัญหาเกิดจากสาย LAN เส้นเดิมเสีย' },
                                        { id: 1006, type: 'contact', title: 'ไม่หาย', text: 'จำเป็นต้องให้ IT Support ตรวจสอบ' }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                { 
                    id: 2, cause_id: 2, type: 'question', title: 'STEP 1: เชื่อมต่อ Wi-Fi', text: 'เลือกเครือข่าย Wi-Fi ที่ถูกต้องและใส่รหัสผ่าน', image: 'https://placehold.co/600x400/ef4444/FFFFFF?text=Step+1:+Connect+Wi-Fi',
                    next_level_nodes: [
                        { id: 2001, type: 'solution', title: 'หาย', text: 'ปัญหาเกิดจากการไม่ได้เชื่อมต่อ Wi-Fi' },
                        { id: 2002, type: 'question', title: 'ไม่หาย', text: 'STEP 2: Restart Wi-Fi', image: 'https://placehold.co/600x400/ef4444/FFFFFF?text=Step+2:+Restart+Wi-Fi',
                            next_level_nodes: [
                                { id: 2003, type: 'solution', title: 'หาย', text: 'Wi-Fi Adapter ของคอมพิวเตอร์อาจทำงานผิดพลาดชั่วคราว' },
                                { id: 2004, type: 'question', title: 'ไม่หาย', text: 'STEP 3: Restart Router', image: 'https://placehold.co/600x400/ef4444/FFFFFF?text=Step+3:+Restart+Router',
                                    next_level_nodes: [
                                        { id: 2005, type: 'solution', title: 'หาย', text: 'Router Wi-Fi อาจทำงานผิดพลาดชั่วคราว' },
                                        { id: 2006, type: 'contact', title: 'ไม่หาย', text: 'จำเป็นต้องให้ IT Support ตรวจสอบ' }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                { id: 3, cause_id: 6, type: 'contact', text: 'ติดต่อแผนก IT เพื่อทำการรีเซ็ตรหัสผ่าน'},
                { 
                    id: 4, cause_id: 7, type: 'question', title: 'STEP 1: Restart SAP GUI', text: 'ปิดและเปิดโปรแกรม SAP GUI ใหม่อีกครั้ง', image: 'https://placehold.co/600x400/eab308/FFFFFF?text=Step+1:+Restart+SAP',
                    next_level_nodes: [
                        { id: 4001, type: 'solution', title: 'หาย', text: 'ปัญหาได้รับการแก้ไขเรียบร้อย' },
                        { id: 4002, type: 'question', title: 'ไม่หาย', text: 'STEP 2: Restart คอมพิวเตอร์', image: 'https://placehold.co/600x400/eab308/FFFFFF?text=Step+2:+Restart+PC',
                            next_level_nodes: [
                                { id: 4003, type: 'solution', title: 'หาย', text: 'ปัญหาได้รับการแก้ไขหลังจาก Restart คอมพิวเตอร์' },
                                { id: 4004, type: 'contact', title: 'ไม่หาย', text: 'จำเป็นต้องให้ IT Support ตรวจสอบ' }
                            ]
                        }
                    ]
                },
                { 
                    id: 5, cause_id: 8, type: 'question', title: 'STEP 1: ตรวจสอบสายและ Port', text: 'ตรวจสอบสายเชื่อมต่อ RS232 และการตั้งค่า Port', image: 'https://placehold.co/600x400/22c55e/FFFFFF?text=Step+1:+Check+Port',
                    next_level_nodes: [
                        { id: 5001, type: 'solution', title: 'หาย', text: 'ปัญหาเกิดจากการตั้งค่า Port หรือสายเชื่อมต่อ' },
                        { id: 5002, type: 'question', title: 'ไม่หาย', text: 'STEP 2: Restart โปรแกรม CIMCO', image: 'https://placehold.co/600x400/22c55e/FFFFFF?text=Step+2:+Restart+CIMCO',
                            next_level_nodes: [
                                { id: 5003, type: 'solution', title: 'หาย', text: 'โปรแกรม CIMCO อาจทำงานผิดพลาดชั่วคราว' },
                                { id: 5004, type: 'contact', title: 'ไม่หาย', text: 'จำเป็นต้องให้ IT Support ตรวจสอบ' }
                            ]
                        }
                    ]
                },
                { 
                    id: 6, cause_id: 9, type: 'question', title: 'STEP 1: ตรวจสอบเครื่องพิมพ์', text: 'ตรวจสอบสถานะ, กระดาษ, หมึก และเปิด-ปิดใหม่', image: 'https://placehold.co/600x400/3b82f6/FFFFFF?text=Step+1:+Check+Printer',
                    next_level_nodes: [
                        { id: 6001, type: 'solution', title: 'หาย', text: 'ปัญหาเกิดจากสถานะของเครื่องพิมพ์' },
                        { id: 6002, type: 'question', title: 'ไม่หาย', text: 'STEP 2: Restart คอมพิวเตอร์', image: 'https://placehold.co/600x400/3b82f6/FFFFFF?text=Step+2:+Restart+PC',
                            next_level_nodes: [
                                { id: 6003, type: 'solution', title: 'หาย', text: 'Print Spooler ของ Windows อาจทำงานผิดพลาด' },
                                { id: 6004, type: 'question', title: 'ไม่หาย', text: 'STEP 3: ติดตั้งไดรเวอร์ใหม่', image: 'https://placehold.co/600x400/3b82f6/FFFFFF?text=Step+3:+Reinstall+Driver',
                                    next_level_nodes: [
                                        { id: 6005, type: 'solution', title: 'หาย', text: 'ไดรเวอร์เครื่องพิมพ์เดิมอาจเสียหาย' },
                                        { id: 6006, type: 'contact', title: 'ไม่หาย', text: 'จำเป็นต้องให้ IT Support ตรวจสอบ' }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                { 
                    id: 7, cause_id: 10, type: 'question', title: 'STEP 1: ติดตั้งไดรเวอร์ใหม่', text: 'ทำการลบและติดตั้งไดรเวอร์เครื่องพิมพ์ใหม่อีกครั้ง', image: 'https://placehold.co/600x400/3b82f6/FFFFFF?text=Step+1:+Reinstall+Driver',
                    next_level_nodes: [
                        { id: 7001, type: 'solution', title: 'หาย', text: 'ปัญหาเกิดจากไดรเวอร์เครื่องพิมพ์เสียหาย' },
                        { id: 7002, type: 'question', title: 'ไม่หาย', text: 'STEP 2: ลองพิมพ์จากโปรแกรมอื่น', image: 'https://placehold.co/600x400/3b82f6/FFFFFF?text=Step+2:+Test+Print',
                            next_level_nodes: [
                                { id: 7003, type: 'solution', title: 'พิมพ์ได้ปกติ', text: 'ปัญหาอาจเกิดจากไฟล์หรือโปรแกรมที่ใช้พิมพ์' },
                                { id: 7004, type: 'contact', title: 'ยังพิมพ์เพี้ยน', text: 'จำเป็นต้องให้ IT Support ตรวจสอบ' }
                            ]
                        }
                    ]
                },
                { 
                    id: 8, cause_id: 11, type: 'question', title: 'STEP 1: Restart คอมพิวเตอร์', text: 'ลองทำการ Restart คอมพิวเตอร์ก่อนเป็นอันดับแรก', image: 'https://placehold.co/600x400/8b5cf6/FFFFFF?text=Step+1:+Restart+PC',
                    next_level_nodes: [
                        { id: 8001, type: 'solution', title: 'หาย', text: 'โปรแกรมอาจทำงานผิดพลาดชั่วคราว' },
                        { id: 8002, type: 'question', title: 'ไม่หาย', text: 'STEP 2: Run as Administrator', image: 'https://placehold.co/600x400/8b5cf6/FFFFFF?text=Step+2:+Run+as+Admin',
                            next_level_nodes: [
                                { id: 8003, type: 'solution', title: 'หาย', text: 'โปรแกรมต้องการสิทธิ์ผู้ดูแลในการทำงาน' },
                                { id: 8004, type: 'question', title: 'ไม่หาย', text: 'STEP 3: ติดตั้งโปรแกรมใหม่', image: 'https://placehold.co/600x400/8b5cf6/FFFFFF?text=Step+3:+Reinstall',
                                    next_level_nodes: [
                                        { id: 8005, type: 'solution', title: 'หาย', text: 'ไฟล์โปรแกรมเดิมอาจเสียหาย' },
                                        { id: 8006, type: 'contact', title: 'ไม่หาย', text: 'จำเป็นต้องให้ IT Support ตรวจสอบ' }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                { 
                    id: 9, cause_id: 12, type: 'question', title: 'STEP 1: ตรวจสอบการเชื่อมต่อ', text: 'ตรวจสอบ Port USB หรือเปลี่ยนถ่าน/แบตเตอรี่', image: 'https://placehold.co/600x400/ec4899/FFFFFF?text=Step+1:+Check+Connection',
                    next_level_nodes: [
                        { id: 9001, type: 'solution', title: 'หาย', text: 'ปัญหาเกิดจากการเชื่อมต่อหรือพลังงาน' },
                        { id: 9002, type: 'question', title: 'ไม่หาย', text: 'STEP 2: ลองเปลี่ยน Port USB', image: 'https://placehold.co/600x400/ec4899/FFFFFF?text=Step+2:+Change+Port',
                            next_level_nodes: [
                                { id: 9003, type: 'solution', title: 'หาย', text: 'Port USB เดิมอาจมีปัญหา' },
                                { id: 9004, type: 'question', title: 'ไม่หาย', text: 'STEP 3: Restart คอมพิวเตอร์', image: 'https://placehold.co/600x400/ec4899/FFFFFF?text=Step+3:+Restart+PC',
                                    next_level_nodes: [
                                        { id: 9005, type: 'solution', title: 'หาย', text: 'ไดรเวอร์ของอุปกรณ์อาจทำงานผิดพลาด' },
                                        { id: 9006, type: 'contact', title: 'ไม่หาย', text: 'อุปกรณ์อาจเสียหาย, ติดต่อ IT Support' }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                { 
                    id: 10, cause_id: 13, type: 'question', title: 'STEP 1: ตรวจสอบอินเทอร์เน็ต', text: 'ลองเข้าเว็บไซต์อื่นเพื่อตรวจสอบว่าอินเทอร์เน็ตใช้งานได้หรือไม่', image: 'https://placehold.co/600x400/f97316/FFFFFF?text=Step+1:+Check+Internet',
                    next_level_nodes: [
                        { id: 10001, type: 'solution', title: 'อินเทอร์เน็ตใช้ไม่ได้', text: 'ปัญหาเกิดจากอินเทอร์เน็ต, ให้กลับไปแก้ปัญหา Network' },
                        { id: 10002, type: 'question', title: 'อินเทอร์เน็ตปกติ', text: 'STEP 2: Restart โปรแกรมอีเมล', image: 'https://placehold.co/600x400/f97316/FFFFFF?text=Step+2:+Restart+Email',
                            next_level_nodes: [
                                { id: 10003, type: 'solution', title: 'หาย', text: 'โปรแกรมอีเมลอาจทำงานผิดพลาดชั่วคราว' },
                                { id: 10004, type: 'question', title: 'ไม่หาย', text: 'STEP 3: ตรวจสอบผ่าน Webmail', image: 'https://placehold.co/600x400/f97316/FFFFFF?text=Step+3:+Check+Webmail',
                                    next_level_nodes: [
                                        { id: 10005, type: 'solution', title: 'Webmail ใช้ได้', text: 'ปัญหาเกิดจากการตั้งค่าในโปรแกรม, ติดต่อ IT' },
                                        { id: 10006, type: 'contact', title: 'Webmail ใช้ไม่ได้', text: 'ปัญหาเกิดจาก Server, ติดต่อ IT Support' }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                { 
                    id: 11, cause_id: 14, type: 'question', title: 'STEP 1: Renew IP Address', text: 'เปิด Command Prompt แล้วพิมพ์ ipconfig /renew', image: 'https://placehold.co/600x400/ef4444/FFFFFF?text=Step+1:+ipconfig+/renew',
                    next_level_nodes: [
                        { id: 11001, type: 'solution', title: 'หาย', text: 'ได้รับ IP Address ใหม่และใช้งานได้ปกติ' },
                        { id: 11002, type: 'question', title: 'ไม่หาย', text: 'STEP 2: Restart คอมพิวเตอร์', image: 'https://placehold.co/600x400/ef4444/FFFFFF?text=Step+2:+Restart+PC',
                            next_level_nodes: [
                                { id: 11003, type: 'solution', title: 'หาย', text: 'ปัญหาได้รับการแก้ไขหลัง Restart' },
                                { id: 11004, type: 'contact', title: 'ไม่หาย', text: 'จำเป็นต้องให้ IT Support ตรวจสอบ' }
                            ]
                        }
                    ]
                },
                { id: 12, cause_id: 15, type: 'contact', text: 'ติดต่อ IT Support เพื่อขอสิทธิ์การใช้งาน Transaction ที่ต้องการ'},
                { id: 13, cause_id: 16, type: 'contact', text: 'ติดต่อ IT เพื่อต่ออายุ License ของโปรแกรม'},
                { 
                    id: 14, cause_id: 17, type: 'question', title: 'STEP 1: เปลี่ยนตลับหมึก', text: 'เปลี่ยนตลับหมึกพิมพ์ใหม่ตามรุ่นของเครื่องพิมพ์', image: 'https://placehold.co/600x400/3b82f6/FFFFFF?text=Step+1:+Replace+Ink',
                    next_level_nodes: [
                        { id: 14001, type: 'solution', title: 'หาย', text: 'ปัญหาเกิดจากหมึกหมด' },
                        { id: 14002, type: 'question', title: 'ไม่หาย', text: 'STEP 2: เปิด-ปิดเครื่องพิมพ์ใหม่', image: 'https://placehold.co/600x400/3b82f6/FFFFFF?text=Step+2:+Restart+Printer',
                            next_level_nodes: [
                                { id: 14003, type: 'solution', title: 'หาย', text: 'เครื่องพิมพ์อาจต้องใช้เวลาในการตรวจจับตลับหมึกใหม่' },
                                { id: 14004, type: 'contact', title: 'ไม่หาย', text: 'ตลับหมึกหรือเครื่องพิมพ์อาจมีปัญหา, ติดต่อ IT' }
                            ]
                        }
                    ]
                },
                { id: 15, cause_id: 18, type: 'solution', text: 'ทำการอัปเดตโปรแกรมให้เป็นเวอร์ชันล่าสุด', image: 'https://placehold.co/600x400/8b5cf6/FFFFFF?text=Update+Software'},
                { 
                    id: 16, cause_id: 19, type: 'question', title: 'STEP 1: ตรวจสอบสาย Power', text: 'ตรวจสอบปลั๊กไฟและสาย Power ว่าเสียบแน่น', image: 'https://placehold.co/600x400/ec4899/FFFFFF?text=Step+1:+Check+Power',
                    next_level_nodes: [
                        { id: 16001, type: 'solution', title: 'หาย', text: 'ปัญหาเกิดจากสายไฟหลวม' },
                        { id: 16002, type: 'question', title: 'ไม่หาย', text: 'STEP 2: ลองเปลี่ยนปลั๊กไฟ', image: 'https://placehold.co/600x400/ec4899/FFFFFF?text=Step+2:+Change+Outlet',
                            next_level_nodes: [
                                { id: 16003, type: 'solution', title: 'หาย', text: 'ปัญหาเกิดจากปลั๊กไฟเดิม' },
                                { id: 16004, type: 'contact', title: 'ไม่หาย', text: 'Power Supply อาจเสีย, ติดต่อ IT' }
                            ]
                        }
                    ]
                },
                { 
                    id: 17, cause_id: 20, type: 'question', title: 'STEP 1: ลบอีเมลในถังขยะ', text: 'ลบอีเมลที่ไม่จำเป็นในถังขยะ (Trash) ทั้งหมด', image: 'https://placehold.co/600x400/f97316/FFFFFF?text=Step+1:+Empty+Trash',
                    next_level_nodes: [
                        { id: 17001, type: 'solution', title: 'หาย', text: 'ได้พื้นที่กลับคืนมาเพียงพอ' },
                        { id: 17002, type: 'question', title: 'ไม่หาย', text: 'STEP 2: ลบอีเมลใน Sent Items', image: 'https://placehold.co/600x400/f97316/FFFFFF?text=Step+2:+Clean+Sent+Items',
                            next_level_nodes: [
                                { id: 17003, type: 'solution', title: 'หาย', text: 'ได้พื้นที่กลับคืนมาเพียงพอ' },
                                { id: 17004, type: 'contact', title: 'ไม่หาย', text: 'จำเป็นต้อง Archive หรือขอขยายพื้นที่, ติดต่อ IT' }
                            ]
                        }
                    ]
                },
                { 
                    id: 18, cause_id: 21, type: 'question', title: 'STEP 1: ตรวจสอบปลั๊ก Adapter', text: 'ตรวจสอบ Adapter ของ Router และเสียบปลั๊กไฟให้แน่น', image: 'https://placehold.co/600x400/ef4444/FFFFFF?text=Step+1:+Check+Adapter',
                    next_level_nodes: [
                        { id: 18001, type: 'solution', title: 'หาย', text: 'ปัญหาเกิดจากปลั๊กหลวม' },
                        { id: 18002, type: 'question', title: 'ไม่หาย', text: 'STEP 2: ลองเปลี่ยนปลั๊กไฟ', image: 'https://placehold.co/600x400/ef4444/FFFFFF?text=Step+2:+Change+Outlet',
                            next_level_nodes: [
                                { id: 18003, type: 'solution', title: 'หาย', text: 'ปัญหาเกิดจากปลั๊กไฟเดิม' },
                                { id: 18004, type: 'contact', title: 'ไม่หาย', text: 'Adapter หรือ Router อาจเสีย, ติดต่อ IT' }
                            ]
                        }
                    ]
                },
                { 
                    id: 19, cause_id: 22, type: 'question', title: 'STEP 1: ติดตั้ง C++ Redistributable', text: 'ทำการติดตั้ง Microsoft Visual C++ Redistributable', image: 'https://placehold.co/600x400/8b5cf6/FFFFFF?text=Step+1:+Install+C%2B%2B',
                    next_level_nodes: [
                        { id: 19001, type: 'solution', title: 'หาย', text: 'โปรแกรมต้องการไฟล์ดังกล่าวในการทำงาน' },
                        { id: 19002, type: 'question', title: 'ไม่หาย', text: 'STEP 2: ติดตั้งโปรแกรมใหม่', image: 'https://placehold.co/600x400/8b5cf6/FFFFFF?text=Step+2:+Reinstall',
                            next_level_nodes: [
                                { id: 19003, type: 'solution', title: 'หาย', text: 'ไฟล์โปรแกรมเดิมอาจเสียหาย' },
                                { id: 19004, type: 'contact', title: 'ไม่หาย', text: 'จำเป็นต้องให้ IT Support ตรวจสอบ' }
                            ]
                        }
                    ]
                },
                { 
                    id: 20, cause_id: 23, type: 'question', title: 'STEP 1: ตรวจสอบสายสัญญาณจอ', text: 'ตรวจสอบสายสัญญาณจอ (VGA/HDMI) ว่าเสียบแน่น', image: 'https://placehold.co/600x400/ec4899/FFFFFF?text=Step+1:+Check+Cable',
                    next_level_nodes: [
                        { id: 20001, type: 'solution', title: 'หาย', text: 'ปัญหาเกิดจากสายสัญญาณหลวม' },
                        { id: 20002, type: 'question', title: 'ไม่หาย', text: 'STEP 2: ตรวจสอบว่าจอเปิดอยู่', image: 'https://placehold.co/600x400/ec4899/FFFFFF?text=Step+2:+Check+Monitor+Power',
                            next_level_nodes: [
                                { id: 20003, type: 'solution', title: 'หาย', text: 'จอภาพอาจปิดอยู่หรืออยู่ในโหมด Sleep' },
                                { id: 20004, type: 'contact', title: 'ไม่หาย', text: 'สาย, จอ, หรือการ์ดจออาจมีปัญหา, ติดต่อ IT' }
                            ]
                        }
                    ]
                }
            ]
        };

        // --- DOM Element References ---
        const flowchartContainer = document.getElementById('itsmainmn-flowchart-main-container');
        const svgCanvas = document.getElementById('itsmainmn-connector-svg');
        const solutionModal = document.getElementById('itsmainmn-solution-modal');
        const contactModal = document.getElementById('itsmainmn-contact-modal');
        const searchInput = document.getElementById('itsmainmn-search-input');
        const searchResultsContainer = document.getElementById('itsmainmn-search-results-container');
        const contactFab = document.getElementById('itsmainmn-contact-fab');
        
        const imagePopup = document.getElementById('itsmainmn-image-popup');
        const imagePopupImg = imagePopup ? imagePopup.querySelector('img') : null;
        let searchIndex = [];

        /**
         * Shows the image preview popup.
         * @param {string} imageUrl - The URL of the image to display.
         */
        const showImagePopup = (imageUrl) => {
            if (!imagePopup || !imagePopupImg) return;
            imagePopupImg.src = imageUrl;
            imagePopup.classList.add('itsmainmn-show');
        };

        /**
         * Hides the image preview popup.
         */
        const hideImagePopup = () => {
            if (!imagePopup) return;
            imagePopup.classList.remove('itsmainmn-show');
        };
        
        if (imagePopup) {
            imagePopup.addEventListener('click', (e) => {
                if (e.target === imagePopup) {
                    hideImagePopup();
                }
            });
        }

        /**
         * Builds a flat array from the nested data for easy searching.
         */
        const buildSearchIndex = () => {
            searchIndex = [];
            dbData.titles.forEach(title => {
                searchIndex.push({ text: title.title, type: 'หัวข้อ', path: [title.id], parentText: '' });
            });
            dbData.causes.forEach(cause => {
                const parentTitle = dbData.titles.find(t => t.id === cause.title_id);
                if (parentTitle) {
                    searchIndex.push({ text: cause.title, type: 'สาเหตุ', path: [parentTitle.id, cause.id], parentText: `ใน: ${parentTitle.title}` });
                }
            });
        };
        
        /**
         * Displays a modal with a fade-in effect.
         * @param {HTMLElement} modal - The modal element to show.
         */
        const showModal = (modal) => {
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('itsmainmn-show'), 10);
        };
        
        /**
         * Hides a modal with a fade-out effect.
         * @param {HTMLElement} modal - The modal element to hide.
         */
        const hideModal = (modal) => {
            modal.classList.remove('itsmainmn-show');
            setTimeout(() => modal.style.display = 'none', 300);
        };

        // --- Event Listeners for Modals and FAB ---
        [solutionModal, contactModal].forEach(modal => {
            if (!modal) return;
            modal.querySelectorAll('.itsmainmn-close-btn').forEach(btn => btn.addEventListener('click', () => hideModal(modal)));
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    hideModal(modal);
                }
            });
        });

        if (contactFab) {
            contactFab.addEventListener('click', () => showModal(contactModal));
        }

        /**
         * Creates a DOM element for a flowchart node.
         * @param {object} item - The data object for the node.
         * @param {number} level - The depth level of the node in the flowchart.
         * @returns {HTMLElement} The created node element.
         */
        const createNode = (item, level) => {
            const node = document.createElement('div');
            node.className = 'itsmainmn-flowchart-node';
            node.dataset.id = item.id;
            node.dataset.level = level;
            node.style.setProperty('--hover-color', item.color || 'transparent');
            if (level === 0) node.style.borderTopColor = item.color;
            
            const iconWrench = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.471-2.471a.563.563 0 01.796 0l.473.473a.563.563 0 010 .796l-2.471 2.471m-4.588-4.588l2.471-2.471a.563.563 0 01.796 0l.473.473a.563.563 0 010 .796l-2.471 2.471m-2.135 2.135L6.176 17.25a.563.563 0 01-.796 0l-.473-.473a.563.563 0 010-.796l2.471-2.471" /></svg>';
            const iconProblem = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>';
            const iconQuestion = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zM12 15.75h.008v.008H12v-.008z" /></svg>';
            const iconContact = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg>';
            let iconHtml = '';

            if (level === 0) iconHtml = item.icon;
            else if (level === 1) iconHtml = iconProblem;
            else {
                switch(item.type) {
                    case 'question': iconHtml = iconQuestion; break;
                    case 'solution': iconHtml = iconWrench; break;
                    case 'contact': iconHtml = iconContact; break;
                    default: iconHtml = iconWrench;
                }
            }

            const title = item.title || item.text;
            const text = item.title ? item.text : '';
            node.innerHTML = `<div class="icon">${iconHtml}</div><div class="flowchart-node-text"><h3>${title}</h3><p>${text || ''}</p></div>`;

            if (item.image && level > 0) {
                const imageIcon = document.createElement('span');
                imageIcon.className = 'itsmainmn-node-image-icon';
                imageIcon.title = 'ดูรูปภาพประกอบ';
                imageIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>`;
                
                imageIcon.addEventListener('click', (e) => {
                    e.stopPropagation(); 
                    showImagePopup(item.image);
                });
                
                node.appendChild(imageIcon);
            }
            
            node.addEventListener('click', () => handleNodeClick(node, item, level));
            return node;
        }
        
        /**
         * Handles the logic when a flowchart node is clicked.
         * @param {HTMLElement} nodeEl - The clicked node element.
         * @param {object} item - The data object for the clicked node.
         * @param {number} level - The level of the clicked node.
         */
        const handleNodeClick = (nodeEl, item, level) => {
            hideImagePopup();
            const wasActive = nodeEl.classList.contains('active');

            let currentLevel = level + 1;
            let nextLevelEl;
            while ((nextLevelEl = document.getElementById(`level-${currentLevel}`))) {
                nextLevelEl.remove();
                currentLevel++;
            }

            nodeEl.parentElement.querySelectorAll('.itsmainmn-flowchart-node').forEach(n => n.classList.remove('itsmainmn-active'));

            if (wasActive) {
                setTimeout(drawAllConnectors, 50);
                return;
            }

            nodeEl.classList.add('itsmainmn-active');

            let childrenData = [];
            if (item.next_level_nodes) {
                childrenData = item.next_level_nodes;
            } else if (level === 0) {
                childrenData = dbData.causes.filter(c => c.title_id === item.id);
            } else if (level === 1) {
                childrenData = dbData.solutions.filter(s => s.cause_id === item.id);
            }

            if (childrenData && childrenData.length > 0) {
                const childrenContainer = document.createElement('div');
                childrenContainer.className = 'itsmainmn-flowchart-level';
                childrenContainer.id = `level-${level + 1}`;
                const parentColor = getComputedStyle(nodeEl).getPropertyValue('--hover-color');

                childrenData.forEach((child, index) => {
                    const childNode = createNode(child, level + 1);
                    childNode.style.setProperty('--hover-color', parentColor);
                    childNode.style.borderTopColor = parentColor;
                    childNode.style.animationDelay = `${index * 50}ms`;
                    childrenContainer.appendChild(childNode);
                });
                
                flowchartContainer.appendChild(childrenContainer);

                setTimeout(() => {
                    const parentRect = nodeEl.getBoundingClientRect();
                    const containerRect = flowchartContainer.getBoundingClientRect();
                    const childrenHeight = childrenContainer.offsetHeight;
                    const parentCenterY = (parentRect.top - containerRect.top) + (parentRect.height / 2) + flowchartContainer.scrollTop;
                    
                    const newTop = parentCenterY - (childrenHeight / 2);
                    const adjustedTop = Math.max(flowchartContainer.scrollTop, newTop);
                    childrenContainer.style.top = `${adjustedTop}px`;
                    
                    drawAllConnectors();
                    
                    setTimeout(() => {
                        childrenContainer.classList.add('itsmainmn-visible');
                        childrenContainer.scrollIntoView({ behavior: 'smooth', inline: 'end', block: 'nearest' });
                    }, 50);
                }, 50);
            } else {
                const itemType = item.type || 'solution';
                if (itemType === 'solution') {
                    document.getElementById('solution-title').textContent = item.title || 'วิธีแก้ไขปัญหา';
                    document.getElementById('solution-text').textContent = item.text;
                    showModal(solutionModal);
                } else if (itemType === 'contact') {
                    showModal(contactModal);
                }
                setTimeout(drawAllConnectors, 50);
            }
        };

        /**
         * Redraws all connectors between active nodes.
         */
        const drawAllConnectors = () => {
            if (!svgCanvas) return;
            svgCanvas.innerHTML = '';
            const levels = Array.from(flowchartContainer.children).filter(el => el.classList.contains('itsmainmn-flowchart-level'));
            for (let i = 0; i < levels.length - 1; i++) {
                const parentNode = levels[i].querySelector('.itsmainmn-flowchart-node.itsmainmn-active');
                if (parentNode) {
                    const parentColor = getComputedStyle(parentNode).getPropertyValue('--hover-color');
                    const nextLevel = levels[i+1];
                    if(nextLevel) {
                        nextLevel.querySelectorAll('.itsmainmn-flowchart-node').forEach(childNode => {
                            drawConnector(parentNode, childNode, parentColor);
                        });
                    }
                }
            }
        }

        /**
         * Draws a single bezier curve connector between two nodes.
         * @param {HTMLElement} parentEl - The starting node element.
         * @param {HTMLElement} childEl - The ending node element.
         * @param {string} color - The color of the connector line.
         */
        const drawConnector = (parentEl, childEl, color) => {
            const containerRect = flowchartContainer.getBoundingClientRect();
            const parentRect = parentEl.getBoundingClientRect();
            const childRect = childEl.getBoundingClientRect();

            const startX = (parentRect.right - containerRect.left);
            const startY = (parentRect.top + parentRect.height / 2 - containerRect.top);
            const endX = (childRect.left - containerRect.left);
            const endY = (childRect.top + childRect.height / 2 - containerRect.top);
            
            const controlX1 = startX + (endX - startX) * 0.5;
            const controlX2 = endX - (endX - startX) * 0.5;
            
            const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
            path.setAttribute("d", `M ${startX} ${startY} C ${controlX1} ${startY}, ${controlX2} ${endY}, ${endX} ${endY}`);
            path.classList.add('itsmainmn-animated');
            path.style.stroke = color || 'var(--connector-color)';
            svgCanvas.appendChild(path);
        }

        /**
         * Initializes or resets the flowchart to its initial state.
         */
        const initFlowchart = () => {
            hideImagePopup();
            flowchartContainer.innerHTML = ''; 
            flowchartContainer.appendChild(svgCanvas);

            const level0 = document.createElement('div');
            level0.className = 'itsmainmn-flowchart-level';
            level0.id = 'level-0';
            flowchartContainer.appendChild(level0);

            dbData.titles.forEach((title, index) => {
                const node = createNode(title, 0);
                node.style.animationDelay = `${index * 50}ms`;
                level0.appendChild(node);
            });
            setTimeout(() => level0.classList.add('itsmainmn-visible'), 50);
        }
        
        // --- Search Functionality ---
        const handleSearch = (event) => {
            const query = event.target.value.toLowerCase();
            searchResultsContainer.innerHTML = '';

            if (query.length < 2) {
                return;
            }

            const results = searchIndex.filter(item => item.text.toLowerCase().includes(query));

            results.forEach(item => {
                const resultEl = document.createElement('div');
                resultEl.className = 'itsmainmn-search-result-item';
                resultEl.innerHTML = `<strong>${item.text}</strong><span>${item.parentText}</span>`;
                resultEl.addEventListener('click', () => {
                    buildFlowchartToNode(item.path);
                    searchInput.value = '';
                    searchResultsContainer.innerHTML = '';
                });
                searchResultsContainer.appendChild(resultEl);
            });
        };

        const buildFlowchartToNode = async (path) => {
            initFlowchart();
            await new Promise(r => setTimeout(r, 100));

            for (const nodeId of path) {
                const nodeToClick = flowchartContainer.querySelector(`.itsmainmn-flowchart-node[data-id='${nodeId}']`);
                if (nodeToClick) {
                    nodeToClick.click();
                    await new Promise(r => setTimeout(r, 600)); 
                } else {
                    console.error("Node not found in path:", nodeId);
                    break;
                }
            }
        };

        searchInput.addEventListener('input', handleSearch);
        document.addEventListener('click', (e) => {
            if (!searchResultsContainer.contains(e.target) && e.target !== searchInput) {
                searchResultsContainer.innerHTML = '';
            }
        });

        // --- Initial Setup ---
        buildSearchIndex();
        initFlowchart();
        new ResizeObserver(drawAllConnectors).observe(flowchartContainer);
    });
    </script>

<?php include_once __DIR__ . '/inc/footer.php'; ?>


