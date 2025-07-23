<?php include_once __DIR__ . '/inc/header.php'; ?>



<main class="itsmainmn-page-container">
    <div id="itsmainmn-flowchart-main-container">
        <svg id="itsmainmn-connector-svg"></svg>
    </div>
</main>

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
        fetch('inc/flowchart_data.php')
            .then(response => response.json())
            .then(data => {
                dbData = data;
                window.dbData = data; // Make available globally for header search
                console.log('dbData:', dbData);
                buildSearchIndex();
                initFlowchart();
            })
            .catch(err => {
                console.error('โหลดข้อมูล flowchart ไม่สำเร็จ:', err);
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

            let iconHtml = '';
            if (level === 0) {
                // Render SVG markup as HTML, not as text
                const iconDiv = document.createElement('div');
                iconDiv.className = 'icon';
                iconDiv.innerHTML = item.icon || '';
                node.appendChild(iconDiv);
            } else if (level === 1) {
                iconHtml = iconProblem;
                node.innerHTML = `<div class="icon">${iconHtml}</div>`;
            } else {
                switch(item.type) {
                    case 'question': iconHtml = iconQuestion; break;
                    case 'solution': iconHtml = iconWrench; break;
                    case 'contact': iconHtml = iconContact; break;
                    default: iconHtml = iconWrench;
                }
                node.innerHTML = `<div class="icon">${iconHtml}</div>`;
            }

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
        
        // Make initFlowchart available globally for header search
        window.initFlowchart = initFlowchart;
        
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


