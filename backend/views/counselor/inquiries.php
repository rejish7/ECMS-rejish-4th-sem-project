<?php
$inquiries = $inquiries ?? [];
$open = $open ?? [];
$closed = $closed ?? 0;
ob_start();
?>
<style>
    .ciq-page { display: flex; flex-direction: column; gap: 24px; }
    .ciq-header h2 { margin: 0; color: #0b1c30; font-size: 32px; font-weight: 700; }
    .ciq-header p { margin: 4px 0 0; color: #73777f; font-size: 14px; }

    .ciq-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .ciq-stat { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; }
    .ciq-stat__value { font-size: 28px; font-weight: 700; color: #0b1c30; }
    .ciq-stat__label { font-size: 13px; color: #73777f; margin-top: 4px; }

    .ciq-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
    .ciq-card-header { padding: 20px 24px; border-bottom: 1px solid #e5e7eb; }
    .ciq-card-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #0b1c30; }
    .ciq-table { width: 100%; border-collapse: collapse; }
    .ciq-table th { padding: 12px 24px; text-align: left; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: #9ca3af; background: #fafbfc; border-bottom: 1px solid #e5e7eb; }
    .ciq-table td { padding: 16px 24px; font-size: 14px; color: #43474f; border-bottom: 1px solid #f3f4f6; }
    .ciq-table tbody tr:last-child td { border-bottom: none; }
    .ciq-table tbody tr:hover { background: #fafbff; }
    .ciq-id { color: #0054cb; font-weight: 600; }
    .ciq-date { font-size: 13px; color: #73777f; }
    .ciq-badge { display: inline-flex; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
    .ciq-badge--new { background: #fef3c7; color: #d97706; }
    .ciq-badge--assigned { background: #dbeafe; color: #2563eb; }
    .ciq-badge--in-progress { background: #fef3c7; color: #d97706; }
    .ciq-badge--closed { background: #f3f4f6; color: #6b7280; }
    .ciq-empty { text-align: center; padding: 40px 20px; color: #9ca3af; font-size: 14px; }
    @media (max-width: 768px) { .ciq-stats { grid-template-columns: 1fr; } }
</style>
<div class="ciq-page">
    <section class="ciq-header">
        <h2>My Inquiries</h2>
        <p>Inquiries assigned to you from your students.</p>
    </section>

    <section class="ciq-stats">
        <div class="ciq-stat"><div class="ciq-stat__value"><?php echo e(count($inquiries)); ?></div><div class="ciq-stat__label">Total Inquiries</div></div>
        <div class="ciq-stat"><div class="ciq-stat__value" style="color:#d97706;"><?php echo e(count($open)); ?></div><div class="ciq-stat__label">Open</div></div>
        <div class="ciq-stat"><div class="ciq-stat__value" style="color:#059669;"><?php echo e($closed); ?></div><div class="ciq-stat__label">Closed</div></div>
    </section>

    <section class="ciq-card">
        <div class="ciq-card-header"><h3>All Inquiries</h3></div>
        <?php if (!empty($inquiries)): ?>
            <table class="ciq-table">
                <thead><tr><th>Inquiry ID</th><th>Student</th><th>Country</th><th>Level of Study</th><th>Date</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($inquiries as $inq): ?>
                        <tr>
                            <td><span class="ciq-id">#<?php echo e($inq['inquiry_id']); ?></span></td>
                            <td><?php echo e($inq['student_name'] ?? '-'); ?></td>
                            <td><?php echo e($inq['country_of_interest'] ?? '-'); ?></td>
                            <td><?php echo e($inq['level_of_study'] ?? '-'); ?></td>
                            <td><span class="ciq-date"><?php echo e(date('M d, Y', strtotime($inq['created_at']))); ?></span></td>
                            <td><span class="ciq-badge ciq-badge--<?php echo e($inq['status']); ?>"><?php echo e(ucwords(str_replace('-', ' ', $inq['status']))); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="ciq-empty">No inquiries assigned to you yet.</div>
        <?php endif; ?>
    </section>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../layouts/counselor-layout.php';