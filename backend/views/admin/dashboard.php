<?php
$pageTitle = 'Dashboard';
$pageDescription = 'Admin dashboard overview';
$currentPage = 'dashboard';

$assetPath = url('/frontend/assets');

$stats = [
    ['label' => 'Total Students', 'value' => '1,240', 'icon' => 'students', 'color' => '#0054cb'],
    ['label' => 'Total Counselors', 'value' => '45', 'icon' => 'counselors', 'color' => '#0054cb'],
    ['label' => 'Counseling Sessions', 'value' => '312', 'icon' => 'sessions', 'color' => '#6b7280'],
    ['label' => 'Upcoming Appointments', 'value' => '18', 'icon' => 'appointments', 'color' => '#ef4444'],
];

$recentUsers = [
    ['name' => 'Sarah Jenkins', 'role' => 'Student', 'email' => 's.jenkins@edu.com', 'lastActive' => '2 mins ago', 'status' => 'Active', 'statusTone' => 'active'],
    ['name' => 'Dr. Michael Chen', 'role' => 'Counselor', 'email' => 'm.chen@ecms.org', 'lastActive' => '1 hour ago', 'status' => 'Active', 'statusTone' => 'active'],
    ['name' => 'Emily Rodriguez', 'role' => 'Student', 'email' => 'e.rod@edu.com', 'lastActive' => '1 day ago', 'status' => 'Offline', 'statusTone' => 'offline'],
];

$upcomingSessions = [
    ['student' => 'Sarah Jenkins', 'initials' => 'SJ', 'avatarClass' => 'db-session-avatar--blue', 'counselor' => 'Dr. Michael Chen', 'time' => '10:00 AM', 'mode' => 'Online', 'modeTone' => 'online'],
    ['student' => 'David Kim', 'initials' => 'DK', 'avatarClass' => 'db-session-avatar--gray', 'counselor' => 'Prof. Alan Turing', 'time' => '2:30 PM', 'mode' => 'In-Person', 'modeTone' => 'in-person'],
];

$systemActivities = [
    ['text' => 'New Student registered via portal', 'time' => '10 mins ago', 'active' => true],
    ['text' => 'System Backup completed successfully', 'time' => '2 hours ago', 'active' => false],
    ['text' => 'Document Uploaded by Emily Rodriguez', 'time' => 'Yesterday, 4:15 PM', 'active' => false],
];

ob_start();
?>
<style>
    .db-page {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 24px;
        align-items: start;
    }

    .db-left {
        display: flex;
        flex-direction: column;
        gap: 24px;
        min-width: 0;
    }

    .db-right {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .db-welcome {
        margin-bottom: 8px;
    }

    .db-welcome h2 {
        margin: 0;
        color: #0b1c30;
        font-size: 32px;
        font-weight: 700;
        line-height: 1.2;
        letter-spacing: -0.64px;
    }

    .db-welcome p {
        margin: 4px 0 0;
        color: #73777f;
        font-size: 14px;
    }

    .db-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .db-stat-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }

    .db-stat-card__icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }

    .db-stat-card__icon--students { background: #e0edff; color: #0054cb; }
    .db-stat-card__icon--counselors { background: #e0edff; color: #0054cb; }
    .db-stat-card__icon--sessions { background: #f3f4f6; color: #6b7280; }
    .db-stat-card__icon--appointments { background: #fef2f2; color: #ef4444; }

    .db-stat-card__icon svg {
        width: 20px;
        height: 20px;
    }

    .db-stat-card__label {
        font-size: 13px;
        color: #73777f;
        margin-bottom: 4px;
    }

    .db-stat-card__value {
        font-size: 28px;
        font-weight: 700;
        color: #0b1c30;
        line-height: 1.2;
    }

    .db-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }

    .db-card__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px 0;
    }

    .db-card__title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0b1c30;
    }

    .db-card__link {
        font-size: 14px;
        font-weight: 500;
        color: #0054cb;
    }

    .db-card__link:hover {
        text-decoration: underline;
    }

    /* Chart */
    .db-chart {
        padding: 24px;
    }

    .db-chart-bars {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        height: 180px;
        padding-top: 16px;
    }

    .db-chart-bar-wrap {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        height: 100%;
        justify-content: flex-end;
    }

    .db-chart-bar {
        width: 100%;
        max-width: 52px;
        border-radius: 6px 6px 0 0;
        background: #002549;
        transition: opacity 0.2s;
    }

    .db-chart-bar:hover {
        opacity: 0.85;
    }

    .db-chart-bar--highlight {
        background: #3b82f6;
    }

    .db-chart-label {
        font-size: 11px;
        color: #9ca3af;
    }

    /* Recent Users */
    .db-users-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .db-users-table col:nth-child(1) { width: 24%; }
    .db-users-table col:nth-child(2) { width: 13%; }
    .db-users-table col:nth-child(3) { width: 24%; }
    .db-users-table col:nth-child(4) { width: 12%; }
    .db-users-table col:nth-child(5) { width: 12%; }
    .db-users-table col:nth-child(6) { width: 15%; }

    .db-users-table th {
        padding: 12px 24px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #9ca3af;
        background: #fafbfc;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .db-users-table th:last-child {
        text-align: right;
    }

    .db-users-table td {
        padding: 14px 24px;
        font-size: 14px;
        color: #43474f;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .db-users-table tbody tr:last-child td {
        border-bottom: none;
    }

    .db-users-table tbody tr:hover {
        background: #fafbff;
    }

    .db-user-name {
        font-weight: 600;
        color: #0b1c30;
        white-space: nowrap;
    }

    .db-user-email {
        color: #73777f;
        font-size: 13px;
        white-space: nowrap;
    }

    .db-user-last-active {
        color: #73777f;
        font-size: 13px;
        white-space: nowrap;
    }

    .db-actions {
        display: flex;
        gap: 4px;
        justify-content: flex-end;
    }

    .db-action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: none;
        background: none;
        cursor: pointer;
        transition: background 0.15s;
    }

    .db-action-btn:hover {
        background: #f3f4f6;
    }

    .db-action-btn svg {
        width: 16px;
        height: 16px;
    }

    .db-action-btn--view svg { color: #2563eb; }
    .db-action-btn--edit svg { color: #6b7280; }
    .db-action-btn--delete svg { color: #ef4444; }

    .db-status-dot {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 500;
    }

    .db-status-dot__circle {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .db-status-dot--active { color: #059669; }
    .db-status-dot--active .db-status-dot__circle { background: #10b981; }
    .db-status-dot--offline { color: #9ca3af; }
    .db-status-dot--offline .db-status-dot__circle { background: #d1d5db; }

    /* Quick Actions */
    .db-quick-actions {
        padding: 20px 24px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .db-action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        height: 44px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s;
    }

    .db-action-btn--primary {
        background: #0054cb;
        color: #fff;
        border: none;
    }

    .db-action-btn--primary:hover {
        background: #004aaf;
    }

    .db-action-btn--outline {
        background: #fff;
        color: #43474f;
        border: 1px solid #e5e7eb;
    }

    .db-action-btn--outline:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .db-action-btn svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    /* Upcoming Sessions */
    .db-upcoming {
        padding: 20px 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .db-session-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .db-session-avatar {
        width: 40px;
        height: 40px;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        flex: 0 0 auto;
    }

    .db-session-avatar--blue { background: #e0edff; color: #0054cb; }
    .db-session-avatar--gray { background: #f3f4f6; color: #6b7280; }

    .db-session-info {
        flex: 1;
        min-width: 0;
    }

    .db-session-info__name {
        font-size: 14px;
        font-weight: 600;
        color: #0b1c30;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .db-session-info__counselor {
        font-size: 12px;
        color: #73777f;
        margin-top: 1px;
    }

    .db-session-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 0 0 auto;
    }

    .db-session-time {
        font-size: 12px;
        font-weight: 600;
        color: #0054cb;
    }

    .db-session-mode {
        display: inline-flex;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
    }

    .db-session-mode--online { background: #d1fae5; color: #047857; }
    .db-session-mode--in-person { background: #dbeafe; color: #1d4ed8; }

    .db-upcoming-footer {
        padding-top: 8px;
    }

    .db-upcoming-footer a {
        font-size: 14px;
        font-weight: 500;
        color: #0054cb;
    }

    .db-upcoming-footer a:hover {
        text-decoration: underline;
    }

    /* System Activities */
    .db-activities {
        padding: 20px 24px;
    }

    .db-activity-list {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .db-activity-item {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .db-activity-item:last-child {
        border-bottom: none;
    }

    .db-activity-timeline {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 4px;
    }

    .db-activity-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .db-activity-dot--active {
        background: #0054cb;
        box-shadow: 0 0 0 3px rgba(0,84,203,0.15);
    }

    .db-activity-dot--idle {
        background: #e5e7eb;
    }

    .db-activity-content {
        flex: 1;
        min-width: 0;
    }

    .db-activity-text {
        font-size: 13px;
        font-weight: 500;
        color: #0b1c30;
        line-height: 1.4;
    }

    .db-activity-time {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 2px;
    }

    @media (max-width: 1200px) {
        .db-page {
            grid-template-columns: 1fr;
        }
        .db-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .db-stats {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="db-welcome">
    <h2>Welcome back, Admin</h2>
    <p><?php echo e(date('l, F j, Y')); ?></p>
</div>

<section class="db-stats" aria-label="Dashboard statistics">
    <?php foreach ($stats as $stat): ?>
        <article class="db-stat-card">
            <div class="db-stat-card__icon db-stat-card__icon--<?php echo e($stat['icon']); ?>">
                <?php if ($stat['icon'] === 'students'): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                <?php elseif ($stat['icon'] === 'counselors'): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                <?php elseif ($stat['icon'] === 'sessions'): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                <?php else: ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                <?php endif; ?>
            </div>
            <div class="db-stat-card__label"><?php echo e($stat['label']); ?></div>
            <div class="db-stat-card__value"><?php echo e($stat['value']); ?></div>
        </article>
    <?php endforeach; ?>
</section>

<div class="db-page">
    <div class="db-left">
        <div class="db-card">
            <div class="db-card__header">
                <h3 class="db-card__title">Monthly Counseling Sessions</h3>
            </div>
            <div class="db-chart">
                <div class="db-chart-bars">
                    <?php
                    $barHeights = [65, 80, 70, 85, 75, 90, 100];
                    $barLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul'];
                    foreach ($barHeights as $i => $height):
                        $isHighlight = ($i === 5);
                    ?>
                        <div class="db-chart-bar-wrap">
                            <div class="db-chart-bar<?php echo $isHighlight ? ' db-chart-bar--highlight' : ''; ?>" style="height:<?php echo e($height); ?>%"></div>
                            <span class="db-chart-label"><?php echo e($barLabels[$i]); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="db-card">
            <div class="db-card__header">
                <h3 class="db-card__title">Recent Users</h3>
                <a href="<?php echo e(url('/admin/users')); ?>" class="db-card__link">View All</a>
            </div>
            <table class="db-users-table">
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Last Active</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentUsers as $user): ?>
                        <tr>
                            <td class="db-user-name"><?php echo e($user['name']); ?></td>
                            <td><?php echo e($user['role']); ?></td>
                            <td class="db-user-email"><?php echo e($user['email']); ?></td>
                            <td class="db-user-last-active"><?php echo e($user['lastActive']); ?></td>
                            <td>
                                <span class="db-status-dot db-status-dot--<?php echo e($user['statusTone']); ?>">
                                    <span class="db-status-dot__circle"></span>
                                    <?php echo e($user['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="db-right">
        <div class="db-card">
            <div class="db-card__header">
                <h3 class="db-card__title">Quick Actions</h3>
            </div>
            <div class="db-quick-actions">
                <button type="button" class="db-action-btn db-action-btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="8.5" cy="7" r="4"/>
                        <line x1="20" y1="8" x2="20" y2="14"/>
                        <line x1="23" y1="11" x2="17" y2="11"/>
                    </svg>
                    Add Student
                </button>
                <button type="button" class="db-action-btn db-action-btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Add Counselor
                </button>
                <button type="button" class="db-action-btn db-action-btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Schedule Session
                </button>
            </div>
        </div>

        <div class="db-card">
            <div class="db-card__header">
                <h3 class="db-card__title">Upcoming Sessions</h3>
            </div>
            <div class="db-upcoming">
                <?php foreach ($upcomingSessions as $session): ?>
                    <div class="db-session-item">
                        <div class="db-session-avatar <?php echo e($session['avatarClass']); ?>">
                            <?php echo e($session['initials']); ?>
                        </div>
                        <div class="db-session-info">
                            <div class="db-session-info__name"><?php echo e($session['student']); ?></div>
                            <div class="db-session-info__counselor"><?php echo e($session['counselor']); ?></div>
                        </div>
                        <div class="db-session-meta">
                            <span class="db-session-time"><?php echo e($session['time']); ?></span>
                            <span class="db-session-mode db-session-mode--<?php echo e($session['modeTone']); ?>"><?php echo e($session['mode']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="db-upcoming-footer">
                    <a href="<?php echo e(url('/admin/appointments')); ?>">View Calendar</a>
                </div>
            </div>
        </div>

        <div class="db-card">
            <div class="db-card__header">
                <h3 class="db-card__title">System Activities</h3>
            </div>
            <div class="db-activities">
                <div class="db-activity-list">
                    <?php foreach ($systemActivities as $activity): ?>
                        <div class="db-activity-item">
                            <div class="db-activity-timeline">
                                <span class="db-activity-dot db-activity-dot--<?php echo e($activity['active'] ? 'active' : 'idle'); ?>"></span>
                            </div>
                            <div class="db-activity-content">
                                <div class="db-activity-text"><?php echo e($activity['text']); ?></div>
                                <div class="db-activity-time"><?php echo e($activity['time']); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();

include __DIR__ . '/../layouts/admin-layout.php';
