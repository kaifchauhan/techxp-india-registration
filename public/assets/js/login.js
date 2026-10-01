/**
 * TechXP India - Attendee Login Component Logic
 * Smart Email OR Mobile identifier detection, AJAX auth, and feedback
 */
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    if (!loginForm) return;

    const identifierInput = document.getElementById('identifier');
    const identifierBadge = document.getElementById('identifierBadge');
    const submitBtn = document.getElementById('loginSubmitBtn');
    const alertBox = document.getElementById('loginAlertBox');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    // Live Email vs Mobile Detection Hint
    if (identifierInput && identifierBadge) {
        identifierInput.addEventListener('input', (e) => {
            const val = e.target.value.trim();
            if (!val) {
                identifierBadge.innerHTML = '<i class="bi bi-person me-1"></i>Email or Mobile';
                identifierBadge.className = 'badge bg-secondary';
                return;
            }

            if (/^[0-9]+$/.test(val)) {
                identifierBadge.innerHTML = '<i class="bi bi-phone me-1"></i>Mobile Number';
                identifierBadge.className = 'badge bg-info text-dark';
            } else if (val.includes('@')) {
                identifierBadge.innerHTML = '<i class="bi bi-envelope me-1"></i>Email Address';
                identifierBadge.className = 'badge bg-primary';
            } else {
                identifierBadge.innerHTML = '<i class="bi bi-person me-1"></i>Email / Mobile';
                identifierBadge.className = 'badge bg-secondary';
            }
        });
    }

    // Toggle Password Visibility
    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', () => {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            togglePasswordBtn.innerHTML = isPassword ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
        });
    }

    // Form submission
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const identifier = identifierInput ? identifierInput.value.trim() : '';
        const password = passwordInput ? passwordInput.value : '';

        if (!identifier || !password) {
            showLoginAlert('Please enter both your Email/Mobile and Password.', 'danger');
            return;
        }

        const originalBtnHtml = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verifying Credentials...';

        showLoginAlert('', false);

        try {
            const formData = new FormData(loginForm);

            const response = await fetch(loginForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showLoginAlert(`<strong>Success!</strong> ${data.message}`, 'success');
                submitBtn.innerHTML = '<i class="bi bi-check2-circle me-2"></i>Access Granted!';

                setTimeout(() => {
                    window.location.href = data.redirect_url || '/event';
                }, 800);
            } else {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                showLoginAlert(data.message || 'Authentication failed. Please check credentials.', 'danger');
            }
        } catch (err) {
            console.error('Login Error:', err);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
            showLoginAlert('Network error or server unavailable. Please try again.', 'danger');
        }
    });

    function showLoginAlert(html, type) {
        if (!alertBox) return;
        if (!html) {
            alertBox.classList.add('d-none');
            alertBox.innerHTML = '';
            return;
        }
        alertBox.className = `alert alert-${type} d-flex align-items-center mb-3`;
        alertBox.innerHTML = `<div>${html}</div>`;
    }
});
