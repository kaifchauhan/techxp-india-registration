<?php
$isAttendee = session()->get('is_attendee_logged_in');
$isAdmin    = session()->get('is_admin_logged_in');
?>
<header class="txp-navbar">
    <div class="container-fluid px-lg-5">
        <div class="d-flex align-items-center justify-content-between">
            <!-- Brand -->
            <a href="<?= site_url('/') ?>" class="navbar-brand-logo">
                <span>Tech<span class="text-gradient-cyan">XP</span> <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-6 align-middle ms-1">INDIA '26</span></span>
            </a>

            <!-- Navigation Links -->
            <nav class="d-none d-md-flex align-items-center gap-3">
                <a href="<?= site_url('/') ?>" class="nav-link text-white-50 hover-text-white fw-medium px-2 py-1"><i class="bi bi-house-door me-1"></i>Summit Home</a>
                <a href="<?= site_url('event') ?>" class="nav-link text-white-50 hover-text-white fw-medium px-2 py-1">
                    <span class="pulse-indicator me-1"></span>Live Portal
                </a>
                <?php if ($isAdmin): ?>
                    <a href="<?= site_url('admin') ?>" class="nav-link text-warning fw-medium px-2 py-1"><i class="bi bi-speedometer2 me-1"></i>Admin Dashboard</a>
                    <a href="<?= site_url('admin/registrations') ?>" class="nav-link text-white-50 hover-text-white fw-medium px-2 py-1"><i class="bi bi-people me-1"></i>Registrations</a>
                <?php endif; ?>
            </nav>

            <!-- User Auth Actions -->
            <div class="d-flex align-items-center gap-2">
                <?php if ($isAttendee): ?>
                    <div class="dropdown">
                        <button class="btn btn-txp-outline btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-badge text-cyan"></i>
                            <span class="d-none d-sm-inline fw-semibold"><?= esc(session()->get('attendee_name')) ?></span>
                            <span class="badge bg-primary text-white" style="font-size: 0.7rem;"><?= esc(session()->get('attendee_reg_id')) ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="background: #131B34; border: 1px solid rgba(255,255,255,0.15);">
                            <li><a class="dropdown-item text-white" href="<?= site_url('event') ?>"><i class="bi bi-broadcast me-2 text-cyan"></i>Live Stream & Chat</a></li>
                            <li><hr class="dropdown-divider border-secondary"></li>
                            <li><a class="dropdown-item text-danger" href="<?= site_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</a></li>
                        </ul>
                    </div>
                <?php elseif ($isAdmin): ?>
                    <div class="dropdown">
                        <button class="btn btn-warning btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-shield-lock"></i>
                            <span>Admin (<?= esc(session()->get('admin_username')) ?>)</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="background: #131B34; border: 1px solid rgba(255,255,255,0.15);">
                            <li><a class="dropdown-item text-white" href="<?= site_url('admin') ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item text-white" href="<?= site_url('admin/registrations') ?>"><i class="bi bi-people me-2"></i>Registrations</a></li>
                            <li><hr class="dropdown-divider border-secondary"></li>
                            <li><a class="dropdown-item text-danger" href="<?= site_url('admin/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= site_url('login') ?>" class="btn btn-txp-outline btn-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Attendee Login
                    </a>
                    <a href="<?= site_url('register') ?>" class="btn btn-txp-primary btn-sm">
                        <i class="bi bi-ticket-perforated me-1"></i>Register Now
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
