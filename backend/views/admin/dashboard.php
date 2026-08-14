<?php
$pageTitle = $pageTitle ?? 'Dashboard';
$pageDescription = $pageDescription ?? 'Admin dashboard overview';
$currentPage = $currentPage ?? 'dashboard';
$assetPath = url('/frontend/assets');
$stats = $stats ?? [];
$recentUsers = $recentUsers ?? [];
$recentSessions = $recentSessions ?? [];
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
.db-stat-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;box-shadow:0 1px 2px rgba(0,0,0,0.04);text-decoration:none;display:block;transition:box-shadow 0.15s}
.db-stat-card:hover{box-shadow:0 4px 12px rgba(0,0,0,0.08)}
.db-stat-card__icon{width:40px;height:40px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:14px}
.db-stat-card__icon--students{background:#e0edff;color:#0054cb}
.db-stat-card__icon--counselors{background:#e0edff;color:#0054cb}
.db-stat-card__icon--sessions{background:#f3f4f6;color:#6b7280}
.db-stat-card__icon--appointments{background:#fef2f2;color:#ef4444}
.db-stat-card__icon svg{width:20px;height:20px}
.db-stat-card__label{font-size:13px;color:#73777f;margin-bottom:4px}
.db-stat-card__value{font-size:28px;font-weight:700;color:#0b1c30;line-height:1.2}
.db-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 1px 2px rgba(0,0,0,0.04)}
.db-card__header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px 0}
.db-card__title{margin:0;font-size:18px;font-weight:700;color:#0b1c30}
.db-card__link{font-size:14px;font-weight:500;color:#0054cb;text-decoration:none}
.db-card__link:hover{text-decoration:underline}
.db-users-table{width:100%;border-collapse:collapse}
.db-users-table th{padding:12px 24px;text-align:left;font-size:11px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;color:#9ca3af;background:#fafbfc;border-bottom:1px solid #e5e7eb}
.db-users-table td{padding:14px 24px;font-size:14px;color:#43474f;border-bottom:1px solid #f3f4f6}
.db-users-table tbody tr:last-child td{border-bottom:none}
.db-users-table tbody tr:hover{background:#fafbff}
.db-user-name{font-weight:600;color:#0b1c30}
.db-user-email{color:#73777f;font-size:13px}
.db-status-dot{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:500}
.db-status-dot__circle{width:8px;height:8px;border-radius:50%}
.db-status-dot--active,.db-status-dot--Active{color:#059669}
.db-status-dot--active .db-status-dot__circle,.db-status-dot--Active .db-status-dot__circle{background:#10b981}
.db-status-dot--inactive,.db-status-dot--Inactive,.db-status-dot--offline,.db-status-dot--Offline{color:#9ca3af}
.db-status-dot--inactive .db-status-dot__circle,.db-status-dot--Inactive .db-status-dot__circle,.db-status-dot--offline .db-status-dot__circle,.db-status-dot--Offline .db-status-dot__circle{background:#d1d5db}
.db-quick-actions{padding:20px 24px;display:flex;flex-direction:column;gap:12px}
.db-action-link{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;height:44px;border-radius:8px;font-size:14px;font-weight:500;cursor:pointer;transition:all 0.15s;text-decoration:none}
.db-action-link--primary{background:#0054cb;color:#fff;border:none}
.db-action-link--primary:hover{background:#004aaf}
.db-action-link--outline{background:#fff;color:#43474f;border:1px solid #e5e7eb}
.db-action-link--outline:hover{background:#f9fafb;border-color:#d1d5db}
.db-action-link svg{width:18px;height:18px;flex-shrink:0}
.db-upcoming{padding:20px 24px;display:flex;flex-direction:column;gap:16px}
.db-session-item{display:flex;align-items:center;gap:12px}
.db-session-avatar{width:40px;height:40px;border-radius:9999px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex:0 0 auto;background:#e0edff;color:#0054cb}
.db-session-info{flex:1;min-width:0}
.db-session-info__name{font-size:14px;font-weight:600;color:#0b1c30;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.db-session-info__counselor{font-size:12px;color:#73777f;margin-top:1px}
.db-session-meta{display:flex;align-items:center;gap:8px;flex:0 0 auto}
.db-session-time{font-size:12px;font-weight:600;color:#0054cb}
.db-session-mode{display:inline-flex;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:500}
.db-session-mode--Video{background:#d1fae5;color:#047857}
.db-session-mode--In-Person{background:#dbeafe;color:#1d4ed8}
.db-upcoming-footer{padding-top:8px}
.db-upcoming-footer a{font-size:14px;font-weight:500;color:#0054cb;text-decoration:none}
.db-upcoming-footer a:hover{text-decoration:underline}
.db-empty{text-align:center;padding:40px 24px;color:#9ca3af;font-size:14px}
@media(max-width:1200px){.db-page{grid-template-columns:1fr}.db-stats{grid-template-columns:repeat(2,1fr)}}
@media(max-width:768px){.db-stats{grid-template-columns:1fr}}
</style>

<div class="db-welcome">
    <h2>Welcome back, Admin</h2>
    <p><?php echo e(date('l, F j, Y')); ?></p>
</div>

<section class="db-stats" aria-label="Dashboard statistics">
    <?php foreach ($stats as $stat): ?>
        <a href="<?php echo e($stat['link'] ?? '#'); ?>" class="db-stat-card">
            <div class="db-stat-card__icon db-stat-card__icon--<?php echo e($stat['icon']); ?>">
                <?php if ($stat['icon'] === 'students'): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                <?php elseif ($stat['icon'] === 'counselors'): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <?php elseif ($stat['icon'] === 'sessions'): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <?php else: ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <?php endif; ?>
            </div>
            <div class="db-stat-card__label"><?php echo e($stat['label']); ?></div>
            <div class="db-stat-card__value"><?php echo e($stat['value']); ?></div>
        </a>
    <?php endforeach; ?>
</section>

<div class="db-page">
    <div class="db-left">
        <div class="db-card">
            <div class="db-card__header">
                <h3 class="db-card__title">Recent Users</h3>
                <a href="<?php echo url('/admin/users'); ?>" class="db-card__link">View All</a>
            </div>
            <?php if (!empty($recentUsers)): ?>
            <table class="db-users-table">
                <thead><tr><th>Name</th><th>Role</th><th>Email</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($recentUsers as $user): ?>
                        <tr>
                            <td class="db-user-name"><?php echo e($user['name']); ?></td>
                            <td><?php echo e(ucfirst($user['role'])); ?></td>
                            <td class="db-user-email"><?php echo e($user['email']); ?></td>
                            <td>
                                <span class="db-status-dot db-status-dot--<?php echo e($user['status'] ?? 'active'); ?>">
                                    <span class="db-status-dot__circle"></span>
                                    <?php echo e(ucfirst($user['status'] ?? 'active')); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="db-empty">No users yet.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="db-right">
        <div class="db-card">
            <div class="db-card__header"><h3 class="db-card__title">Quick Actions</h3></div>
            <div class="db-quick-actions">
                <a href="<?php echo url('/admin/students/create'); ?>" class="db-action-link db-action-link--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                    Add Student
                </a>
                <a href="<?php echo url('/admin/counselors/create'); ?>" class="db-action-link db-action-link--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Add Counselor
                </a>
                <a href="<?php echo url('/admin/sessions/create'); ?>" class="db-action-link db-action-link--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Schedule Session
                </a>
            </div>
        </div>

        <div class="db-card">
            <div class="db-card__header">
                <h3 class="db-card__title">Upcoming Sessions</h3>
                <a href="<?php echo url('/admin/sessions'); ?>" class="db-card__link">View All</a>
            </div>
            <div class="db-upcoming">
                <?php if (!empty($recentSessions)): ?>
                    <?php foreach ($recentSessions as $s): ?>
                        <div class="db-session-item">
                            <div class="db-session-avatar"><?php echo e(substr($s['student_name'] ?? 'NA', 0, 2)); ?></div>
                            <div class="db-session-info">
                                <div class="db-session-info__name"><?php echo e($s['student_name'] ?? 'N/A'); ?></div>
                                <div class="db-session-info__counselor"><?php echo e($s['counselor_name'] ?? 'N/A'); ?></div>
                            </div>
                            <div class="db-session-meta">
                                <span class="db-session-mode db-session-mode--<?php echo e($s['mode'] ?? 'In-Person'); ?>"><?php echo e($s['mode'] ?? '-'); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="db-empty">No upcoming sessions.</div>
                <?php endif; ?>
                <div class="db-upcoming-footer">
                    <a href="<?php echo url('/admin/sessions'); ?>">View All Sessions</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin-layout.php';
