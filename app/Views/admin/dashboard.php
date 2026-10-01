<?= view('layouts/header', ['pageTitle' => $pageTitle]) ?>
<?= view('layouts/navbar') ?>

<main class="py-4">
    <div class="container-fluid px-lg-5">
        <!-- Dashboard Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom border-white border-opacity-10">
            <div>
                <span class="badge bg-warning text-dark fw-bold mb-1"><i class="bi bi-shield-check me-1"></i>ADMIN CONSOLE</span>
                <h2 class="text-white fw-bold mb-0">Summit Registration & Operations Dashboard</h2>
                <p class="text-secondary small mb-0">Overview of attendee metrics, ticket tiers, check-in activity, and live attendance.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= site_url('admin/registrations') ?>" class="btn btn-txp-primary btn-sm">
                    <i class="bi bi-people me-1"></i>View All Registrations
                </a>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check2-circle me-1"></i><?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>



        <!-- Metrics & RECENT REGISTRATIONS -->
        <div class="row g-4 mb-4">
            <!-- Ticket Type Breakdown -->
            <div class="col-lg-4">
                <div class="txp-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-secondary small text-uppercase fw-semibold">Total Registrations</span>
                        <div class="p-2 rounded bg-primary bg-opacity-20 text-primary">
                            <i class="bi bi-people-fill fs-5"></i>
                        </div>
                    </div>
                    <h3 class="display-6 fw-bold text-white mb-1"><?= number_format($stats['total']) ?></h3>
                    <span class="text-secondary small">Across all pass categories</span>
                </div>
            </div>

            <!-- Recent Registrations Table -->
            <div class="col-lg-8">
                <div class="txp-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-white mb-0"><i class="bi bi-clock-history text-gradient-primary me-2"></i>Recent Attendee Registrations</h5>
                        <a href="<?= site_url('admin/registrations') ?>" class="text-cyan text-decoration-none small">
                            View All <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table txp-table align-middle">
                            <thead>
                                <tr>
                                    <th>Badge ID</th>
                                    <th>Attendee Name</th>
                                    <th>Contact</th>
                                    <th>Country</th>
                                    <th>City</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($stats['recent'])): ?>
                                    <?php foreach ($stats['recent'] as $row): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= site_url('admin/registrations/' . $row['id']) ?>" class="font-monospace text-cyan text-decoration-none fw-bold">
                                                    <?= esc($row['registration_id']) ?>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-white"><?= esc($row['full_name']) ?></div>
                                                <small class="text-secondary"><?= esc($row['company'] ?: 'N/A') ?></small>
                                            </td>
                                            <td>
                                                <div class="small text-white"><?= esc($row['email']) ?></div>
                                                <small class="text-secondary">+91 <?= esc($row['mobile']) ?></small>
                                            </td>
                                            <td>
                                                <div class="small text-white"><?= esc($row['country']) ?></div>
                                            </td>
                                            <td>
                                                <div class="small text-white"><?= esc($row['city']) ?></div>
                                            </td>
                                        
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-secondary py-4">No registrations found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
$extraScripts = '<script src="' . base_url('assets/js/admin.js') . '"></script>';
?>
<?= view('layouts/footer', ['extraScripts' => $extraScripts]) ?>
