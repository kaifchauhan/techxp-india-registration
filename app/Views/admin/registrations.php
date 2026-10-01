<?= view('layouts/header', ['pageTitle' => $pageTitle]) ?>
<?= view('layouts/navbar') ?>

<main class="py-4">
    <div class="container-fluid px-lg-5">

        <!-- PAGE HEADER -->
        <div class="mb-4 pb-3 border-bottom border-white border-opacity-10">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('admin') ?>"
                           class="text-secondary text-decoration-none">
                            Admin
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-white" aria-current="page">
                        Registrations
                    </li>
                </ol>
            </nav>

            <h2 class="text-white fw-bold mb-1">
                Registration Dashboard
            </h2>

            <p class="text-secondary small mb-0">
                View and manage event registrations.
            </p>
        </div>

        <!-- FLASH MESSAGES -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>


        <!-- SUMMARY -->
        <div class="row g-3 mb-4">

            <!-- TOTAL REGISTRATIONS -->
            <div class="col-md-4">
                <div class="txp-card p-4 h-100">
                    <div class="text-secondary small mb-2">
                        Total Registrations
                    </div>

                    <div class="display-6 fw-bold text-white">
                        <?= esc($totalCount) ?>
                    </div>
                </div>
            </div>

            <!-- REGISTRATIONS BY SESSION -->
            <div class="col-md-4">
                <div class="txp-card p-4 h-100">
                    <div class="text-secondary small mb-3">
                        Registrations by Session
                    </div>

                    <?php if (!empty($registrationsBySession)): ?>
                        <?php foreach ($registrationsBySession as $session): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white">
                                    <?= esc($session['session_preference']) ?>
                                </span>

                                <span class="text-cyan fw-semibold">
                                    <?= esc($session['total']) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-secondary small">
                            No session data available.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- REGISTRATIONS BY LOCATION -->
            <div class="col-md-4">
                <div class="txp-card p-4 h-100">
                    <div class="text-secondary small mb-3">
                        Registrations by City / Country
                    </div>

                    <?php if (!empty($registrationsByLocation)): ?>
                        <?php foreach ($registrationsByLocation as $location): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white">
                                    <?= esc($location['city'] ?: 'Unknown') ?>,
                                    <?= esc($location['country'] ?: 'Unknown') ?>
                                </span>

                                <span class="text-cyan fw-semibold">
                                    <?= esc($location['total']) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-secondary small">
                            No location data available.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>


        <!-- SEARCH & FILTER -->
        <div class="txp-card p-3 mb-4">

            <form action="<?= site_url('admin/registrations') ?>"
                  method="GET"
                  class="row g-2 align-items-center">

                <!-- SEARCH -->
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary border-opacity-25 text-secondary">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text"
                               class="form-control"
                               name="search"
                               value="<?= esc($filters['search'] ?? '') ?>"
                               placeholder="Search by name, email or registration ID">
                    </div>
                </div>

                <!-- SESSION FILTER -->
                <div class="col-md-3">
                    <select class="form-select" name="session">
                        <option value="">All Sessions</option>
                        <option value="Main Session"
                            <?= (($filters['session'] ?? '') === 'Main Session') ? 'selected' : '' ?>>
                            Main Session
                        </option>
                        <option value="Workshop"
                            <?= (($filters['session'] ?? '') === 'Workshop') ? 'selected' : '' ?>>
                            Workshop
                        </option>
                        <option value="Both"
                            <?= (($filters['session'] ?? '') === 'Both') ? 'selected' : '' ?>>
                            Both
                        </option>
                    </select>
                </div>

                <!-- COUNTRY FILTER -->
                <div class="col-md-2">
                    <select class="form-select" name="country">
                        <option value="">All Countries</option>

                        <option value="India"
                            <?= (($filters['country'] ?? '') === 'India') ? 'selected' : '' ?>>
                            India
                        </option>

                        <option value="United States"
                            <?= (($filters['country'] ?? '') === 'United States') ? 'selected' : '' ?>>
                            United States
                        </option>

                        <option value="United Kingdom"
                            <?= (($filters['country'] ?? '') === 'United Kingdom') ? 'selected' : '' ?>>
                            United Kingdom
                        </option>

                        <option value="Canada"
                            <?= (($filters['country'] ?? '') === 'Canada') ? 'selected' : '' ?>>
                            Canada
                        </option>
                    </select>
                </div>


                <!-- ACTIONS -->
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit"
                            class="btn btn-txp-cyan flex-fill">
                        <i class="bi bi-filter me-1"></i>
                        Apply
                    </button>

                    <a href="<?= site_url('admin/registrations') ?>"
                       class="btn btn-txp-outline"
                       title="Reset Filters">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>

            </form>

        </div>


        <!-- RECENT REGISTRATIONS -->
        <div class="txp-card p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>
                    <h5 class="text-white mb-1">
                        Recent Registrations
                    </h5>

                    <span class="text-secondary small">
                        Showing <?= count($registrations) ?>
                        of <?= esc($totalCount) ?> registrations
                    </span>
                </div>

            </div>


            <!-- REGISTRATION TABLE -->
            <div class="table-responsive">

                <table class="table txp-table align-middle">

                    <thead>
                        <tr>
                            <th>Registration ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>City</th>
                            <th>Country</th>
                            <th>Session</th>
                            <th>Registered</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($registrations)): ?>

                            <?php foreach ($registrations as $row): ?>

                                <tr>

                                    <!-- REGISTRATION ID -->
                                    <td>
                                        <span class="font-monospace text-cyan fw-semibold">
                                            <?= esc($row['registration_id']) ?>
                                        </span>
                                    </td>

                                    <!-- NAME -->
                                    <td>
                                        <div class="fw-semibold text-white">
                                            <?= esc($row['full_name']) ?>
                                        </div>
                                    </td>

                                    <!-- EMAIL -->
                                    <td>
                                        <span class="text-white small">
                                            <?= esc($row['email']) ?>
                                        </span>
                                    </td>

                                    <!-- CITY -->
                                    <td>
                                        <span class="text-white small">
                                            <?= esc($row['city'] ?: 'N/A') ?>
                                        </span>
                                    </td>

                                    <!-- COUNTRY -->
                                    <td>
                                        <span class="text-white small">
                                            <?= esc($row['country'] ?: 'N/A') ?>
                                        </span>
                                    </td>

                                    <!-- SESSION -->
                                    <td>
                                        <span class="text-white small">
                                            <?= esc($row['session_preference'] ?: 'N/A') ?>
                                        </span>
                                    </td>

                                    <!-- REGISTERED DATE -->
                                    <td>
                                        <small class="text-secondary">
                                            <?= date('d M Y', strtotime($row['created_at'])) ?>
                                        </small>
                                        <br>
                                        <small class="text-secondary">
                                            <?= date('h:i A', strtotime($row['created_at'])) ?>
                                        </small>
                                    </td>


                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="8"
                                    class="text-center text-secondary py-5">

                                    <i class="bi bi-search display-6 d-block mb-2 opacity-50"></i>

                                    No registrations found.

                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>


            <!-- PAGINATION -->
            <?php if ($totalPages > 1): ?>

                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-white border-opacity-10">

                    <span class="text-secondary small">
                        Page <?= esc($page) ?> of <?= esc($totalPages) ?>
                    </span>

                    <nav aria-label="Registration Pagination">

                        <ul class="pagination pagination-sm mb-0">

                            <?php if ($page > 1): ?>

                                <li class="page-item">

                                    <a class="page-link bg-dark text-white border-secondary"
                                       href="<?= site_url(
                                           'admin/registrations?' .
                                           http_build_query(
                                               array_merge(
                                                   $filters,
                                                   ['page' => $page - 1]
                                               )
                                           )
                                       ) ?>">
                                        Previous
                                    </a>

                                </li>

                            <?php endif; ?>


                            <?php for ($p = 1; $p <= $totalPages; $p++): ?>

                                <?php if (
                                    $p == 1 ||
                                    $p == $totalPages ||
                                    abs($p - $page) <= 1
                                ): ?>

                                    <li class="page-item <?= $p == $page ? 'active' : '' ?>">

                                        <a class="page-link <?= $p == $page
                                            ? 'bg-primary text-white border-primary'
                                            : 'bg-dark text-white border-secondary' ?>"
                                           href="<?= site_url(
                                               'admin/registrations?' .
                                               http_build_query(
                                                   array_merge(
                                                       $filters,
                                                       ['page' => $p]
                                                   )
                                               )
                                           ) ?>">
                                            <?= $p ?>
                                        </a>

                                    </li>

                                <?php endif; ?>

                            <?php endfor; ?>


                            <?php if ($page < $totalPages): ?>

                                <li class="page-item">

                                    <a class="page-link bg-dark text-white border-secondary"
                                       href="<?= site_url(
                                           'admin/registrations?' .
                                           http_build_query(
                                               array_merge(
                                                   $filters,
                                                   ['page' => $page + 1]
                                               )
                                           )
                                       ) ?>">
                                        Next
                                    </a>

                                </li>

                            <?php endif; ?>

                        </ul>

                    </nav>

                </div>

            <?php endif; ?>

        </div>

    </div>
</main>

<?= view('layouts/footer') ?>
