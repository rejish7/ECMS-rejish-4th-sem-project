<?php
$assetPath = 'frontend/assets';
$sidebarImagePath = $assetPath . '/images/counselors-dashboard';
$imagePath = $assetPath . '/images/user-management';

$sidebarItems = [
    ['label' => 'Dashboard', 'icon' => 'sidebar-dashboard.svg', 'active' => false],
    ['label' => 'User Management', 'icon' => 'sidebar-users.svg', 'active' => true],
    ['label' => 'Students', 'icon' => 'sidebar-students.svg', 'active' => false],
    ['label' => 'Counselors', 'icon' => 'sidebar-counselors.svg', 'active' => false],
    ['label' => 'Counseling Sessions', 'icon' => 'sidebar-sessions.svg', 'active' => false],
    ['label' => 'Appointments', 'icon' => 'sidebar-appointments.svg', 'active' => false],
    ['label' => 'Documents', 'icon' => 'sidebar-documents.svg', 'active' => false],
];

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
    ],
];

function asset_url($base, $file)
{
    return $base . '/' . $file;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Counselors Management | ECMS</title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($assetPath, ENT_QUOTES); ?>/css/app.css">
</head>
<body class="dashboard-page">
    <div class="dashboard-shell">
        <aside class="sidebar" aria-label="Primary navigation">
            <div class="sidebar__brand">
                <h1>ECMS Admin</h1>
                <p>Education Counseling</p>
            </div>

            <nav class="sidebar__nav">
                <?php foreach ($sidebarItems as $item): ?>
                    <a class="sidebar__link<?php echo $item['active'] ? ' sidebar__link--active' : ''; ?>" href="#"<?php echo $item['active'] ? ' aria-current="page"' : ''; ?>>
                        <span class="sidebar__icon">
                            <img src="<?php echo htmlspecialchars(asset_url($sidebarImagePath, $item['icon']), ENT_QUOTES); ?>" alt="">
                        </span>
                        <span class="sidebar__label"><?php echo htmlspecialchars($item['label'], ENT_QUOTES); ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="sidebar__footer">
                <a class="sidebar__link" href="#">
                    <span class="sidebar__icon">
                        <img src="<?php echo htmlspecialchars(asset_url($sidebarImagePath, 'sidebar-logout.svg'), ENT_QUOTES); ?>" alt="">
                    </span>
                    <span class="sidebar__label">Logout</span>
                </a>
            </div>
        </aside>

        <div class="dashboard-main">
            <header class="topbar">
                <label class="topbar__search topbar__search--wide" aria-label="Search">
                    <img src="<?php echo htmlspecialchars(asset_url($imagePath, 'user-search-icon.svg'), ENT_QUOTES); ?>" alt="">
                    <input type="search" placeholder="Search system..." aria-label="Search system">
                </label>

                <div class="topbar__actions">
                    <button type="button" class="icon-button icon-button--topbar" aria-label="Notifications">
                        <img src="<?php echo htmlspecialchars(asset_url($imagePath, 'top-notification.svg'), ENT_QUOTES); ?>" alt="">
                    </button>

                    <div class="topbar__profile">
                        <span>Admin User</span>
                        <img src="<?php echo htmlspecialchars(asset_url($imagePath, 'admin-avatar.jpg'), ENT_QUOTES); ?>" alt="Admin User profile picture">
                    </div>
                </div>
            </header>

            <main class="dashboard-content">
                <section class="page-hero" aria-labelledby="page-title">
                    <div>
                        <h2 id="page-title">User Management</h2>
                        <p>Manage all system users across roles.</p>
                    </div>

                    <button type="button" class="primary-button">
                        <img src="<?php echo htmlspecialchars(asset_url($imagePath, 'add-user-icon.svg'), ENT_QUOTES); ?>" alt="">
                        <span>Add New User</span>
                    </button>
                </section>

                <section class="filters-stats-grid" aria-label="User filters and stats">
                    <article class="filters-card">
                        <h3>Filter Users</h3>

                        <div class="filters-card__grid">
                            <div class="filter-field filter-field--search">
                                <span class="filter-field__label">Search by<br>Name or<br>Email</span>
                                <label class="filter-input">
                                    <img src="<?php echo htmlspecialchars(asset_url($imagePath, 'search-icon.svg'), ENT_QUOTES); ?>" alt="">
                                    <input type="search" placeholder="Enter details..." aria-label="Search users">
                                </label>
                            </div>

                            <div class="filter-field">
                                <span class="filter-field__label">Role</span>
                                <button type="button" class="select-button select-button--compact">
                                    <span>All Roles</span>
                                    <img src="<?php echo htmlspecialchars(asset_url($imagePath, 'role-dropdown.svg'), ENT_QUOTES); ?>" alt="">
                                </button>
                            </div>

                            <div class="filter-field">
                                <span class="filter-field__label">Status</span>
                                <button type="button" class="select-button select-button--compact">
                                    <span>All Statuses</span>
                                    <img src="<?php echo htmlspecialchars(asset_url($imagePath, 'status-dropdown.svg'), ENT_QUOTES); ?>" alt="">
                                </button>
                            </div>

                            <button type="button" class="clear-button">
                                <span>Clear</span>
                            </button>
                        </div>
                    </article>

                    <article class="stats-card">
                        <div class="stats-card__header">
                            <h3>Total Active Users</h3>
                            <img src="<?php echo htmlspecialchars(asset_url($imagePath, 'stats-icon.svg'), ENT_QUOTES); ?>" alt="">
                        </div>
                        <div class="stats-card__value">1,248</div>
                        <div class="stats-card__note">↑ 12 new this week</div>
                    </article>
                </section>

                <section class="table-card" aria-label="User directory table">
                    <div class="table-card__header">
                        <h3>User Directory</h3>
                        <p>Showing 1-5 of 1,248</p>
                    </div>

                    <div class="table-scroll">
                        <table class="counselors-table user-table">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th class="table-actions-head">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>
                                            <div class="counselor-profile">
                                                <?php if ($user['avatar'] === 'image'): ?>
                                                    <img src="<?php echo htmlspecialchars(asset_url($imagePath, $user['image']), ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($user['name'], ENT_QUOTES); ?> profile picture">
                                                <?php else: ?>
                                                    <span class="counselor-profile__initials counselor-profile__initials--<?php echo htmlspecialchars(strtolower($user['initials']), ENT_QUOTES); ?>"><?php echo htmlspecialchars($user['initials'], ENT_QUOTES); ?></span>
                                                <?php endif; ?>

                                                <div class="counselor-profile__copy">
                                                    <strong><?php echo htmlspecialchars($user['name'], ENT_QUOTES); ?></strong>
                                                    <span>ID: <?php echo htmlspecialchars($user['id'], ENT_QUOTES); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="specialization-pill specialization-pill--<?php echo htmlspecialchars(strtolower($user['role']), ENT_QUOTES); ?>"><?php echo htmlspecialchars($user['role'], ENT_QUOTES); ?></span>
                                        </td>
                                        <td>
                                            <span class="user-email"><?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?></span>
                                        </td>
                                        <td>
                                            <span class="status status--<?php echo htmlspecialchars($user['statusTone'], ENT_QUOTES); ?>">
                                                <span class="status__dot"></span>
                                                <?php echo htmlspecialchars($user['status'], ENT_QUOTES); ?>
                                            </span>
                                        </td>
                                        <td class="table-actions-cell">
                                            <div class="row-actions row-actions--hidden" aria-hidden="true">
                                                <button type="button" class="icon-button icon-button--row" aria-label="Edit user">
                                                    <img src="<?php echo htmlspecialchars(asset_url($imagePath, 'row-action-icon-1.svg'), ENT_QUOTES); ?>" alt="">
                                                </button>
                                                <button type="button" class="icon-button icon-button--row" aria-label="Delete user">
                                                    <img src="<?php echo htmlspecialchars(asset_url($imagePath, 'row-action-icon-2.svg'), ENT_QUOTES); ?>" alt="">
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-footer">
                        <button type="button" class="pagination__button pagination__button--ghost">Previous</button>

                        <div class="pagination" aria-label="Pagination">
                            <button type="button" class="pagination__page pagination__page--active" aria-current="page">1</button>
                            <button type="button" class="pagination__page">2</button>
                            <button type="button" class="pagination__page">3</button>
                            <span class="pagination__ellipsis">...</span>
                        </div>

                        <button type="button" class="pagination__button">Next</button>
                    </div>
                </section>
            </main>

            <footer class="dashboard-footer">
                <p>Version 1.0.0 | Created by Rejish Khanal | For educational purposes only.</p>
            </footer>
        </div>
    </div>
</body>
</html>