<?php
$pageTitle = $pageTitle ?? 'My Sessions';
$pageDescription = $pageDescription ?? 'Your counseling sessions.';
$currentPage = $currentPage ?? 'sessions';
$assetPath = url('/frontend/assets');
$sessions = $sessions ?? [];
ob_start();
?>
<style>
.page-header{margin-bottom:24px}
.page-header h2{margin:0;color:#0b1c30;font-size:32px;font-weight:700;letter-spacing:-0.64px}
.page-header p{margin:4px 0 0;color:#73777f;font-size:14px}
.card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 1px 2px rgba(0,0,0,0.04);overflow:hidden}
.table{width:100%;border-collapse:collapse}
.table th{padding:12px 24px;text-align:left;font-size:11px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;color:#9ca3af;background:#fafbfc;border-bottom:1px solid #e5e7eb}
.table td{padding:14px 24px;font-size:14px;color:#43474f;border-bottom:1px solid #f3f4f6}
.table tbody tr:last-child td{border-bottom:none}
.badge{display:inline-flex;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:500}
.badge--scheduled{background:#dbeafe;color:#1d4ed8}
.badge--completed{background:#d1fae5;color:#059669}
.badge--cancelled{background:#fee2e2;color:#dc2626}
.badge--in-progress{background:#fef3c7;color:#d97706}
.empty{text-align:center;padding:40px 24px;color:#9ca3af;font-size:14px}
</style>
<div class="page-header">
    <h2>My Sessions</h2>
    <p>All your counseling sessions.</p>
</div>
<div class="card">
    <?php if (!empty($sessions)): ?>
        <table class="table">
            <thead><tr><th>Date &amp; Time</th><th>Mode</th><th>Counselor</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($sessions as $s): ?>
                    <tr>
                        <td><?php echo e(date('M d, Y g:i A', strtotime($s['datetime']))); ?></td>
                        <td><?php echo e($s['mode'] ?? '-'); ?></td>
                        <td><?php echo e($s['counselor_name'] ?? '-'); ?></td>
                        <td><span class="badge badge--<?php echo e($s['status']); ?>"><?php echo e(ucfirst($s['status'])); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty">No counseling sessions yet.</div>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/student-layout.php';
