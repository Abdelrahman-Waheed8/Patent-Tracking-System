(function(){
        // Utility to show/hide modal
        const modal = document.getElementById('review-modal');
        const detailsContainer = document.getElementById('review-details');
        function showModal(html){
            detailsContainer.innerHTML = html;
            modal.style.display = 'flex';
        }
        function hideModal(){
            detailsContainer.innerHTML = '';
            modal.style.display = 'none';
        }

        // Build details HTML from table row
        function buildDetailsFromRow(row){
            const cells = Array.from(row.querySelectorAll('td'));
            const headers = ['Application ID','Disclosure ID','Application Number','Filing Date','Status'];
            let html = '<div class="modal-body">';
            html += '<table class="modal-table">';
            for(let i=0;i<Math.min(headers.length,cells.length);i++){
                const val = cells[i] ? cells[i].textContent.trim() : '';
                html += `<tr><th>${headers[i]}:</th><td>${escapeHtml(val)}</td></tr>`;
            }
            html += '</table>';
            html += '</div>';
            return html;
        }

        function escapeHtml(s){
            return (s||'')
                .replace(/&/g,'&amp;')
                .replace(/</g,'&lt;')
                .replace(/>/g,'&gt;')
                .replace(/"/g,'&quot;')
                .replace(/'/g,'&#039;');
        }

        // Handler for review buttons
        document.addEventListener('click', function(e){
            const btn = e.target.closest('button.review-btn, [data-action="review"]');
            if(!btn) return;
            const row = btn.closest('tr');
            if(!row) return;

            const cells = row.querySelectorAll('td');
            const appId = cells[0] ? cells[0].textContent.trim() : '';
            const statusText = (cells[4] ? cells[4].textContent.trim() : (btn.dataset.status||'')).toLowerCase();
            const detailsHtml = buildDetailsFromRow(row);

            if(statusText.includes('reject')){
                // Rejected modal: show details, radio actions and textarea for argument
                let html = detailsHtml;
                html += '<div class="ipc-actions">';
                html += '<form id="reject-form" action="../../src/ipcounsel.php" method="POST">';
                html += '<div><label><input type="radio" name="ipc_action" value="accept"> Accept rejection</label></div>';
                html += '<div><label><input type="radio" name="ipc_action" value="argue" checked> Argue with examiner</label></div>';
                html += '<div id="argument-wrap" style="margin-top:8px;">';
                html += '<label for="argument_text">State why the examiner is wrong:</label><br>';
                html += '<textarea id="argument_text" name="argument_text" rows="4" style="width:100%" placeholder="Type your argument here..."></textarea>';
                html += '</div>';
                html += '<div style="margin-top:10px; text-align:right;">';
                html += '<button type="submit" id="submit-argument" class="btn primary">Submit Argument</button> ';
                html += '<button type="button" id="cancel-review" class="btn">Cancel</button>';
                html += '</div>';
                html += '</form></div>';

                showModal(html);

                // Event bindings
                const submitBtn = document.getElementById('submit-argument');
                const cancelBtn = document.getElementById('cancel-review');
                const forms = document.getElementById('reject-form');
                const argumentText = document.getElementById('argument_text');

                // Toggle textarea visibility depending on radio
                forms.querySelectorAll('input[name="ipc_action"]').forEach(function(r){
                    r.addEventListener('change', function(){
                        const wrap = document.getElementById('argument-wrap');
                        if(this.value === 'argue') wrap.style.display = '';
                        else wrap.style.display = 'none';
                    });
                });

                cancelBtn.addEventListener('click', function(){ hideModal(); });

                submitBtn.addEventListener('click', function(){
                    const action = forms.querySelector('input[name="ipc_action"]:checked').value;
                    const argument = (argumentText && argumentText.value) ? argumentText.value.trim() : '';
                    // Emit custom event with details for backend integration
                    const detail = { applicationId: appId, action: action, argument: argument };
                    document.dispatchEvent(new CustomEvent('ipcounsel:rejected_review', { detail }));
                    console.log('ipcounsel:rejected_review', detail);
                    hideModal();
                });

            } else if(statusText.includes('legal_review') || statusText.includes('legal-review') || statusText.includes('legal review')){
                // Legal review modal: show details and Accept/Cancel
                let html = detailsHtml;
                html += '<div style="margin-top:12px; text-align:right;">';
                html += '<form action="../../src/ipcounsel.php" method="POST">';
                html += '<button type="submit" id="accept-legal" class="btn primary">Accept</button> ';
                html += '<button type="button" id="cancel-legal" class="btn">Cancel</button>';
                html += '</form>';
                html += '</div>';

                showModal(html);

                document.getElementById('cancel-legal').addEventListener('click', hideModal);
                document.getElementById('accept-legal').addEventListener('click', function(){
                    const detail = { applicationId: appId, action: 'accept' };
                    document.dispatchEvent(new CustomEvent('ipcounsel:legal_accept', { detail }));
                    console.log('ipcounsel:legal_accept', detail);
                    hideModal();
                });

            } else {
                // Fallback: just show details with cancel
                let html = detailsHtml + '<div style="margin-top:12px; text-align:right;"><button type="button" id="cancel-review-2" class="btn">Close</button></div>';
                showModal(html);
                document.getElementById('cancel-review-2').addEventListener('click', hideModal);
            }
        });

        // Close icon and overlay click handler
        document.addEventListener('click', function(e){
            if(e.target.classList.contains('close-modal')) hideModal();
            // click outside modal
            if(e.target.id === 'review-modal') hideModal();
        });

        // Initially hide modal
        if(modal) modal.style.display = 'none';

    })();