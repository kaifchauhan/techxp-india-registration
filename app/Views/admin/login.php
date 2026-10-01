<?= view('layouts/header', ['pageTitle' => $pageTitle]) ?>
<?= view('layouts/navbar') ?>

<main class="py-5">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="txp-card p-4 p-md-5 border-warning border-opacity-25">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3);">
                            <i class="bi bi-shield-lock-fill text-warning fs-3"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-1">Staff Administrator Portal</h3>
                        <p class="text-secondary small">Restricted to authorized summit coordinators & organizers</p>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger py-2 small mb-3">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i><?= esc(session()->getFlashdata('error')) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success py-2 small mb-3">
                            <i class="bi bi-check2-circle me-1"></i><?= esc(session()->getFlashdata('success')) ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('admin/login') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="admin_username" class="form-label">Username or Admin Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary border-opacity-25 text-secondary">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" class="form-control" id="admin_username" name="username" value="<?= old('username', 'admin') ?>" placeholder="e.g. admin" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="admin_password" class="form-label">Administrator Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary border-opacity-25 text-secondary">
                                    <i class="bi bi-key"></i>
                                </span>
                                <input type="password" class="form-control" id="admin_password" name="password" value="AdminPassword123!" placeholder="Enter password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 py-2 fw-bold text-dark">
                            <i class="bi bi-lock-fill me-2"></i>Sign In to Admin Console
                        </button>
                    </form>

                    <!-- Pre-seeded credentials card for reviewers -->
                    <div class="mt-4 p-3 rounded bg-black bg-opacity-40 border border-warning border-opacity-20 small">
                        <span class="text-warning fw-semibold d-block mb-1"><i class="bi bi-info-circle me-1"></i>Reviewer Admin Access:</span>
                        <div class="text-secondary" style="font-size: 0.8rem;">
                            Username: <code class="text-warning">admin</code><br>
                            Password: <code class="text-warning">AdminPassword123!</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= view('layouts/footer') ?>
