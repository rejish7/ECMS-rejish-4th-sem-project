<?php
$pageTitle = 'Dashboard';
$pageDescription = 'Admin dashboard overview';
$currentPage = 'dashboard';

$assetPath = 'frontend/assets';
$imagePath = $assetPath . '/images/user-management';

function dash_asset_url($base, $file) {
    return $base . '/' . $file;
}

$stats = [
    ['label' => 'Total Students', 'value' => '1,248', 'icon' => 'sidebar-students.svg', 'change' => '+12 this week'],
    ['label' => 'Active Counselors', 'value' => '24', 'icon' => 'sidebar-counselors.svg', 'change' => '+2 this month'],
    ['label' => 'Sessions Today', 'value' => '18', 'icon' => 'sidebar-sessions.svg', 'change' => '3 pending'],
    ['label' => 'Pending Appointments', 'value' => '42', 'icon' => 'sidebar-appointments.svg', 'change' => '8 urgent'],
];

$recentActivity = [
    ['user' => 'Sarah Jenkins', 'action' => 'created a new student account', 'time' => '5 min ago'],
    ['user' => 'Marcus Chen', 'action' => 'completed counseling session', 'time' => '15 min ago'],
    ['user' => 'David Rodriguez', 'action' => 'submitted document', 'time' => '1 hour ago'],
    ['user' => 'Emily Watson', 'action' => 'scheduled appointment', 'time' => '2 hours ago'],
];

ob_start();
?>
<section class="page-hero" aria-labelledby="page-title">
    <div>
        <h2 id="page-title">Dashboard Overview</h2>
        <p>Welcome back, Admin. Here's what's happening today.</p>
    </div>
</section>

<section class="dashboard-stats" aria-label="Dashboard statistics">
    <div class="stats-grid">
        <?php foreach ($stats as $stat): ?>
            <article class="stat-card">
                <div class="stat-card__icon">
                    <img src="<?php echo htmlspecialchars(dash_asset_url($assetPath . '/images/counselors-dashboard', $stat['icon']), ENT_QUOTES); ?>" alt="">
                </div>
                <div class="stat-card__content">
                    <h3><?php echo htmlspecialchars($stat['label'], ENT_QUOTES); ?></h3>
                    <div class="stat-card__value"><?php echo htmlspecialchars($stat['value'], ENT_QUOTES); ?></div>
                    <div class="stat-card__change"><?php echo htmlspecialchars($stat['change'], ENT_QUOTES); ?></div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="recent-activity" aria-label="Recent activity">
    <div class="section-header">
        <h3>Recent Activity</h3>
        <a href="/ECMS(rejish)/admin/users" class="view-all-link">View All Users</a>
    </div>

    <div class="activity-list">
        <?php foreach ($recentActivity as $activity): ?>
            <div class="activity-item">
                <div class="activity-item__avatar">
                    <span class="avatar-initials"><?php echo strtoupper(substr($activity['user'], 0, 2)); ?></span>
                </div>
                <div class="activity-item__content">
                    <strong><?php echo htmlspecialchars($activity['user'], ENT_QUOTES); ?></strong>
                    <span><?php echo htmlspecialchars($activity['action'], ENT_QUOTES); ?></span>
                </div>
                <div class="activity-item__time">
                    <?php echo htmlspecialchars($activity['time'], ENT_QUOTES); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="quick-actions" aria-label="Quick actions">
    <h3>Quick Actions</h3>
    <div class="actions-grid">
        <a href="/ECMS(rejish)/admin/users" class="action-card">
            <img src="<?php echo htmlspecialchars(dash_asset_url($imagePath, 'add-user-icon.svg'), ENT_QUOTES); ?>" alt="">
            <span>Add New User</span>
        </a>
        <a href="/ECMS(rejish)/admin/users" class="action-card">
            <img src="<?php echo htmlspecialchars(dash_asset_url($assetPath . '/images/counselors-dashboard', 'sidebar-users.svg'), ENT_QUOTES); ?>" alt="">
            <span>User Management</span>
        </a>
        <!-- TODO: Uncomment when views are created
        <a href="/admin/students" class="action-card">
            <img src="<?php echo htmlspecialchars(dash_asset_url($assetPath . '/images/counselors-dashboard', 'sidebar-students.svg'), ENT_QUOTES); ?>" alt="">
            <span>View Students</span>
        </a>
        <a href="/admin/counselors" class="action-card">
            <img src="<?php echo htmlspecialchars(dash_asset_url($assetPath . '/images/counselors-dashboard', 'sidebar-counselors.svg'), ENT_QUOTES); ?>" alt="">
            <span>Manage Counselors</span>
        </a>
        <a href="/admin/sessions" class="action-card">
            <img src="<?php echo htmlspecialchars(dash_asset_url($assetPath . '/images/counselors-dashboard', 'sidebar-sessions.svg'), ENT_QUOTES); ?>" alt="">
            <span>View Sessions</span>
        </a>
        -->
    </div>
</section>
<?php
$content = ob_get_clean();

include __DIR__ . '/../layouts/admin-layout.php';
