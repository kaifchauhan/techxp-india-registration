<?= view('layouts/header', ['pageTitle' => $pageTitle]) ?>
<?= view('layouts/navbar') ?>

<main class="py-4">
    <div class="container py-3">
        <!-- Breadcrumb & Nav -->
        <div class="mb-4 pb-2 border-bottom border-white border-opacity-10 d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small mb-1">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>" class="text-secondary text-decoration-none">Admin</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/registrations') ?>" class="text-secondary text-decoration-none">Registrations</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page"><?= esc($registration['registration_id']) ?></li>
                    </ol>
                </nav>
                <h3 class="text-white fw-bold mb-0">Attendee Dossier: <?= esc($registration['full_name']) ?></h3>
            </div>
            <a href="<?= site_url('admin/registrations') ?>" class="btn btn-txp-outline btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back to List
            </a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check2-circle me-1"></i><?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i><?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Left Column: Registration Details -->
            <div class="col-lg-8">
                <div class="txp-card p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-white border-opacity-10">
                        <h5 class="text-white mb-0"><i class="bi bi-person-vcard text-gradient-cyan me-2"></i>Personal & Event Credentials</h5>
                        <span class="badge bg-primary text-white font-monospace"><?= esc($registration['registration_id']) ?></span>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <span class="text-secondary small d-block">Full Legal Name</span>
                            <strong class="text-white fs-6"><?= esc($registration['full_name']) ?></strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary small d-block">Email Address</span>
                            <a href="mailto:<?= esc($registration['email']) ?>" class="text-cyan text-decoration-none">
                                <?= esc($registration['email']) ?>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary small d-block">Mobile Contact</span>
                            <a href="tel:+91<?= esc($registration['mobile']) ?>" class="text-white text-decoration-none">
                                +91 <?= esc($registration['mobile']) ?>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary small d-block">Organization / Company</span>
                            <span class="text-white"><?= esc($registration['company'] ?: 'Independent Delegate') ?></span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary small d-block">Job Title / Designation</span>
                            <span class="text-white"><?= esc($registration['designation'] ?: 'Software Engineer') ?></span>
                        </div>

                    </div>
                </div>

                <!-- Audit & Technical Metadata Card -->
                <div class="txp-card p-4">
                    <h5 class="text-white mb-3 pb-2 border-bottom border-white border-opacity-10">
                        <i class="bi bi-shield-check text-gradient-primary me-2"></i>Security & Audit Trail
                    </h5>
                    <div class="row g-3 small">
                        <div class="col-sm-6">
                            <span class="text-secondary d-block">Registration IP Address</span>
                            <code class="text-info"><?= esc($registration['ip_address'] ?: '127.0.0.1') ?></code>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary d-block">Record Created Timestamp</span>
                            <span class="text-white"><?= date('d M Y, h:i:s A', strtotime($registration['created_at'])) ?></span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary d-block">Check-In Status</span>
                            <?php if ($registration['status'] === 'attended' && $registration['check_in_time']): ?>
                                <span class="text-success fw-bold"><i class="bi bi-check-all me-1"></i>Checked In at <?= date('d M Y, h:i A', strtotime($registration['check_in_time'])) ?></span>
                            <?php else: ?>
                                <span class="text-secondary">Not Checked In Yet</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary d-block">Last Updated</span>
                            <span class="text-white"><?= date('d M Y, h:i:s A', strtotime($registration['updated_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Status Controls & Digital Badge Preview -->
            <div class="col-lg-4">
                <!-- Status Control Box -->
                <div class="txp-card p-4 mb-4">
                    <h5 class="text-white mb-3 pb-2 border-bottom border-white border-opacity-10">
                        <i class="bi bi-toggles text-warning me-2"></i>Change Attendee Status
                    </h5>
                    
                    <form action="<?= site_url('admin/registrations/' . $registration['id'] . '/status') ?>" method="POST" class="mb-3">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="status" class="form-label">Current Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="confirmed" <?= $registration['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed (Active)</option>
                                <option value="attended" <?= $registration['status'] === 'attended' ? 'selected' : '' ?>>Attended (Checked-In)</option>
                                <option value="cancelled" <?= $registration['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled (Revoked)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-txp-cyan w-100 btn-sm">
                            <i class="bi bi-arrow-repeat me-1"></i>Update Status
                        </button>
                    </form>

                    <?php if ($registration['status'] === 'confirmed'): ?>
                        <form action="<?= site_url('admin/registrations/' . $registration['id'] . '/checkin') ?>" method="POST">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-success w-100 btn-sm">
                                <i class="bi bi-qr-code-scan me-1"></i>Mark Checked-In (Attended)
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

                <!-- Danger Zone (Delete) -->
                <div class="txp-card p-3 border-danger border-opacity-30">
                    <span class="text-danger fw-bold small d-block mb-1">Danger Zone</span>
                    <p class="text-secondary small mb-2">Permanently remove this registration record from MySQL.</p>
                    <form action="<?= site_url('admin/registrations/' . $registration['id'] . '/delete') ?>" method="POST" class="delete-form" data-name="<?= esc($registration['full_name']) ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                            <i class="bi bi-trash3 me-1"></i>Delete Registration
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
$extraScripts = '<script src="' . base_url('assets/js/admin.js') . '"></script>';
?>
<?= view('layouts/footer', ['extraScripts' => $extraScripts]) ?>
