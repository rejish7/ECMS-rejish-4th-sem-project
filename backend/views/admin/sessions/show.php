<?php ob_start(); ?>
<style>
    .detail-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; max-width: 720px; }
    .detail-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .detail-row { display: flex; padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
    .detail-label { width: 180px; font-size: 14px; font-weight: 500; color: #73777f; }
    .detail-value { font-size: 14px; color: #0b1c30; }
    .detail-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-primary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; }
    .btn-secondary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 14px; font-weight: 500; text-decoration: none; }
    .btn-danger { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #ef4444; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
</style>
<div class="detail-card">
    <h3>Session Details</h3>
    <div class="detail-row"><span class="detail-label">Session ID</span><span class="detail-value"><?php echo e($session['session_id'] ?? $session['id']); ?></span></div>
    <div class="detail-row"><span class="detail-label">Student</span><span class="detail-value"><?php echo e($session['student_name'] ?? '-'); ?></span></div>
    <div class="detail-row"><span class="detail-label">Counselor</span><span class="detail-value"><?php echo e($session['counselor_name'] ?? '-'); ?></span></div>
    <div class="detail-row"><span class="detail-label">Mode</span><span class="detail-value"><?php echo e($session['mode'] ?? '-'); ?></span></div>
    <div class="detail-row"><span class="detail-label">Date & Time</span><span class="detail-value"><?php echo e($session['datetime'] ?? '-'); ?></span></div>
    <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value"><?php echo e($session['status'] ?? '-'); ?></span></div>
    <div class="detail-actions">
        <a href="<?php echo url('/admin/sessions/' . $session['id'] . '/edit'); ?>" class="btn-primary">Edit</a>
        <form method="POST" action="<?php echo url('/admin/sessions/' . $session['id'] . '/delete'); ?>" onsubmit="return confirm('Are you sure?')"><button type="submit" class="btn-danger">Delete</button></form>
        <a href="<?php echo url('/admin/sessions'); ?>" class="btn-secondary">Back to List</a>
    </div>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';