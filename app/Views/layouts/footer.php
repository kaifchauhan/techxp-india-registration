<footer class="txp-footer">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-5">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="fs-5 fw-bold text-white">Tech<span class="text-gradient-cyan">XP</span> India Summit</span>
                </div>
                <p class="text-secondary small mb-3 pe-lg-4">
                    The premier enterprise gathering of cloud architects, AI leaders, and full-stack innovators. Hosted in Bengaluru, India's Silicon Valley.
                </p>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-dark border border-secondary text-secondary small">CodeIgniter 4</span>
                    <span class="badge bg-dark border border-secondary text-secondary small">PHP 8.1</span>
                    <span class="badge bg-dark border border-secondary text-secondary small">MySQL</span>
                    <span class="badge bg-dark border border-secondary text-secondary small">REST API</span>
                </div>
            </div>

        </div>

        <hr class="border-secondary opacity-25">

        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between small text-secondary">
            <div>
                &copy; <?= date('Y') ?> Kaif Chauhan. Built with CodeIgniter 4. All rights reserved.
            </div>
            <div class="d-flex gap-3 mt-2 mt-md-0">
                <a href="<?= site_url('api/venue-weather') ?>" target="_blank" class="text-secondary text-decoration-none hover-text-white"><i class="bi bi-cloud-sun me-1"></i>Weather API</a>
                <a href="<?= site_url('api/registrations') ?>" target="_blank" class="text-secondary text-decoration-none hover-text-white"><i class="bi bi-database me-1"></i>Registrations API</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5.3 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<?= $extraScripts ?? '' ?>
</body>
</html>
