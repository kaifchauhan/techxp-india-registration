<?= view('layouts/header', ['pageTitle' => $pageTitle]) ?>
<?= view('layouts/navbar') ?>

<main class="py-4">
    <div class="container-fluid px-lg-5">
        <!-- Top Status Banner with Attendee Welcome & Venue Climate -->
        <div class="row g-3 mb-4 align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div>
                        <h2 class="text-white fw-bold mb-0">
                            Welcome, <span class="text-gradient-cyan"><?= esc($attendee['full_name']) ?></span>
                        </h2>
                        <div class="text-secondary small mt-1">
                            <span>Badge ID: <strong class="text-white"><?= esc($attendee['registration_id']) ?></strong></span>
                            <span class="mx-2">&bull;</span>
                            <span>Country: <?= esc($attendee['city']) ?>, <?= esc($attendee['country']) ?><span>
                            <?php if (!empty($attendee['company'])): ?>
                                <span class="mx-2">&bull;</span>
                                <span><?= esc($attendee['company']) ?> (<?= esc($attendee['designation'] ?? 'Delegate') ?>)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Third-Party REST API Live Venue Weather Bar -->
            <div class="col-lg-4 text-lg-end">
                <?php if (!empty($weather)): ?>
                    <div class="d-inline-flex align-items-center gap-3 p-2 px-3 rounded-3 txp-card border border-info border-opacity-25 text-start">
                        <i class="bi <?= $weather['icon'] ?> fs-2"></i>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold text-white fs-5"><?= $weather['temperature'] ?>&deg;C</span>
                                <span class="badge bg-info-subtle text-info" style="font-size: 0.65rem;">Mumbai Venue</span>
                            </div>
                            <div class="text-secondary" style="font-size: 0.75rem;">
                                <?= esc($weather['condition']) ?> &bull; Humidity <?= $weather['humidity'] ?>%
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- MAIN EVENT CONTENT GRID -->
        <div class="row g-4">
            <!-- LEFT 8 COLS: EXTERNAL IFRAME STREAM & ATTENDEE DETAILS -->
            <div class="col-xl-8">
                <!-- REQUIREMENT 7: External Live Stream Iframe -->
                <div class="txp-card p-3 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 px-2">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="text-white mb-0">Thank You CR7</h5>
                        </div>

                    </div>

                    <!-- External Iframe Component (Tech Summit Video) -->
                    <div class="stream-container">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/5hFd6zGkxLE?si=SAxaL1_vbNYD9XvT" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                    

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-3 px-2">
                        <div>
                            <h6 class="text-white mb-1">Thank You CR7 </h6>
                            <span class="text-secondary small">
Real Madrid C. F. inform that, as per Cristiano Ronaldo's wishes and at his request, the club has allowed the player to complete his move to Juventus F. C.</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-txp-cyan btn-sm" data-bs-toggle="modal" data-bs-target="#profileModal">
                                <i class="bi bi-person-gear me-1"></i>Edit Profile
                            </button>
                        </div>
                    </div>
                </div>

                <!-- REQUIREMENT 6: Attendee Information & Digital Credentials Card -->
                <div class="row g-4">
                    <div class="col-md-12">
                        <div class="txp-card p-4 h-100">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-white border-opacity-10">
                                <h5 class="text-white mb-0"><i class="bi bi-person-lines-fill text-gradient-primary me-2"></i>Attendee Information</h5>
                            </div>

                            <div class="row g-3 small">
                                <div class="col-sm-6">
                                    <span class="text-secondary d-block">Full Name</span>
                                    <strong class="text-white fs-6"><?= esc($attendee['full_name']) ?></strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-secondary d-block">Badge Identifier</span>
                                    <span class="font-monospace text-cyan fw-bold"><?= esc($attendee['registration_id']) ?></span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-secondary d-block">Registered Email</span>
                                    <span class="text-white"><?= esc($attendee['email']) ?></span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-secondary d-block">Mobile Number</span>
                                    <span class="text-white">+91 <?= esc($attendee['mobile']) ?></span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-secondary d-block">Organization</span>
                                    <span class="text-white"><?= esc($attendee['company'] ?: 'Independent Delegate') ?></span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-secondary d-block">Designation</span>
                                    <span class="text-white"><?= esc($attendee['designation'] ?: 'Software Engineer') ?></span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-secondary d-block">Country</span>
                                    <span class="badge bg-primary text-white"><?= esc($attendee['city']) ?>, <?= esc($attendee['country']) ?></span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-secondary d-block">Registration Date</span>
                                    <span class="text-white"><?= date('d M Y, h:i A', strtotime($attendee['created_at'])) ?></span>
                                </div>
                                <?php if (!empty($attendee['interests'])): ?>
                                    <div class="col-12 pt-2 border-top border-white border-opacity-10">
                                        <span class="text-secondary d-block mb-1">Topics of Interest</span>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php foreach (explode(',', $attendee['interests']) as $interest): ?>
                                                <span class="badge bg-dark border border-secondary text-secondary"><?= esc(trim($interest)) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT 4 COLS: REQUIREMENT 8 - JAVASCRIPT CHAT-STYLE COMPONENT -->
            <div class="col-xl-4">
                <div class="txp-card chat-card">
                    <!-- Chat Header -->
                    <div class="p-3 border-bottom border-white border-opacity-10 d-flex align-items-center justify-content-between bg-black bg-opacity-20">
                        <div class="d-flex align-items-center gap-2">
                            <div>
                                <h6 class="text-white mb-0">Live Summit Chat</h6>
                            </div>
                        </div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.7rem;">Live</span>
                    </div>

                    <!-- Chat Messages Stream Container -->
                    <div id="chatMessagesContainer" class="chat-messages-container">
                        <?php if (!empty($initialChat)): ?>
                            <?php foreach ($initialChat as $msg): ?>
                                <?php
                                $isSelf = ($attendee['full_name'] === $msg['sender_name']);
                                $bubbleClass = 'chat-bubble-other';
                                $roleBadgeClass = 'bg-secondary';
                                $roleName = $msg['sender_role'];

                                if ($isSelf) {
                                    $bubbleClass = 'chat-bubble-self';
                                } elseif ($msg['sender_role'] === 'bot') {
                                    $bubbleClass = 'chat-bubble-bot';
                                    $roleBadgeClass = 'chat-role-bot';
                                    $roleName = 'TechXP Bot';
                                } elseif ($msg['sender_role'] === 'admin') {
                                    $roleBadgeClass = 'chat-role-admin';
                                    $roleName = 'Organizer';
                                } elseif ($msg['sender_role'] === 'speaker') {
                                    $roleBadgeClass = 'chat-role-speaker';
                                    $roleName = 'Speaker';
                                } elseif ($msg['sender_role'] === 'vip') {
                                    $roleBadgeClass = 'chat-role-vip';
                                    $roleName = 'VIP';
                                }
                                ?>
                                <div class="chat-bubble <?= $bubbleClass ?>" data-message-id="<?= $msg['id'] ?>">
                                    <div class="chat-meta">
                                        <span class="chat-sender"><?= esc($msg['sender_name']) ?></span>
                                        <!-- <span class="chat-role-badge <?= $roleBadgeClass ?>"><?= $roleName ?></span> -->
                                        <span class="chat-time ms-auto"><?= date('h:i A', strtotime($msg['created_at'])) ?></span>
                                    </div>
                                    <div class="chat-body-text"><?= esc($msg['message']) ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Smart Concierge Quick Prompt Chips -->
                    <div class="px-3 py-2 bg-black bg-opacity-30 border-top border-white border-opacity-10 d-flex gap-1 overflow-x-auto" style="scrollbar-width: none;">
                        <button type="button" class="btn btn-sm btn-dark text-secondary border border-secondary border-opacity-25 chat-suggestion-chip text-nowrap py-1 px-2" style="font-size: 0.72rem;" data-prompt="What is the summit Wi-Fi?">
                            Wi-Fi password?
                        </button>
                        <button type="button" class="btn btn-sm btn-dark text-secondary border border-secondary border-opacity-25 chat-suggestion-chip text-nowrap py-1 px-2" style="font-size: 0.72rem;" data-prompt="When is the networking lunch?">
                            Lunch time?
                        </button>
                        <button type="button" class="btn btn-sm btn-dark text-secondary border border-secondary border-opacity-25 chat-suggestion-chip text-nowrap py-1 px-2" style="font-size: 0.72rem;" data-prompt="What is today's keynote agenda?">
                            Today's Agenda?
                        </button>
                    </div>

                    <!-- Chat Input Form -->
                    <div class="p-3 border-top border-white border-opacity-10 bg-black bg-opacity-20">
                        <form id="chatForm">
                            <div class="input-group">
                                <input type="text" id="chatInput" class="form-control" placeholder="Type a message or question..." maxlength="500" required autocomplete="off">
                                <button type="submit" id="chatSendBtn" class="btn btn-txp-primary px-3">
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Profile Update Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content txp-card border-secondary">
            <div class="modal-header border-white border-opacity-10">
                <h5 class="modal-title text-white" id="profileModalLabel"><i class="bi bi-person-gear me-2 text-cyan"></i>Update Attendee Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('profile/update') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_company" class="form-label">Company / Organization</label>
                        <input type="text" class="form-control" id="modal_company" name="company" value="<?= esc($attendee['company']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="modal_designation" class="form-label">Job Title / Designation</label>
                        <input type="text" class="form-control" id="modal_designation" name="designation" value="<?= esc($attendee['designation']) ?>">
                    </div>
                </div>
                <div class="modal-footer border-white border-opacity-10">
                    <button type="button" class="btn btn-txp-outline btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-txp-primary btn-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$extraScripts = '<script src="' . base_url('assets/js/chat.js') . '"></script>';
?>
<?= view('layouts/footer', ['extraScripts' => $extraScripts]) ?>
