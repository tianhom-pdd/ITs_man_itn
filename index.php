<?php include_once __DIR__ . '/inc/header.php'; ?>



<main class="itsmainmn-page-container">
    <div id="itsmainmn-flowchart-main-container">
        <svg id="itsmainmn-connector-svg"></svg>
    </div>
</main>

<!-- SVG Icon Definitions -->
<svg style="display: none;" xmlns="http://www.w3.org/2000/svg">
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

<div class="itsmainmn-modal-overlay" id="itsmainmn-solution-modal">
    <div class="itsmainmn-modal-content">
        <div class="itsmainmn-modal-header">
            <h2 class="itsmainmn-        /**
         * Draws a connector line between two nodes.
         * @param {HTMLElement} parentEl - The starting node element.
         * @param {HTMLElement} childEl - The ending node element.
         */
        const drawConnector = (parentEl, childEl) => {itle">
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
        let dbData = { titles: [], causes: [], conclusions: [] };

        // ดึงข้อมูล flowchart จากฐานข้อมูลจริง
        const loadFlowchartData = () => {
            return fetch('inc/flowchart_data.php')
                .then(response => response.json())
                .then(data => {
                    dbData = data;
                    window.dbData = data; // Make available globally for header search
                    console.log('dbData loaded:', dbData);
                    buildSearchIndex();
                    return data;
                })
                .catch(err => {
                    console.error('โหลดข้อมูล flowchart ไม่สำเร็จ:', err);
                    throw err;
                });
        };

        // Load initial data
        loadFlowchartData().then(() => {
            initFlowchart();
        });

        // --- DOM Element References ---
        const flowchartContainer = document.getElementById('itsmainmn-flowchart-main-container');
        const pageContainer = document.querySelector('.itsmainmn-page-container');
        const svgCanvas = document.getElementById('itsmainmn-connector-svg');
        const solutionModal = document.getElementById('itsmainmn-solution-modal');
        const contactModal = document.getElementById('itsmainmn-contact-modal');
        const searchInput = document.getElementById('itsmainmn-search-input');
        const searchResultsContainer = document.getElementById('itsmainmn-search-results-container');
        const contactFab = document.getElementById('itsmainmn-contact-fab');
        const imagePopup = document.getElementById('itsmainmn-image-popup');


        const imagePopupImg = imagePopup ? imagePopup.querySelector('img') : null;
        const problemSearchInput = document.getElementById('itsmainmn-problem-search-input');
        const problemSearchResults = document.getElementById('itsmainmn-problem-search-results');
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
        // --- Problem Search (by cause_it.title) ---
        if (problemSearchInput && problemSearchResults) {
            problemSearchInput.addEventListener('input', function(e) {
                const query = e.target.value.trim().toLowerCase();
                problemSearchResults.innerHTML = '';
                if (query.length < 2) {
                    problemSearchResults.style.display = 'none';
                    return;
                }
                // Find matching problems (causes)
                const results = dbData.causes.filter(cause => (cause.title || '').toLowerCase().includes(query));
                if (results.length === 0) {
                    problemSearchResults.style.display = 'none';
                    return;
                }
                results.forEach(cause => {
                    // Find parent title
                    const parentTitle = dbData.titles.find(t => t.id === cause.title_id);
                    const parentText = parentTitle ? parentTitle.title : '';
                    const el = document.createElement('div');
                    el.className = 'itsmainmn-search-result-item';
                    el.style.padding = '10px 14px';
                    el.style.cursor = 'pointer';
                    el.style.fontSize = '15px';
                    el.style.borderBottom = '1px solid #f0f0f0';
                    el.style.transition = 'background 0.15s';
                    el.onmouseover = () => { el.style.background = '#f5f7fa'; };
                    el.onmouseout = () => { el.style.background = '#fff'; };
                    el.innerHTML = `<strong style='color:#2d5be3;'>${cause.title}</strong><span style='color:#888; font-size:13px; margin-left:8px;'>${parentText ? 'ใน: ' + parentText : ''}</span>`;
                    el.addEventListener('mousedown', function(ev) {
                        // Build flowchart to parent, then click this node
                        buildFlowchartToNode([cause.title_id, cause.id]);
                        problemSearchInput.value = '';
                        problemSearchResults.innerHTML = '';
                        problemSearchResults.style.display = 'none';
                        ev.preventDefault();
                    });
                    problemSearchResults.appendChild(el);
                });
                problemSearchResults.style.display = 'block';
            });
            document.addEventListener('mousedown', function(e) {
                if (!problemSearchResults.contains(e.target) && e.target !== problemSearchInput) {
                    problemSearchResults.style.display = 'none';
                }
            });
        }
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

            // ไอคอน
            const iconWrench = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.471-2.471a.563.563 0 01.796 0l.473.473a.563.563 0 010 .796l-2.471 2.471m-4.588-4.588l2.471-2.471a.563.563 0 01.796 0l.473.473a.563.563 0 010 .796l-2.471 2.471m-2.135 2.135L6.176 17.25a.563.563 0 01-.796 0l-.473-.473a.563.563 0 010-.796l2.471-2.471" /></svg>';
            const iconProblem = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>';
            const iconQuestion = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zM12 15.75h.008v.008H12v-.008z" /></svg>';
            const iconContact = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg>';

            // Create icon div
            const iconDiv = document.createElement('div');
            iconDiv.className = 'icon';
            
            if (level === 0) {
                // For level 0, use the SVG from database directly
                if (item.icon && item.icon.trim()) {
                    console.log('Loading icon for', item.title, ':', item.icon);
                    iconDiv.innerHTML = item.icon;
                } else {
                    console.log('No icon found for', item.title, ', using fallback');
                    iconDiv.innerHTML = iconWrench; // fallback icon
                }
            } else if (level === 1) {
                iconDiv.innerHTML = iconProblem;
            } else {
                switch(item.type) {
                    case 'question': iconDiv.innerHTML = iconQuestion; break;
                    case 'solution': iconDiv.innerHTML = iconWrench; break;
                    case 'contact': iconDiv.innerHTML = iconContact; break;
                    default: iconDiv.innerHTML = iconWrench;
                }
            }
            
            node.appendChild(iconDiv);

            const title = item.title || item.text;
            const text = item.title ? item.text : '';
            // Add text content for all levels
            const textDiv = document.createElement('div');
            textDiv.className = 'flowchart-node-text';
            textDiv.innerHTML = `<h3>${title}</h3><p>${text || ''}</p>`;
            node.appendChild(textDiv);

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
            // Activate connector lights
            activateConnectorLights(nodeEl);
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
                setTimeout(() => {
                    drawAllConnectors();
                }, 50);
                return;
            }

            nodeEl.classList.add('itsmainmn-active');
            
            // Activate light trail effect for connectors leading to this node
            setTimeout(() => {
                activateConnectorLights(nodeEl);
            }, 200);

            // --- ปรับตรงนี้ให้แสดงลูกโหนดถูกต้องตาม parent_id ---
            let childrenData = [];
            if (level === 0) {
                // ลูกของ title คือ causes ที่ title_id ตรงกับ id
                childrenData = dbData.causes.filter(c => c.title_id == item.id && (c.parent_id === null || c.parent_id === "" || c.parent_id == 0));
            } else {
                // ลูกของ cause คือ causes ที่ parent_id ตรงกับ id นี้
                childrenData = dbData.causes.filter(c => c.parent_id == item.id);
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

                    drawAllConnectors(); // Draw connectors for new nodes

                    setTimeout(() => {
                        childrenContainer.classList.add('itsmainmn-visible');
                        childrenContainer.scrollIntoView({ behavior: 'smooth', inline: 'end', block: 'nearest' });
                    }, 50);
                }, 50);
            } else {
                // leaf node: แสดง modal สรุปหรือ contact
                const itemType = item.type || 'solution';
                if (itemType === 'solution') {
                    document.getElementById('solution-title').textContent = item.title || 'วิธีแก้ไขปัญหา';
                    document.getElementById('solution-text').textContent = item.text;
                    showModal(solutionModal);
                } else if (itemType === 'contact') {
                    showModal(contactModal);
                }
                setTimeout(() => {
                    drawAllConnectors();
                }, 50);
            }
        };

        /**
         * Redraws all connectors between active nodes.
         * @param {boolean} withLightEffect - Whether to add light trail effect to new connectors.
         */
        const drawAllConnectors = (withLightEffect = false) => {
            if (!svgCanvas) return;
            
            // Always clear and redraw for consistency
            svgCanvas.innerHTML = '';
            
            const levels = Array.from(flowchartContainer.children).filter(el => el.classList.contains('itsmainmn-flowchart-level'));
            for (let i = 0; i < levels.length - 1; i++) {
                const parentNode = levels[i].querySelector('.itsmainmn-flowchart-node.itsmainmn-active');
                if (parentNode) {
                    const nextLevel = levels[i+1];
                    if(nextLevel) {
                        nextLevel.querySelectorAll('.itsmainmn-flowchart-node').forEach(childNode => {
                            drawConnector(parentNode, childNode);
                        });
                    }
                }
            }
        }

        /**
         * Draws a single bezier curve connector between two nodes.
         * @param {HTMLElement} parentEl - The starting node element.
         * @param {HTMLElement} childEl - The ending node element.
         */
        const drawConnector = (parentEl, childEl) => {
            const containerRect = flowchartContainer.getBoundingClientRect();
            const parentRect = parentEl.getBoundingClientRect();
            const childRect = childEl.getBoundingClientRect();

            const startX = (parentRect.right - containerRect.left);
            const startY = (parentRect.top + parentRect.height / 2 - containerRect.top);
            const endX = (childRect.left - containerRect.left);
            const endY = (childRect.top + childRect.height / 2 - containerRect.top);
            
            const controlX1 = startX + (endX - startX) * 0.5;
            const controlX2 = endX - (endX - startX) * 0.5;
            const pathData = `M ${startX} ${startY} C ${controlX1} ${startY}, ${controlX2} ${endY}, ${endX} ${endY}`;
            
            // Get parent color
            const parentColor = getComputedStyle(parentEl).getPropertyValue('--hover-color') || '#dc2626';
            
            // Main connector path
            const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
            path.setAttribute("d", pathData);
            path.classList.add('animated');
            path.style.stroke = parentColor;
            
            // Add data attributes for later reference
            path.dataset.parentId = parentEl.dataset.id;
            path.dataset.childId = childEl.dataset.id;
            path.dataset.pathData = pathData;
            path.dataset.parentColor = parentColor;
            
            svgCanvas.appendChild(path);
        }

        /**
         * Activates highlighting on connectors leading to a specific node.
         * @param {HTMLElement} targetNode - The target node element.
         */
        const activateConnectorLights = (targetNode) => {
            const targetId = targetNode.dataset.id;
            const allPaths = svgCanvas.querySelectorAll('path');
            
            // Reset all paths to normal state
            allPaths.forEach(path => {
                path.classList.remove('active-connector');
                // Reset to original color
                const originalColor = path.dataset.parentColor || '#dc2626';
                path.style.stroke = originalColor;
                path.style.strokeWidth = '2.5px';
                path.style.filter = 'none';
            });
            
            // Find and activate paths leading to this node
            allPaths.forEach(path => {
                if (path.dataset.childId === targetId) {
                    const parentColor = path.dataset.parentColor || '#dc2626';
                    
                    // Create lighter version of the parent color for active state
                    const lighterColor = lightenColor(parentColor, 20);
                    
                    path.classList.add('active-connector');
                    path.style.stroke = lighterColor;
                    path.style.strokeWidth = '3px';
                    path.style.filter = `drop-shadow(0 0 3px ${parentColor}80)`;
                }
            });
        };
        
        /**
         * Helper function to lighten a hex color
         * @param {string} color - Hex color string
         * @param {number} amount - Amount to lighten (0-100)
         * @returns {string} Lightened hex color
         */
        const lightenColor = (color, amount) => {
            const num = parseInt(color.replace("#", ""), 16);
            const amt = Math.round(2.55 * amount);
            const R = (num >> 16) + amt;
            const G = (num >> 8 & 0x00FF) + amt;
            const B = (num & 0x0000FF) + amt;
            return "#" + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
                (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
                (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
        };

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
        
        // Make functions available globally
        window.initFlowchart = initFlowchart;
        window.loadFlowchartData = loadFlowchartData;
        
        // Refresh data when page becomes visible (user returns from admin)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                // Page became visible, reload data in case it was changed in admin
                loadFlowchartData().then(() => {
                    initFlowchart();
                });
            }
        });
        
        // Also refresh when window gains focus
        window.addEventListener('focus', function() {
            loadFlowchartData().then(() => {
                initFlowchart();
            });
        });
        
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
        new ResizeObserver(drawAllConnectors).observe(flowchartContainer);
    });
    </script>

<?php include_once __DIR__ . '/inc/footer.php'; ?>


