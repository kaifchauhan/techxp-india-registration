/**
 * TechXP India - Admin Panel JavaScript
 * Live check-in handling, status updates, clipboard helpers, and search
 */
document.addEventListener('DOMContentLoaded', () => {
    // 1-Click Fast Check-In Handler
    document.querySelectorAll('.btn-checkin-action').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const regId = btn.getAttribute('data-id');
            const attendeeName = btn.getAttribute('data-name');

            if (!confirm(`Confirm check-in for ${attendeeName}?`)) return;

            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            try {
                const response = await fetch(`/admin/registrations/${regId}/checkin`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Update status badge on the row
                    const statusCell = document.getElementById(`status-badge-${regId}`);
                    if (statusCell) {
                        statusCell.className = 'badge-status badge-status-attended';
                        statusCell.innerHTML = '<i class="bi bi-check-all"></i> Attended';
                    }

                    // Replace button with Checked In text
                    btn.parentNode.innerHTML = `<span class="badge bg-success-subtle text-success border border-success-subtle py-2 px-3"><i class="bi bi-check2 me-1"></i>Checked In</span>`;
                } else {
                    alert(data.message || 'Check-in failed');
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            } catch (err) {
                console.error(err);
                alert('Network error while processing check-in.');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        });
    });

    // Copy to clipboard helper
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const text = btn.getAttribute('data-copy');
            if (text) {
                navigator.clipboard.writeText(text).then(() => {
                    const originalTitle = btn.getAttribute('title');
                    btn.setAttribute('title', 'Copied!');
                    btn.innerHTML = '<i class="bi bi-check2 text-success"></i>';
                    setTimeout(() => {
                        btn.innerHTML = '<i class="bi bi-clipboard"></i>';
                        btn.setAttribute('title', originalTitle);
                    }, 1500);
                });
            }
        });
    });

    // Delete Confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', (e) => {
            const name = form.getAttribute('data-name') || 'this registration';
            if (!confirm(`Are you sure you want to permanently delete registration for ${name}? This action cannot be undone.`)) {
                e.preventDefault();
            }
        });
    });
});
