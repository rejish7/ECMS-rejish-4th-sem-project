<?php
$pageTitle = $pageTitle ?? 'Student Dashboard';
$pageDescription = $pageDescription ?? 'Your counseling overview.';
$currentPage = $currentPage ?? 'dashboard';
$assetPath = url('/frontend/assets');
$stats = $stats ?? [];
$student = $student ?? null;
$sessions = $sessions ?? [];
$documents = $documents ?? [];
$inquiries = $inquiries ?? [];
ob_start();
?>
<style>
.db-page{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start}
.db-left{display:flex;flex-direction:column;gap:24px;min-width:0}
.db-right{display:flex;flex-direction:column;gap:24px}
.db-welcome{margin-bottom:8px}
.db-welcome h2{margin:0;color:#0b1c30;font-size:32px;font-weight:700;line-height:1.2;letter-spacing:-0.64px}
.db-welcome p{margin:4px 0 0;color:#73777f;font-size:14px}
.db-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px}
.db-stat-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;box-shadow:0 1px 2px rgba(0,0,0,0.04);display:block}
.db-stat-card__icon{width:40px;height:40px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:14px}
.db-stat-card__icon--sessions{background:#e0edff;color:#0054cb}
.db-stat-card__icon--upcoming{background:#fef3c7;color:#d97706}
.db-stat-card__icon--documents{background:#d1fae5;color:#059669}
.db-stat-card__icon--inquiries{background:#f3f4f6;color:#6b7280}
.db-stat-card__icon svg{width:20px;height:20px}
.db-stat-card__label{font-size:13px;color:#73777f;margin-bottom:4px}
.db-stat-card__value{font-size:28px;font-weight:700;color:#0b1c30;line-height:1.2}
.db-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 1px 2px rgba(0,0,0,0.04)}
.db-card__header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px 0}
.db-card__title{margin:0;font-size:18px;font-weight:700;color:#0b1c30}
.db-card__link{font-size:14px;font-weight:500;color:#0054cb;text-decoration:none}
.db-card__link:hover{text-decoration:underline}
.db-table{width:100%;border-collapse:collapse}
.db-table th{padding:12px 24px;text-align:left;font-size:11px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;color:#9ca3af;background:#fafbfc;border-bottom:1px solid #e5e7eb}
.db-table td{padding:14px 24px;font-size:14px;color:#43474f;border-bottom:1px solid #f3f4f6}
.db-table tbody tr:last-child td{border-bottom:none}
.db-badge{display:inline-flex;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:500}
.db-badge--scheduled{background:#dbeafe;color:#1d4ed8}
.db-badge--completed{background:#d1fae5;color:#059669}
.db-badge--cancelled{background:#fee2e2;color:#dc2626}
.db-badge--in-progress{background:#fef3c7;color:#d97706}
.db-badge--pending{background:#fef3c7;color:#d97706}
.db-badge--approved{background:#d1fae5;color:#059669}
.db-badge--rejected{background:#fee2e2;color:#dc2626}
.db-badge--resubmit{background:#dbeafe;color:#2563eb}
.db-badge--new{background:#dbeafe;color:#2563eb}
.db-badge--assigned{background:#fef3c7;color:#d97706}
.db-badge--closed{background:#e5e7eb;color:#6b7280}
.db-badge--education{background:#dbeafe;color:#1d4ed8}
.db-badge--visa{background:#fef3c7;color:#d97706}
.db-empty{text-align:center;padding:32px 24px;color:#9ca3af;font-size:14px}
.db-profile{padding:20px 24px;display:flex;flex-direction:column;gap:12px}
.db-profile-row{display:flex;justify-content:space-between;font-size:14px}
.db-profile-row span:first-child{color:#73777f}
.db-profile-row span:last-child{color:#0b1c30;font-weight:500}
@media(max-width:1200px){.db-page{grid-template-columns:1fr}.db-stats{grid-template-columns:repeat(2,1fr)}}
@media(max-width:768px){.db-stats{grid-template-columns:1fr}}
</style>

<div class="db-welcome">
    <h2>Welcome back, <?php echo e($student['name'] ?? 'Student'); ?></h2>
    <p><?php echo e(date('l, F j, Y')); ?></p>
</div>

<section class="db-stats" aria-label="Dashboard statistics">
    <?php foreach ($stats as $stat): ?>
        <div class="db-stat-card">
            <div class="db-stat-card__icon db-stat-card__icon--<?php echo e($stat['icon']); ?>">
                <?php if ($stat['icon'] === 'sessions'): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <?php elseif ($stat['icon'] === 'upcoming'): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <?php elseif ($stat['icon'] === 'documents'): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg>
                <?php else: ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <?php endif; ?>
            </div>
            <div class="db-stat-card__label"><?php echo e($stat['label']); ?></div>
            <div class="db-stat-card__value"><?php echo e($stat['value']); ?></div>
        </div>
    <?php endforeach; ?>
</section>

<div class="db-page">
    <div class="db-left">
        <div class="db-card">
            <div class="db-card__header"><h3 class="db-card__title">Counseling Sessions</h3><a class="db-card__link" href="<?php echo url('/student/sessions'); ?>">View All</a></div>
            <?php if (!empty($sessions)): ?>
                <table class="db-table">
                    <thead><tr><th>Date &amp; Time</th><th>Mode</th><th>Counselor</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach ($sessions as $s): ?>
                            <tr>
                                <td><?php echo e(date('M d, Y g:i A', strtotime($s['datetime']))); ?></td>
                                <td><?php echo e($s['mode'] ?? '-'); ?></td>
                                <td><?php echo e($s['counselor_name'] ?? '-'); ?></td>
                                <td><span class="db-badge db-badge--<?php echo e($s['status']); ?>"><?php echo e(ucfirst($s['status'])); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="db-empty">No counseling sessions yet.</div>
            <?php endif; ?>
        </div>

        <div class="db-card">
            <div class="db-card__header"><h3 class="db-card__title">My Documents</h3><a class="db-card__link" href="<?php echo url('/student/documents'); ?>">View All</a></div>
            <?php if (!empty($documents)): ?>
                <table class="db-table">
                    <thead><tr><th>Name</th><th>Category</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach ($documents as $doc): ?>
                            <tr>
                                <td><?php echo e($doc['name']); ?></td>
                                <td><span class="db-badge db-badge--<?php echo e($doc['category']); ?>"><?php echo e(ucfirst($doc['category'])); ?></span></td>
                                <td><span class="db-badge db-badge--<?php echo e($doc['status']); ?>"><?php echo e(ucfirst($doc['status'])); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="db-empty">No documents uploaded yet.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="db-right">
        <div class="db-card">
            <div class="db-card__header"><h3 class="db-card__title">My Profile</h3></div>
            <div class="db-profile">
                <div class="db-profile-row"><span>Student ID</span><span><?php echo e($student['student_id'] ?? '-'); ?></span></div>
                <div class="db-profile-row"><span>Email</span><span><?php echo e($student['email'] ?? '-'); ?></span></div>
                <div class="db-profile-row"><span>Education Level</span><span><?php echo e($student['education_level'] ?? '-'); ?></span></div>
                <div class="db-profile-row"><span>Counselor</span><span><?php echo e($student['counselor_name'] ?? '-'); ?></span></div>
                <div class="db-profile-row"><span>Status</span><span><?php echo e(ucfirst($student['status'] ?? 'active')); ?></span></div>
            </div>
        </div>

        <div class="db-card">
            <div class="db-card__header"><h3 class="db-card__title">My Inquiries</h3><a class="db-card__link" href="<?php echo url('/student/inquiries'); ?>">View All</a></div>
            <?php if (!empty($inquiries)): ?>
                <table class="db-table">
                    <thead><tr><th>Country</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach ($inquiries as $inq): ?>
                            <tr>
                                <td><?php echo e($inq['country_of_interest'] ?? '-'); ?></td>
                                <td><span class="db-badge db-badge--<?php echo e($inq['status']); ?>"><?php echo e(ucfirst($inq['status'])); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="db-empty">No inquiries submitted.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/student-layout.php';
