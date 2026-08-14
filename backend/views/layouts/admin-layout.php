<?php
$assetPath = $assetPath ?? url('/frontend/assets');
$sidebarImagePath = $sidebarImagePath ?? $assetPath . '/images/counselors-dashboard';
$topbarImagePath = $topbarImagePath ?? $assetPath . '/images/user-management';
$topbarSearchPlaceholder = $topbarSearchPlaceholder ?? 'Search system...';
$topbarSearchIcon = $topbarSearchIcon ?? 'user-search-icon.svg';
$topbarNotificationIcon = $topbarNotificationIcon ?? 'top-notification.svg';
$topbarAvatarImage = $topbarAvatarImage ?? 'admin-avatar.jpg';

$currentPage = $currentPage ?? 'dashboard';

$basePath = rtrim(url('/'), '/');

$adminSidebarItems = [
    ['label' => 'Dashboard', 'icon' => 'sidebar-dashboard.svg', 'route' => $basePath . '/admin/dashboard', 'id' => 'dashboard'],
    ['label' => 'User Management', 'icon' => 'sidebar-users.svg', 'route' => $basePath . '/admin/users', 'id' => 'users'],
    ['label' => 'Students', 'icon' => 'sidebar-students.svg', 'route' => $basePath . '/admin/students', 'id' => 'students'],
    ['label' => 'Counselors', 'icon' => 'sidebar-counselors.svg', 'route' => $basePath . '/admin/counselors', 'id' => 'counselors'],
    ['label' => 'Inquiries', 'icon' => 'sidebar-sessions.svg', 'route' => $basePath . '/admin/inquiries', 'id' => 'inquiries'],
    ['label' => 'Counseling Sessions', 'icon' => 'sidebar-sessions.svg', 'route' => $basePath . '/admin/sessions', 'id' => 'sessions'],
    ['label' => 'Documents', 'icon' => 'sidebar-documents.svg', 'route' => $basePath . '/admin/documents', 'id' => 'documents'],
    ['label' => 'College & Course Catalog', 'icon' => 'sidebar-counselors.svg', 'route' => $basePath . '/admin/catalog', 'id' => 'catalog'],
];

$pageTitle = $pageTitle ?? 'Admin Dashboard';
$pageDescription = $pageDescription ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($pageTitle); ?> | ECMS Admin</title>
    <link rel="stylesheet" href="<?php echo e($assetPath . '/css/app.css'); ?>">
</head>
<body class="dashboard-page">
    <div class="dashboard-shell">
        <aside class="sidebar" aria-label="Primary navigation">
            <div class="sidebar__brand">
                <h1>ECMS Admin</h1>
                <p>Education Counseling</p>
            </div>

            <nav class="sidebar__nav">
                <?php foreach ($adminSidebarItems as $item): ?>
                    <?php $isActive = $item['id'] === $currentPage; ?>
                    <a class="sidebar__link<?php echo $isActive ? ' sidebar__link--active' : ''; ?>" href="<?php echo e($item['route']); ?>"<?php echo $isActive ? ' aria-current="page"' : ''; ?>>
                        <span class="sidebar__icon">
                            <img src="<?php echo e($sidebarImagePath . '/' . $item['icon']); ?>" alt="">
                        </span>
                        <span class="sidebar__label"><?php echo e($item['label']); ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="sidebar__footer">
                <a class="sidebar__link" href="<?php echo e($basePath . '/logout'); ?>">
                    <span class="sidebar__icon">
                        <img src="<?php echo e($sidebarImagePath . '/sidebar-logout.svg'); ?>" alt="">
                    </span>
                    <span class="sidebar__label">Logout</span>
                </a>
            </div>
        </aside>

        <div class="dashboard-main">
            <header class="topbar">
                <label class="topbar__search topbar__search--wide" aria-label="Search">
                    <img src="<?php echo e($topbarImagePath . '/' . $topbarSearchIcon); ?>" alt="">
                    <input type="search" placeholder="<?php echo e($topbarSearchPlaceholder); ?>" aria-label="Search system">
                </label>

                <div class="topbar__actions">
                    <button type="button" class="icon-button icon-button--topbar" aria-label="Notifications">
                        <img src="<?php echo e($topbarImagePath . '/' . $topbarNotificationIcon); ?>" alt="">
                    </button>

                    <a href="<?php echo url('/admin/profile'); ?>" class="topbar__profile" style="text-decoration:none;color:inherit;display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <span><?php echo e($_SESSION['user']['name'] ?? 'Admin User'); ?></span>
                        <img src="<?php echo e($topbarImagePath . '/' . $topbarAvatarImage); ?>" alt="Admin User profile picture">
                    </a>
                </div>
            </header>

            <main class="dashboard-content">
                <?php if (isset($_SESSION['flash'])): ?>
                    <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                        <div style="padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:14px;font-weight:500;<?php echo $type === 'error' ? 'background:#fef2f2;color:#dc2626;border:1px solid #fecaca;' : 'background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;'; ?>">
                            <?php echo e($message); ?>
                        </div>
                    <?php endforeach; ?>
                    <?php unset($_SESSION['flash']); ?>
                <?php endif; ?>
                <?php echo $content; ?>
            </main>

            <footer class="dashboard-footer">
                <p>Version 1.0.0 | Created by Rejish Khanal</p>
            </footer>
        </div>
    </div>
</body>
</html>
