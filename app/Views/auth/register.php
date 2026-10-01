<?= view('layouts/header', ['pageTitle' => $pageTitle]) ?>
<?= view('layouts/navbar') ?>

<main class="py-5">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="txp-card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-white mb-1">Registration</h2>
                        <p class="text-secondary small">Join us at TechXP India 2026 Summit</p>
                    </div>

                    <!-- Client / Server Feedback Alert Box -->
                    <div id="alertBox" class="<?= session()->getFlashdata('error') ? 'alert alert-danger mb-4' : (session()->getFlashdata('errors') ? 'alert alert-danger mb-4' : 'd-none') ?>">
                        <?php if (session()->getFlashdata('error')): ?>
                            <?= esc(session()->getFlashdata('error')) ?>
                        <?php elseif (session()->getFlashdata('errors')): ?>
                            <ul class="mb-0 small ps-3">
                                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                    <li><?= esc($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                    <form id="registrationForm" action="<?= site_url('register') ?>" method="POST" novalidate>
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <!-- Full Name -->
                            <div class="col-12">
                                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control <?= session('errors.full_name') ? 'is-invalid' : '' ?>" id="full_name" name="full_name" value="<?= old('full_name') ?>" placeholder="e.g. Aarav Sharma" required>
                                </div>
                                <?php if (session('errors.full_name')): ?>
                                    <div class="invalid-feedback d-block"><?= esc(session('errors.full_name')) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Email Address -->
                            <div class="col-md-6">
                                <label for="email" class="form-label">Work / Personal Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= old('email') ?>" placeholder="name@company.com" required>
                                </div>
                                <div class="form-text text-secondary" style="font-size: 0.75rem;">Must be unique. Used for your portal login & updates.</div>
                                <?php if (session('errors.email')): ?>
                                    <div class="invalid-feedback d-block"><?= esc(session('errors.email')) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Mobile Number -->
                            <div class="col-md-6">
                                <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary border-opacity-25 text-secondary">+91</span>
                                    <input type="tel" class="form-control <?= session('errors.mobile') ? 'is-invalid' : '' ?>" id="mobile" name="mobile" value="<?= old('mobile') ?>" placeholder="10-digit number" maxlength="10" required>
                                </div>
                                <div class="form-text text-secondary" style="font-size: 0.75rem;">Can also be used directly as your login identifier.</div>
                                <?php if (session('errors.mobile')): ?>
                                    <div class="invalid-feedback d-block"><?= esc(session('errors.mobile')) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Organization / Company -->
                            <div class="col-md-6">
                                <label for="company" class="form-label">Company / Institution</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="company" name="company" value="<?= old('company') ?>" placeholder="e.g. CloudCorp Technologies">
                                </div>
                            </div>

                            <!-- Designation / Role -->
                            <div class="col-md-6">
                                <label for="designation" class="form-label">Designation / Role</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="designation" name="designation" value="<?= old('designation') ?>" placeholder="e.g. Senior Software Architect">
                                </div>
                            </div>

                            <!-- Country -->
                            <div class="col-md-6">
                                <label for="country" class="form-label">
                                    Country <span class="text-danger">*</span>
                                </label>

                                <select
                                    class="form-select <?= session('errors.country') ? 'is-invalid' : '' ?>"
                                    id="country"
                                    name="country"
                                    required
                                >
                                    <option value="">Select Country</option>
                                    <option value="India" <?= old('country') === 'India' ? 'selected' : '' ?>>
                                        India
                                    </option>
                                    <option value="United States" <?= old('country') === 'United States' ? 'selected' : '' ?>>
                                        United States
                                    </option>
                                    <option value="United Kingdom" <?= old('country') === 'United Kingdom' ? 'selected' : '' ?>>
                                        United Kingdom
                                    </option>
                                    <option value="Canada" <?= old('country') === 'Canada' ? 'selected' : '' ?>>
                                        Canada
                                    </option>
                                </select>

                                <?php if (session('errors.country')): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= esc(session('errors.country')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- City -->
                                <div class="col-md-6">
                                    <label for="city" class="form-label">
                                        City <span class="text-danger">*</span>
                                    </label>

                                    <select
                                        class="form-select <?= session('errors.city') ? 'is-invalid' : '' ?>"
                                        id="city"
                                        name="city"
                                        required
                                    >
                                        <option value="">Select City</option>
                                        <option value="Mumbai" <?= old('city') === 'Mumbai' ? 'selected' : '' ?>>
                                            Mumbai
                                        </option>
                                        <option value="Delhi" <?= old('city') === 'Delhi' ? 'selected' : '' ?>>
                                            Delhi
                                        </option>
                                        <option value="Bangalore" <?= old('city') === 'Bangalore' ? 'selected' : '' ?>>
                                            Bangalore
                                        </option>
                                        <option value="Hyderabad" <?= old('city') === 'Hyderabad' ? 'selected' : '' ?>>
                                            Hyderabad
                                        </option>
                                        <option value="Pune" <?= old('city') === 'Pune' ? 'selected' : '' ?>>
                                            Pune
                                        </option>
                                        <option value="Chennai" <?= old('city') === 'Chennai' ? 'selected' : '' ?>>
                                            Chennai
                                        </option>
                                    </select>

                                    <?php if (session('errors.city')): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= esc(session('errors.city')) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            <!-- Session Preference -->
                            <div class="col-md-6">
                                <label for="session_preference" class="form-label">
                                    Session Preference
                                </label>

                                <select
                                    class="form-select <?= session('errors.session_preference') ? 'is-invalid' : '' ?>"
                                    id="session_preference"
                                    name="session_preference"
                                >
                                    <option value="">Select Preference</option>

                                    <option value="Main Session"
                                        <?= old('session_preference') === 'Main Session' ? 'selected' : '' ?>>
                                        Main Session
                                    </option>

                                    <option value="Workshop"
                                        <?= old('session_preference') === 'Workshop' ? 'selected' : '' ?>>
                                        Workshop
                                    </option>

                                    <option value="Both"
                                        <?= old('session_preference') === 'Both' ? 'selected' : '' ?>>
                                        Both
                                    </option>
                                </select>

                                <?php if (session('errors.session_preference')): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= esc(session('errors.session_preference')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>


                            <!-- Password -->
                            <div class="col-md-6">
                                <label for="password" class="form-label">Create Access Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="Min. 6 characters" required>
                                </div>
                                <?php if (session('errors.password')): ?>
                                    <div class="invalid-feedback d-block"><?= esc(session('errors.password')) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6">
                                <label for="password_confirm" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control <?= session('errors.password_confirm') ? 'is-invalid' : '' ?>" id="password_confirm" name="password_confirm" placeholder="Retype password" required>
                                </div>
                                <?php if (session('errors.password_confirm')): ?>
                                    <div class="invalid-feedback d-block"><?= esc(session('errors.password_confirm')) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Consent -->
                            <div class="col-12">
                                <div class="form-check">
                                    <input
                                        class="form-check-input <?= session('errors.consent') ? 'is-invalid' : '' ?>"
                                        type="checkbox"
                                        id="consent"
                                        name="consent"
                                        value="1"
                                        <?= old('consent') ? 'checked' : '' ?>
                                        required
                                    >

                                    <label class="form-check-label" for="consent">
                                        I agree to the registration terms and consent to my information
                                        being used for this event.
                                        <span class="text-danger">*</span>
                                    </label>

                                    <?php if (session('errors.consent')): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= esc(session('errors.consent')) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12 pt-3">
                                <button type="submit" id="submitBtn" class="btn btn-txp-primary w-100 py-3 fs-6">
                                    Complete Registration
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top border-white border-opacity-10 text-secondary small">
                        Already registered? <a href="<?= site_url('login') ?>" class="text-cyan text-decoration-none fw-semibold">Login with Email or Mobile <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
$extraScripts = '<script src="' . base_url('assets/js/registration.js') . '"></script>';
?>
<?= view('layouts/footer', ['extraScripts' => $extraScripts]) ?>
