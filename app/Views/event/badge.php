<?= view('layouts/header', ['pageTitle' => $pageTitle]) ?>

<div class="container py-5 text-center">
    <div class="no-print mb-4">
        <a href="<?= site_url('event') ?>" class="btn btn-txp-outline me-2"><i class="bi bi-arrow-left me-1"></i>Back to Portal</a>
        <button onclick="window.print()" class="btn btn-txp-primary"><i class="bi bi-printer me-1"></i>Print / Save Badge</button>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="attendee-badge-card text-center p-5 shadow-lg">
                <!-- Top Brand Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-5 fw-bold text-white">Tech<span class="text-gradient-cyan">XP</span> India</span>
                    </div>
                    <span class="badge bg-warning text-dark px-3 py-1 fw-bold"><?= esc($attendee['ticket_type']) ?></span>
                </div>

                <!-- Event Details -->
                <div class="text-uppercase text-secondary fs-8 tracking-wider mb-4">
                    Summit 2026 &bull; BIEC Bengaluru &bull; Oct 24–26
                </div>

                <!-- QR Code Box -->
                <div class="badge-qr-box my-4 mx-auto p-2 bg-white rounded-3 shadow">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode(site_url('admin/registrations/' . $attendee['id'])) ?>" 
                         alt="Badge QR Code" 
                         style="width: 150px; height: 150px;">
                </div>

                <!-- Badge ID -->
                <div class="font-monospace text-gradient-cyan fs-4 fw-bold mb-2">
                    <?= esc($attendee['registration_id']) ?>
                </div>

                <!-- Attendee Name & Designation -->
                <h3 class="text-white fw-bold mb-1"><?= esc($attendee['full_name']) ?></h3>
                <div class="text-cyan fw-medium mb-1"><?= esc($attendee['designation'] ?: 'Software Engineer') ?></div>
                <div class="text-secondary small mb-4"><?= esc($attendee['company'] ?: 'Independent Delegate') ?></div>

                <!-- Footer Security Strip -->
                <div class="pt-3 border-top border-white border-opacity-10 d-flex justify-content-between text-secondary fs-8">
                    <span>STATUS: <strong><?= strtoupper(esc($attendee['status'])) ?></strong></span>
                    <span>ISSUED: <strong><?= date('d M Y', strtotime($attendee['created_at'])) ?></strong></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
