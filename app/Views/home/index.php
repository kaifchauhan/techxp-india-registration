<?= view('layouts/header', ['pageTitle' => $pageTitle]) ?>
<?= view('layouts/navbar') ?>

<main>
    <!-- HERO SECTION -->
    <section class="py-5 py-lg-6 position-relative border-bottom border-white border-opacity-10">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="container py-4">
                        <h2 class="display-6 fw-bold text-white mb-3">Ready to Join India's Leading Tech Conclave?</h2>
                        <p class="text-secondary lead max-w-600 mx-auto mb-4">
                            Secure your spot today. Duplicate registrations are prevented and digital badges are issued instantly.
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="<?= site_url('register') ?>" class="btn btn-txp-primary btn-lg px-4">
                                <i class="bi bi-pencil-square me-2"></i>Register Now
                            </a>
                            <a href="<?= site_url('login') ?>" class="btn btn-txp-outline btn-lg px-4">
                                <i class="bi bi-shield-check me-2"></i>Attendee Access
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Hero: Live Venue Weather & Summit Preview Card -->
                <div class="col-lg-5">
                    <!-- Real Third-Party REST API Integration Widget -->
                    <div class="txp-card p-4 mb-4 border border-info border-opacity-25" style="background: linear-gradient(145deg, rgba(19, 27, 52, 0.85) 0%, rgba(14, 21, 41, 0.95) 100%);">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-white border-opacity-10">
                            <div>
                                <span class="badge bg-info-subtle text-info border border-info-subtle mb-1">
                                    <i class="bi bi-lightning-charge-fill me-1"></i>Live Third-Party REST API
                                </span>
                                <h5 class="text-white mb-0">Venue Climate & Weather</h5>
                            </div>
                            <span class="pulse-indicator"></span>
                        </div>

                        <?php if (!empty($weather)): ?>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="bi <?= $weather['icon'] ?> display-4"></i>
                                    <div>
                                        <div class="display-6 fw-bold text-white"><?= $weather['temperature'] ?>&deg;C</div>
                                        <div class="text-info fw-medium"><?= esc($weather['condition']) ?></div>
                                    </div>
                                </div>
                                <div class="text-end small text-secondary">
                                    <div>Feels like: <strong class="text-white"><?= $weather['feels_like'] ?>&deg;C</strong></div>
                                    <div>Humidity: <strong class="text-white"><?= $weather['humidity'] ?>%</strong></div>
                                    <div>Wind: <strong class="text-white"><?= $weather['wind_speed'] ?> km/h</strong></div>
                                </div>
                            </div>
                            <div class="p-2 rounded bg-black bg-opacity-30 d-flex justify-content-between align-items-center small text-secondary">
                                <span><i class="bi bi-geo-alt-fill text-danger me-1"></i><?= esc($weather['city']) ?></span>
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem;"><?= esc($weather['source']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </section>

</main>

<?= view('layouts/footer') ?>
