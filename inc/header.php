<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Support Center - Flowchart</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

    <header class="itsmainmn-header">
        <div class="itsmainmn-header-left">
            <div class="itsmainmn-logo-wrapper">
                <img src="assets/img/logo.svg" alt="IT Support Logo" class="itsmainmn-header-logo" style="height:40px;max-width:120px;object-fit:contain;" onerror="this.src='https://placehold.co/120x40/dc2626/FFFFFF?text=Logo'">
            </div>
            <span class="itsmainmn-header-title">IT Support Center</span>
        </div>
        <div class="itsmainmn-header-search" style="position:relative;">
            <input type="text" id="itsmainmn-search-input" placeholder="ค้นหาชื่อปัญหา...">
            <div id="itsmainmn-search-results-container" style="position:absolute;top:110%;left:0;width:100%;background:#fff;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,0.13);max-height:340px;overflow-y:auto;z-index:2000;border:1px solid #e2e8f0;margin-top:4px;display:none;"></div>
        </div>
        <script>
        // --- Problem Search in Header ---
        document.addEventListener('DOMContentLoaded', function() {
            // Make dbData available globally when loaded
            function getDbData() {
                return window.dbData && window.dbData.causes ? window.dbData : null;
            }
            
            // Expose global buildFlowchartToNode function for header search
            window.buildFlowchartToNode = async (path) => {
                const flowchartContainer = document.getElementById('itsmainmn-flowchart-main-container');
                if (!flowchartContainer) return;
                
                // Reset flowchart first
                if (window.initFlowchart) {
                    window.initFlowchart();
                }
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
            
            const searchInput = document.getElementById('itsmainmn-search-input');
            const searchResultsContainer = document.getElementById('itsmainmn-search-results-container');
            if (!searchInput || !searchResultsContainer) return;
            
            searchInput.addEventListener('input', function(e) {
                const dbData = getDbData();
                if (!dbData) return;
                const query = e.target.value.trim().toLowerCase();
                searchResultsContainer.innerHTML = '';
                if (query.length < 2) {
                    searchResultsContainer.style.display = 'none';
                    return;
                }
                // Find matching problems (causes)
                const results = dbData.causes.filter(cause => (cause.title || '').toLowerCase().includes(query));
                if (results.length === 0) {
                    searchResultsContainer.style.display = 'none';
                    return;
                }
                results.forEach(cause => {
                    // Find parent title
                    const parentTitle = dbData.titles.find(t => t.id === cause.title_id);
                    const parentText = parentTitle ? parentTitle.title : '';
                    const el = document.createElement('div');
                    el.className = 'itsmainmn-search-result-item';
                    el.style.padding = '0.85rem 1.1rem';
                    el.style.cursor = 'pointer';
                    el.style.borderBottom = '1px solid #f0f0f0';
                    el.style.transition = 'background 0.18s';
                    el.onmouseover = () => { el.style.background = '#f3f6fa'; };
                    el.onmouseout = () => { el.style.background = '#fff'; };
                    el.innerHTML = `<strong>${cause.title}</strong><span style='color:#888; font-size:13px; margin-left:8px;'>${parentText ? 'ใน: ' + parentText : ''}</span>`;
                    el.addEventListener('mousedown', function(ev) {
                        // Use global buildFlowchartToNode
                        window.buildFlowchartToNode([cause.title_id, cause.id]);
                        searchInput.value = '';
                        searchResultsContainer.innerHTML = '';
                        searchResultsContainer.style.display = 'none';
                        ev.preventDefault();
                    });
                    searchResultsContainer.appendChild(el);
                });
                searchResultsContainer.style.display = 'block';
            });
            document.addEventListener('mousedown', function(e) {
                if (!searchResultsContainer.contains(e.target) && e.target !== searchInput) {
                    searchResultsContainer.style.display = 'none';
                }
            });
        });
        </script>
    </header>