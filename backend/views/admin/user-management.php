<?php
$pageTitle = 'User Management';
$pageDescription = 'Manage all system users across roles.';
$currentPage = 'users';

$users = [
    [
        'avatar' => 'image',
        'image' => 'sarah-jenkins.jpg',
        'initials' => 'SJ',
        'name' => 'Sarah Jenkins',
        'id' => 'USR-8821',
        'role' => 'Administrator',
        'email' => 'sarah.j@ecms.edu',
        'status' => 'Active',
        'statusTone' => 'active',
        'joined' => 'Jan 12, 2024',
    ],
    [
        'avatar' => 'initials',
        'initials' => 'MC',
        'name' => 'Marcus Chen',
        'id' => 'USR-9044',
        'role' => 'Counselor',
        'email' => 'm.chen@ecms.edu',
        'status' => 'Active',
        'statusTone' => 'active',
        'joined' => 'Mar 8, 2024',
    ],
    [
        'avatar' => 'image',
        'image' => 'david-rodriguez.jpg',
        'initials' => 'DR',
        'name' => 'David Rodriguez',
        'id' => 'STU-1102',
        'role' => 'Student',
        'email' => 'd.rod23@student.edu',
        'status' => 'Inactive',
        'statusTone' => 'inactive',
        'joined' => 'Sep 1, 2024',
    ],
    [
        'avatar' => 'initials',
        'initials' => 'EW',
        'name' => 'Emily Watson',
        'id' => 'USR-9210',
        'role' => 'Counselor',
        'email' => 'e.watson@ecms.edu',
        'status' => 'Active',
        'statusTone' => 'active',
        'joined' => 'Feb 15, 2024',
    ],
    [
        'avatar' => 'initials',
        'initials' => 'JP',
        'name' => 'James Patel',
        'id' => 'STU-1187',
        'role' => 'Student',
        'email' => 'j.patel@student.edu',
        'status' => 'Active',
        'statusTone' => 'active',
        'joined' => 'Oct 20, 2024',
    ],
];

$stats = [
    ['label' => 'Total Users', 'value' => '1,248', 'icon' => 'stats-icon.svg', 'change' => '+12 this week', 'changeType' => 'positive'],
    ['label' => 'Active', 'value' => '1,180', 'icon' => 'stats-icon.svg', 'change' => '94.5%', 'changeType' => 'positive'],
    ['label' => 'Inactive', 'value' => '68', 'icon' => 'stats-icon.svg', 'change' => '5.5%', 'changeType' => 'neutral'],
    ['label' => 'New This Month', 'value' => '42', 'icon' => 'stats-icon.svg', 'change' => '+8 vs last month', 'changeType' => 'positive'],
];

$assetPath = '/ECMS(rejish)/frontend/assets';
$imagePath = $assetPath . '/images/user-management';

ob_start();
?>
<style>
    .um-page { padding: 0; }
    .um-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .um-header-text h2 { margin: 0 0 4px; font-size: 24px; font-weight: 700; color: #0b1c30; }
    .um-header-text p { margin: 0; color: #73777f; font-size: 14px; }
    .um-add-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #0054cb; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: background 0.2s; }
    .um-add-btn:hover { background: #0044a8; }
    .um-add-btn svg { width: 16px; height: 16px; }

    .um-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
    .um-stat-card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #e5e7eb; }
    .um-stat-label { font-size: 12px; color: #73777f; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 500; margin-bottom: 8px; }
    .um-stat-value { font-size: 28px; font-weight: 700; color: #0b1c30; margin-bottom: 4px; }
    .um-stat-change { font-size: 12px; color: #10b981; }
    .um-stat-change--neutral { color: #73777f; }

    .um-filters { background: #fff; border-radius: 12px; padding: 20px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #e5e7eb; margin-bottom: 24px; display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; }
    .um-filter-group { display: flex; flex-direction: column; gap: 6px; }
    .um-filter-group--search { flex: 1; min-width: 240px; }
    .um-filter-label { font-size: 12px; font-weight: 500; color: #73777f; }
    .um-filter-input { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; transition: border-color 0.2s; }
    .um-filter-input:focus-within { border-color: #0054cb; background: #fff; }
    .um-filter-input svg { width: 16px; height: 16px; color: #9ca3af; flex-shrink: 0; }
    .um-filter-input input { border: none; background: none; outline: none; font-size: 14px; color: #0b1c30; width: 100%; }
    .um-filter-input input::placeholder { color: #9ca3af; }
    .um-filter-select { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; color: #0b1c30; cursor: pointer; min-width: 140px; justify-content: space-between; transition: border-color 0.2s; }
    .um-filter-select:hover { border-color: #d1d5db; }
    .um-filter-select svg { width: 12px; height: 12px; color: #9ca3af; }
    .um-clear-btn { padding: 8px 16px; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; color: #6b7280; cursor: pointer; transition: all 0.2s; }
    .um-clear-btn:hover { background: #e5e7eb; color: #374151; }

    .um-table-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #e5e7eb; overflow: hidden; }
    .um-table-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-bottom: 1px solid #e5e7eb; }
    .um-table-header h3 { margin: 0; font-size: 16px; font-weight: 600; color: #0b1c30; }
    .um-table-header p { margin: 0; font-size: 13px; color: #73777f; }
    .um-table-wrap { overflow-x: auto; }
    .um-table { width: 100%; border-collapse: collapse; }
    .um-table th { padding: 12px 24px; background: #f9fafb; font-size: 11px; font-weight: 600; color: #73777f; text-transform: uppercase; letter-spacing: 0.5px; text-align: left; border-bottom: 1px solid #e5e7eb; white-space: nowrap; }
    .um-table th:last-child { text-align: right; }
    .um-table td { padding: 16px 24px; border-bottom: 1px solid #f3f4f6; font-size: 14px; vertical-align: middle; }
    .um-table tbody tr { transition: background 0.15s; }
    .um-table tbody tr:hover { background: #f9fafb; }
    .um-table tbody tr:last-child td { border-bottom: none; }

    .um-user { display: flex; align-items: center; gap: 12px; }
    .um-user-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
    .um-user-initials { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 600; color: #fff; flex-shrink: 0; }
    .um-user-initials--sj { background: #8b5cf6; }
    .um-user-initials--mc { background: #3b82f6; }
    .um-user-initials--dr { background: #f59e0b; }
    .um-user-initials--ew { background: #10b981; }
    .um-user-initials--jp { background: #ef4444; }
    .um-user-info strong { display: block; font-size: 14px; font-weight: 500; color: #0b1c30; }
    .um-user-info span { font-size: 12px; color: #73777f; }

    .um-role-badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
    .um-role-badge--administrator { background: #ede9fe; color: #7c3aed; }
    .um-role-badge--counselor { background: #dbeafe; color: #2563eb; }
    .um-role-badge--student { background: #fef3c7; color: #d97706; }

    .um-email { color: #6b7280; }

    .um-status { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; }
    .um-status-dot { width: 7px; height: 7px; border-radius: 50%; }
    .um-status--active .um-status-dot { background: #10b981; }
    .um-status--active { color: #059669; }
    .um-status--inactive .um-status-dot { background: #d1d5db; }
    .um-status--inactive { color: #9ca3af; }

    .um-joined { color: #9ca3af; font-size: 13px; }

    .um-actions { display: flex; gap: 4px; justify-content: flex-end; opacity: 0; transition: opacity 0.15s; }
    .um-table tbody tr:hover .um-actions { opacity: 1; }
    .um-action-btn { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 6px; border: none; background: none; cursor: pointer; transition: background 0.15s; }
    .um-action-btn:hover { background: #f3f4f6; }
    .um-action-btn--edit svg { width: 16px; height: 16px; color: #6b7280; }
    .um-action-btn--delete svg { width: 16px; height: 16px; color: #ef4444; }

    .um-pagination { display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-top: 1px solid #e5e7eb; }
    .um-pagination-info { font-size: 13px; color: #73777f; }
    .um-pagination-pages { display: flex; gap: 4px; }
    .um-page-btn { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 6px; border: none; background: none; font-size: 13px; font-weight: 500; color: #6b7280; cursor: pointer; transition: all 0.15s; }
    .um-page-btn:hover { background: #f3f4f6; }
    .um-page-btn--active { background: #0054cb; color: #fff; }
    .um-page-btn--active:hover { background: #0044a8; }
    .um-page-nav { display: flex; gap: 8px; }
    .um-page-nav-btn { padding: 6px 14px; border-radius: 6px; border: 1px solid #e5e7eb; background: #fff; font-size: 13px; font-weight: 500; color: #374151; cursor: pointer; transition: all 0.15s; }
    .um-page-nav-btn:hover { background: #f9fafb; border-color: #d1d5db; }
    .um-page-nav-btn:disabled { opacity: 0.4; cursor: not-allowed; }

    @media (max-width: 768px) {
        .um-stats { grid-template-columns: repeat(2, 1fr); }
        .um-filters { flex-direction: column; }
        .um-filter-group--search { min-width: 100%; }
        .um-header { flex-direction: column; gap: 12px; align-items: flex-start; }
    }
</style>

<div class="um-page">
    <div class="um-header">
        <div class="um-header-text">
            <h2>User Management</h2>
            <p>Manage all system users across roles.</p>
        </div>
        <button type="button" class="um-add-btn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Add New User
        </button>
    </div>

    <div class="um-stats">
        <?php foreach ($stats as $stat): ?>
            <div class="um-stat-card">
                <div class="um-stat-label"><?php echo $stat['label']; ?></div>
                <div class="um-stat-value"><?php echo $stat['value']; ?></div>
                <div class="um-stat-change <?php echo $stat['changeType'] === 'neutral' ? 'um-stat-change--neutral' : ''; ?>"><?php echo $stat['change']; ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="um-filters">
        <div class="um-filter-group um-filter-group--search">
            <span class="um-filter-label">Search</span>
            <div class="um-filter-input">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input type="search" placeholder="Search by name, email, or ID..." aria-label="Search users">
            </div>
        </div>
        <div class="um-filter-group">
            <span class="um-filter-label">Role</span>
            <button type="button" class="um-filter-select">
                <span>All Roles</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
            </button>
        </div>
        <div class="um-filter-group">
            <span class="um-filter-label">Status</span>
            <button type="button" class="um-filter-select">
                <span>All Statuses</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
            </button>
        </div>
        <button type="button" class="um-clear-btn">Clear Filters</button>
    </div>

    <div class="um-table-card">
        <div class="um-table-header">
            <h3>User Directory</h3>
            <p>Showing <?php echo count($users); ?> of 1,248 users</p>
        </div>
        <div class="um-table-wrap">
            <table class="um-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="um-user">
                                    <?php if ($user['avatar'] === 'image'): ?>
                                        <img class="um-user-avatar" src="<?php echo htmlspecialchars($imagePath . '/' . $user['image'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($user['name'], ENT_QUOTES); ?>">
                                    <?php else: ?>
                                        <span class="um-user-initials um-user-initials--<?php echo htmlspecialchars(strtolower($user['initials']), ENT_QUOTES); ?>"><?php echo htmlspecialchars($user['initials'], ENT_QUOTES); ?></span>
                                    <?php endif; ?>
                                    <div class="um-user-info">
                                        <strong><?php echo htmlspecialchars($user['name'], ENT_QUOTES); ?></strong>
                                        <span><?php echo htmlspecialchars($user['id'], ENT_QUOTES); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="um-role-badge um-role-badge--<?php echo htmlspecialchars(strtolower($user['role']), ENT_QUOTES); ?>"><?php echo htmlspecialchars($user['role'], ENT_QUOTES); ?></span>
                            </td>
                            <td>
                                <span class="um-email"><?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?></span>
                            </td>
                            <td>
                                <span class="um-status um-status--<?php echo htmlspecialchars($user['statusTone'], ENT_QUOTES); ?>">
                                    <span class="um-status-dot"></span>
                                    <?php echo htmlspecialchars($user['status'], ENT_QUOTES); ?>
                                </span>
                            </td>
                            <td>
                                <span class="um-joined"><?php echo htmlspecialchars($user['joined'], ENT_QUOTES); ?></span>
                            </td>
                            <td>
                                <div class="um-actions">
                                    <button type="button" class="um-action-btn um-action-btn--edit" aria-label="Edit user">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                    </button>
                                    <button type="button" class="um-action-btn um-action-btn--delete" aria-label="Delete user">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="um-pagination">
            <span class="um-pagination-info">Page 1 of 250</span>
            <div class="um-pagination-pages">
                <button type="button" class="um-page-nav-btn" disabled>Previous</button>
                <button type="button" class="um-page-btn um-page-btn--active">1</button>
                <button type="button" class="um-page-btn">2</button>
                <button type="button" class="um-page-btn">3</button>
                <button type="button" class="um-page-btn">4</button>
                <button type="button" class="um-page-btn">5</button>
                <button type="button" class="um-page-nav-btn">Next</button>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();

include __DIR__ . '/../layouts/admin-layout.php';
