<?php
$pageTitle = $pageTitle ?? 'Counselor Dashboard';
$pageDescription = $pageDescription ?? 'Your counseling overview.';
$currentPage = $currentPage ?? 'dashboard';
$assetPath = url('/frontend/assets');
$stats = $stats ?? [];
$counselor = $counselor ?? null;
$students = $students ?? [];
$sessions = $sessions ?? [];
$upcomingSessions = $upcomingSessions ?? [];
$inquiries = $inquiries ?? [];
$documents = $documents ?? [];
ob_start();
?>
<style>
.db-page{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:24px;align-items:start}
.db-left{display:flex;flex-direction:column;gap:24px;min-width:0}
.db-right{display:flex;flex-direction:column;gap:24px}
.db-welcome{margin-bottom:8px}
.db-welcome h2{margin:0;color:#0b1c30;font-size:32px;font-weight:700;line-height:1.2;letter-spacing:-0.64px}
.db-welcome p{margin:4px 0 0;color:#73777f;font-size:14px}
.db-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px}
.db-stat-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;box-shadow:0 1px 2px rgba(0,0,0,0.04);text-decoration:none;display:block;transition:box-shadow 0.15s}
.db-stat-card:hover{box-shadow:0 4px 12px rgba(0,0,0,0.08)}
.db-stat-card__icon{width:40px;height:40px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:14px}
.db-stat-card__icon--students{background:#e0edff;color:#0054cb}
.db-stat-card__icon--appointments{background:#fef3c7;color:#d97706}
.db-stat-card__icon--inquiries{background:#e0e7ff;color:#4338ca}
.db-stat-card__icon--documents{background:#d1fae5;color:#059669}
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
.db-table tbody tr:hover{background:#fafbff}
.db-student{display:flex;align-items:center;gap:10px}
.db-avatar{width:34px;height:34px;border-radius:9999px;background:#dbeafe;color:#2563eb;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex:0 0 auto}
.db-student-name{font-weight:600;color:#0b1c30;line-height:1.2}
.db-student-sub{font-size:12px;color:#73777f}
.db-status-dot{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:500}
.db-status-dot__circle{width:8px;height:8px;border-radius:50%}
.db-status-dot--active{color:#059669}
.db-status-dot--active .db-status-dot__circle{background:#10b981}
.db-status-dot--inactive{color:#9ca3af}
.db-status-dot--inactive .db-status-dot__circle{background:#d1d5db}
.db-badge{display:inline-flex;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:500}
.db-badge--scheduled{background:#dbeafe;color:#1d4ed8}
.db-badge--completed{background:#d1fae5;color:#059669}
.db-badge--cancelled{background:#fee2e2;color:#dc2626}
.db-badge--in-progress{background:#fef3c7;color:#d97706}
.db-badge--new{background:#fef3c7;color:#d97706}
.db-badge--assigned{background:#e0e7ff;color:#4338ca}
.db-badge--closed{background:#e5e7eb;color:#6b7280}
.db-badge--pending{background:#fef3c7;color:#d97706}
.db-badge--approved{background:#d1fae5;color:#059669}
.db-badge--rejected{background:#fee2e2;color:#dc2626}
.db-badge--resubmit{background:#dbeafe;color:#2563eb}
.db-badge--education{background:#dbeafe;color:#1d4ed8}
.db-badge--visa{background:#fef3c7;color:#d97706}
.db-empty{text-align:center;padding:32px 24px;color:#9ca3af;font-size:14px}
.db-quick-actions{padding:20px 24px;display:flex;flex-direction:column;gap:12px}
.db-action-link{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;height:44px;border-radius:8px;font-size:14px;font-weight:500;cursor:pointer;transition:all 0.15s;text-decoration:none}
.db-action-link--primary{background:#0054cb;color:#fff;border:none}
.db-action-link--primary:hover{background:#004aaf}
.db-action-link--outline{background:#fff;color:#43474f;border:1px solid #e5e7eb}
.db-action-link--outline:hover{background:#f9fafb;border-color:#d1d5db}
.db-action-link svg{width:18px;height:18px;flex-shrink:0}
.db-upcoming{padding:20px 24px;display:flex;flex-direction:column;gap:16px}
.db-session-item{display:flex;align-items:center;gap:12px}
.db-session-info{flex:1;min-width:0}
.db-session-info__name{font-size:14px;font-weight:600;color:#0b1c30;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.db-session-info__date{font-size:12px;color:#73777f;margin-top:1px}
.db-session-meta{display:flex;align-items:center;gap:8px;flex:0 0 auto}
.db-session-time{font-size:12px;font-weight:600;color:#0054cb}
.db-session-mode{display:inline-flex;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:500}
.db-session-mode--video{background:#d1fae5;color:#047857}
.db-session-mode--in-person{background:#dbeafe;color:#1d4ed8}
.db-upcoming-footer{padding-top:8px}
.db-upcoming-footer a{font-size:14px;font-weight:500;color:#0054cb;text-decoration:none}
.db-upcoming-footer a:hover{text-decoration:underline}
.db-profile{padding:20px 24px;display:flex;flex-direction:column;gap:14px}
.db-profile-top{display:flex;align-items:center;gap:12px}
.db-profile-avatar{width:48px;height:48px;border-radius:9999px;background:#e0edff;color:#0054cb;display:inline-flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;flex:0 0 auto}
.db-profile-name{font-size:15px;font-weight:700;color:#0b1c30}
.db-profile-role{font-size:12px;color:#73777f;margin-top:2px}
.db-profile-row{display:flex;justify-content:space-between;font-size:14px}
.db-profile-row span:first-child{color:#73777f}
.db-profile-row span:last-child{color:#0b1c30;font-weight:500}
.db-profile-sep{height:1px;background:#f3f4f6}
@media(max-width:1200px){.db-page{grid-template-columns:1fr}.db-stats{grid-template-columns:repeat(2,1fr)}}
@media(max-width:768px){.db-stats{grid-template-columns:1fr}}
</style>

<div class="db-welcome">
    <h2>Welcome back, <?php echo e($counselor['name'] ?? 'Counselor'); ?></h2>
    <p><?php echo e(date('l, F j, Y')); ?></p>
</div>

<section class="db-stats" aria-label="Dashboard statistics">
    <?php foreach ($stats as $stat): ?>
        <a href="<?php echo e(url($stat['link'] ?? '#')); ?>" class="db-stat-card">
            <div class="db-stat-card__icon db-stat-card__icon--<?php echo e($stat['icon']); ?>">
                <?php if ($stat['icon'] === 'students'): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                <?php elseif ($stat['icon'] === 'appointments'): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <?php elseif ($stat['icon'] === 'inquiries'): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <?php else: ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg>
                <?php endif; ?>
            </div>
            <div class="db-stat-card__label"><?php echo e($stat['label']); ?></div>
            <div class="db-stat-card__value"><?php echo e($stat['value']); ?></div>
        </a>
    <?php endforeach; ?>
</section>

<div class="db-page">
    <div class="db-left">
        <div class="db-card" id="students">
            <div class="db-card__header">
                <h3 class="db-card__title">Assigned Students</h3>
                <a href="<?php echo url('/counselor/students'); ?>" class="db-card__link">View All</a>
            </div>
            <?php if (!empty($students)): ?>
                <table class="db-table">
                    <thead><tr><th>Student</th><th>Student ID</th><th>Education Level</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach ($students as $st): ?>
                            <tr>
                                <td>
                                    <div class="db-student">
                                        <span class="db-avatar"><?php echo e(strtoupper(substr($st['name'], 0, 2))); ?></span>
                                        <a href="<?php echo url('/counselor/students/' . $st['id']); ?>" style="text-decoration:none;color:#0b1c30;font-weight:600;"><?php echo e($st['name']); ?></a>
                                    </div>
                                </td>
                                <td><span class="db-student-sub"><?php echo e($st['student_id'] ?? '-'); ?></span></td>
                                <td><?php echo e($st['education_level'] ?? '-'); ?></td>
                                <td>
                                    <span class="db-status-dot db-status-dot--<?php echo e($st['status'] ?? 'active'); ?>">
                                        <span class="db-status-dot__circle"></span>
                                        <?php echo e(ucfirst($st['status'] ?? 'active')); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="db-empty">No students assigned yet.</div>
            <?php endif; ?>
        </div>

        <div class="db-card">
            <div class="db-card__header">
                <h3 class="db-card__title">Upcoming Sessions</h3>
                <a href="<?php echo url('/counselor/sessions'); ?>" class="db-card__link">View All</a>
            </div>
            <?php if (!empty($upcomingSessions)): ?>
                <div class="db-upcoming">
                    <?php foreach ($upcomingSessions as $s): ?>
                        <div class="db-session-item">
                            <span class="db-avatar"><?php echo e(strtoupper(substr($s['student_name'] ?? '?', 0, 2))); ?></span>
                            <div class="db-session-info">
                                <div class="db-session-info__name"><?php echo e($s['student_name'] ?? '-'); ?></div>
                                <div class="db-session-info__date"><?php echo e($s['subject'] ?? date('D, M j', strtotime($s['datetime']))); ?></div>
                            </div>
                            <div class="db-session-meta">
                                <span class="db-session-time"><?php echo e(date('g:i A', strtotime($s['datetime']))); ?></span>
                                <span class="db-session-mode db-session-mode--<?php echo e(($s['mode'] ?? 'In-Person') === 'Video Call' ? 'video' : 'in-person'); ?>"><?php echo e($s['mode'] ?? '-'); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="db-empty">No upcoming sessions.</div>
            <?php endif; ?>
        </div>

        <div class="db-card">
            <div class="db-card__header">
                <h3 class="db-card__title">Recent Documents</h3>
                <a href="<?php echo url('/counselor/documents'); ?>" class="db-card__link">View All</a>
            </div>
            <?php if (!empty($documents)): ?>
                <table class="db-table">
                    <thead><tr><th>Document</th><th>Student</th><th>Category</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach ($documents as $doc): ?>
                            <tr>
                                <td><span style="font-weight:500;color:#0b1c30;"><?php echo e($doc['name']); ?></span></td>
                                <td><span class="db-student-sub"><?php echo e($doc['student_name'] ?? '-'); ?></span></td>
                                <td><span class="db-badge db-badge--<?php echo e($doc['category']); ?>"><?php echo e(ucfirst($doc['category'])); ?></span></td>
                                <td><span class="db-badge db-badge--<?php echo e($doc['status']); ?>"><?php echo e(ucfirst($doc['status'])); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="db-empty">No documents yet. Assign a required document from the actions menu.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="db-right">
        <div class="db-card">
            <div class="db-card__header"><h3 class="db-card__title">Quick Actions</h3></div>
            <div class="db-quick-actions">
                <a href="<?php echo url('/counselor/sessions/create'); ?>" class="db-action-link db-action-link--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Schedule Session
                </a>
                <a href="<?php echo url('/counselor/documents/assign'); ?>" class="db-action-link db-action-link--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
                    Assign Required Document
                </a>
                <a href="<?php echo url('/counselor/documents'); ?>" class="db-action-link db-action-link--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    View Documents
                </a>
                <a href="<?php echo url('/counselor/sessions'); ?>" class="db-action-link db-action-link--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    My Sessions
                </a>
            </div>
        </div>

        <div class="db-card">
            <div class="db-card__header">
                <h3 class="db-card__title">Inquiry Queue</h3>
                <a href="<?php echo url('/counselor/inquiries'); ?>" class="db-card__link">View All</a>
            </div>
            <?php if (!empty($inquiries)): ?>
                <table class="db-table">
                    <thead><tr><th>Student</th><th>Country</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach ($inquiries as $inq): ?>
                            <tr>
                                <td><span style="font-weight:500;color:#0b1c30;"><?php echo e($inq['student_name'] ?? '-'); ?></span></td>
                                <td><?php echo e($inq['country_of_interest'] ?? '-'); ?></td>
                                <td><span class="db-badge db-badge--<?php echo e($inq['status']); ?>"><?php echo e(ucwords(str_replace('-', ' ', $inq['status']))); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="db-empty">No inquiries assigned.</div>
            <?php endif; ?>
        </div>

        <div class="db-card">
            <div class="db-card__header"><h3 class="db-card__title">My Profile</h3></div>
            <div class="db-profile">
                <div class="db-profile-top">
                    <span class="db-profile-avatar"><?php echo e(strtoupper(substr($counselor['name'] ?? 'C', 0, 2))); ?></span>
                    <div>
                        <div class="db-profile-name"><?php echo e($counselor['name'] ?? '-'); ?></div>
                        <div class="db-profile-role"><?php echo e($counselor['specialization'] ?? 'Counselor'); ?></div>
                    </div>
                </div>
                <div class="db-profile-sep"></div>
                <div class="db-profile-row"><span>Email</span><span><?php echo e($counselor['email'] ?? '-'); ?></span></div>
                <div class="db-profile-row"><span>Max Students</span><span><?php echo e($counselor['max_students'] ?? '-'); ?></span></div>
                <div class="db-profile-row"><span>Status</span><span><?php echo e(ucfirst($counselor['status'] ?? 'available')); ?></span></div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/counselor-layout.php';
