<?= view('layouts/header', ['pageTitle' => $pageTitle]) ?>
<?= view('layouts/navbar') ?>

<main class="py-5">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="txp-card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-white mb-1">Attendee Login</h2>
                        <p class="text-secondary small">Sign in using either your <strong>Email</strong> or <strong>Mobile Number</strong></p>
                    </div>

                    <!-- Flash messages / Alert -->
                    <div id="loginAlertBox" class="<?= session()->getFlashdata('error') ? 'alert alert-danger mb-3' : (session()->getFlashdata('success') ? 'alert alert-success mb-3' : 'd-none') ?>">
                        <?php if (session()->getFlashdata('error')): ?>
                            <?= esc(session()->getFlashdata('error')) ?>
                        <?php elseif (session()->getFlashdata('success')): ?>
                            <?= esc(session()->getFlashdata('success')) ?>
                        <?php endif; ?>
                    </div>

                    <form id="loginForm" action="<?= site_url('login') ?>" method="POST">
                        <?= csrf_field() ?>

                        <!-- Email OR Mobile Identifier -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="identifier" class="form-label mb-0">Email or Mobile Number</label>
                            </div>
                            <div class="input-group">
                                <input type="text" class="form-control" id="identifier" name="identifier" value="<?= old('identifier') ?>" placeholder="e.g. name@company.com or 9876543210" required autofocus>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label mb-0">Password</label>
                            </div>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                                <button type="button" class="btn btn-outline-secondary border-opacity-25" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" id="loginSubmitBtn" class="btn btn-txp-primary w-100 py-2 fs-6">
                            Login
                        </button>
                    </form>

                    <!-- Technical Review Quick Credentials helper -->
                    <div class="mt-4 p-3 rounded bg-black bg-opacity-40 border border-white border-opacity-10 small">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-white fw-semibold">Test Credentials:</span>
                        </div>
                        <div class="text-secondary" style="font-size: 0.8rem;">
                            <div>Email: <code class="text-info">kaif@test.com</code></div>
                            <div>Mobile: <code class="text-info">9874563210</code></div>
                            <div>Password: <code class="text-info">123456</code></div>
                        </div>
                    </div>

                    <div class="text-center mt-4 pt-3 border-top border-white border-opacity-10 text-secondary small">
                        Not registered yet? <a href="<?= site_url('register') ?>" class="text-cyan text-decoration-none fw-semibold">Register for a summit pass <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
$extraScripts = '<script src="' . base_url('assets/js/login.js') . '"></script>';
?>
<?= view('layouts/footer', ['extraScripts' => $extraScripts]) ?>
