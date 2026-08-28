<?php
$students = $students ?? [];
ob_start();
?>
<style>
    .stu-page { display: flex; flex-direction: column; gap: 24px; }
    .stu-header h2 { margin: 0; color: #0b1c30; font-size: 32px; font-weight: 700; }
    .stu-header p { margin: 4px 0 0; color: #73777f; font-size: 14px; }
    .stu-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
    .stu-card-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid #e5e7eb; }
    .stu-card-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #0b1c30; }
    .stu-table { width: 100%; border-collapse: collapse; }
    .stu-table th { padding: 12px 24px; text-align: left; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: #9ca3af; background: #fafbfc; border-bottom: 1px solid #e5e7eb; }
    .stu-table td { padding: 16px 24px; font-size: 14px; color: #43474f; border-bottom: 1px solid #f3f4f6; }
    .stu-table tbody tr:last-child td { border-bottom: none; }
    .stu-table tbody tr:hover { background: #fafbff; }
    .stu-table tr { cursor: pointer; }
    .stu-student { display: flex; align-items: center; gap: 10px; }
    .stu-avatar { width: 36px; height: 36px; border-radius: 9999px; background: #dbeafe; color: #2563eb; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; flex: 0 0 auto; }
    .stu-name { font-weight: 600; color: #0b1c30; }
    .stu-id { font-size: 12px; color: #73777f; }
    .stu-status { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; }
    .stu-status__dot { width: 8px; height: 8px; border-radius: 9999px; }
    .stu-status--active { color: #059669; } .stu-status--active .stu-status__dot { background: #10b981; }
    .stu-status--inactive { color: #9ca3af; } .stu-status--inactive .stu-status__dot { background: #d1d5db; }
    .stu-btn { display: inline-flex; align-items: center; height: 34px; padding: 0 14px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 13px; font-weight: 500; text-decoration: none; }
    .stu-btn:hover { background: #004aaf; }
    .stu-empty { text-align: center; padding: 40px 20px; color: #9ca3af; font-size: 14px; }
</style>
<div class="stu-page">
    <section class="stu-header">
        <h2>My Students</h2>
        <p>Students assigned to you for counseling.</p>
    </section>
    <section class="stu-card">
        <div class="stu-card-header">
            <h3><?php echo count($students); ?> Assigned Students</h3>
        </div>
        <?php if (!empty($students)): ?>
            <table class="stu-table">
                <thead><tr><th>Student</th><th>Student ID</th><th>Education Level</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($students as $st): ?>
                        <tr data-href="<?php echo url('/counselor/students/' . $st['id']); ?>">
                            <td>
                                <div class="stu-student">
                                    <span class="stu-avatar"><?php echo e(strtoupper(substr($st['name'], 0, 2))); ?></span>
                                    <span class="stu-name"><?php echo e($st['name']); ?></span>
                                </div>
                            </td>
                            <td><span class="stu-id"><?php echo e($st['student_id'] ?? '-'); ?></span></td>
                            <td><?php echo e($st['education_level'] ?? '-'); ?></td>
                            <td><span class="stu-status stu-status--<?php echo e($st['status'] ?? 'active'); ?>"><span class="stu-status__dot"></span><?php echo e(ucfirst($st['status'] ?? 'active')); ?></span></td>
                            <td style="text-align:right;"><a class="stu-btn" href="<?php echo url('/counselor/students/' . $st['id']); ?>">View Profile</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="stu-empty">No students assigned to you yet.</div>
        <?php endif; ?>
    </section>
</div>
<script>
document.querySelectorAll('.stu-table tr[data-href]').forEach(function (tr) {
    tr.addEventListener('click', function (e) {
        if (e.target.closest('a')) return;
        window.location.href = tr.dataset.href;
    });
});
</script>
<?php $content = ob_get_clean(); include __DIR__ . '/../layouts/counselor-layout.php';