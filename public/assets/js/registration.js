/**
 * TechXP India - Registration Component Logic
 * Robust validation, duplicate checks, fetch integration, and feedback
 */
document.addEventListener('DOMContentLoaded', () => {
    const regForm = document.getElementById('registrationForm');
    if (!regForm) return;

    const submitBtn = document.getElementById('submitBtn');
    const alertBox = document.getElementById('alertBox');
    const mobileInput = document.getElementById('mobile');
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');

    // Restrict mobile input to numbers only
    if (mobileInput) {
        mobileInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 10);
        });
    }

    regForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Clear previous alert
        showAlert('', false);
        clearFieldErrors();

        // Client-side quick checks
        let hasClientError = false;

        const fullName = document.getElementById('full_name')?.value.trim();
        const email = document.getElementById('email')?.value.trim();
        const mobile = mobileInput ? mobileInput.value.trim() : '';

        if (!fullName || fullName.length < 3) {
            setFieldError('full_name', 'Full name must be at least 3 characters.');
            hasClientError = true;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRegex.test(email)) {
            setFieldError('email', 'Please provide a valid email address.');
            hasClientError = true;
        }

        if (!mobile || mobile.length !== 10) {
            setFieldError('mobile', 'Please enter a valid 10-digit mobile number.');
            hasClientError = true;
        }


        if (!password || password.value.length < 6) {
            setFieldError('password', 'Password must be at least 6 characters long.');
            hasClientError = true;
        }

        if (password && passwordConfirm && password.value !== passwordConfirm.value) {
            setFieldError('password_confirm', 'Passwords do not match.');
            hasClientError = true;
        }

        if (hasClientError) {
            showAlert('Please correct the highlighted errors before submitting.', 'danger');
            return;
        }

        // Set button loading state
        const originalBtnHtml = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Confirming Registration...';

        try {
            const formData = new FormData(regForm);

            const response = await fetch(regForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showAlert(`<strong>Success!</strong> ${data.message} Redirecting to your Attendee Portal...`, 'success');
                submitBtn.innerHTML = '<i class="bi bi-check2-circle me-2"></i>Registration Confirmed!';
                
                setTimeout(() => {
                    window.location.href = data.redirect_url || '/event';
                }, 1200);
            } else {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;

                if (data.errors) {
                    for (const [field, msg] of Object.entries(data.errors)) {
                        setFieldError(field, msg);
                    }
                    showAlert('Please correct the validation errors below.', 'danger');
                } else {
                    showAlert(data.message || 'Registration failed. Please check your details.', 'danger');
                }
            }
        } catch (error) {
            console.error('Registration Error:', error);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
            showAlert('Network or server error. Please try again.', 'danger');
        }
    });

    function setFieldError(fieldId, message) {
        const input = document.getElementById(fieldId);
        if (input) {
            input.classList.add('is-invalid');
            let feedback = input.nextElementSibling;
            if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                input.parentNode.appendChild(feedback);
            }
            feedback.innerText = message;
        }
    }

    function clearFieldErrors() {
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    }

    function showAlert(html, type) {
        if (!alertBox) return;
        if (!html) {
            alertBox.classList.add('d-none');
            alertBox.innerHTML = '';
            return;
        }
        alertBox.className = `alert alert-${type} d-flex align-items-center mb-4`;
        alertBox.innerHTML = `<div>${html}</div>`;
        alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
