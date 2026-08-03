<?php
$pageTitle = 'User Management';
$pageDescription = 'Manage all system users across roles.';
$currentPage = 'users';

$assetPath = url('/frontend/assets');
$imagePath = $assetPath . '/images/user-management';

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

ob_start();
?>
<section class="page-hero" aria-labelledby="page-title">
    <div>
        <h2 id="page-title">User Management</h2>
        <p>Manage all system users across roles.</p>
    </div>

    <button type="button" class="primary-button">
        <img src="<?php echo e($imagePath . '/add-user-icon.svg'); ?>" alt="">
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
                    <img src="<?php echo e($imagePath . '/search-icon.svg'); ?>" alt="">
                    <input type="search" placeholder="Enter details..." aria-label="Search users">
                </label>
            </div>

            <div class="filter-field">
                <span class="filter-field__label">Role</span>
                <button type="button" class="select-button select-button--compact">
                    <span>All Roles</span>
                    <img src="<?php echo e($imagePath . '/role-dropdown.svg'); ?>" alt="">
                </button>
            </div>

            <div class="filter-field">
                <span class="filter-field__label">Status</span>
                <button type="button" class="select-button select-button--compact">
                    <span>All Statuses</span>
                    <img src="<?php echo e($imagePath . '/status-dropdown.svg'); ?>" alt="">
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
            <img src="<?php echo e($imagePath . '/stats-icon.svg'); ?>" alt="">
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
                                    <img src="<?php echo e($imagePath . '/' . $user['image']); ?>" alt="<?php echo e($user['name']); ?> profile picture">
                                <?php else: ?>
                                    <span class="counselor-profile__initials counselor-profile__initials--<?php echo e(strtolower($user['initials'])); ?>"><?php echo e($user['initials']); ?></span>
                                <?php endif; ?>

                                <div class="counselor-profile__copy">
                                    <strong><?php echo e($user['name']); ?></strong>
                                    <span>ID: <?php echo e($user['id']); ?></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="specialization-pill specialization-pill--<?php echo e(strtolower($user['role'])); ?>"><?php echo e($user['role']); ?></span>
                        </td>
                        <td>
                            <span class="user-email"><?php echo e($user['email']); ?></span>
                        </td>
                        <td>
                            <span class="status status--<?php echo e($user['statusTone']); ?>">
                                <span class="status__dot"></span>
                                <?php echo e($user['status']); ?>
                            </span>
                        </td>
                        <td class="table-actions-cell">
                            <div class="row-actions row-actions--hidden" aria-hidden="true">
                                <button type="button" class="icon-button icon-button--row" aria-label="Edit user">
                                    <img src="<?php echo e($imagePath . '/row-action-icon-1.svg'); ?>" alt="">
                                </button>
                                <button type="button" class="icon-button icon-button--row" aria-label="Delete user">
                                    <img src="<?php echo e($imagePath . '/row-action-icon-2.svg'); ?>" alt="">
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
<?php
$content = ob_get_clean();

include __DIR__ . '/../layouts/admin-layout.php';
