<?php ob_start(); ?>
<style>
    .form-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; max-width: 720px; }
    .form-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 500; color: #43474f; margin-bottom: 6px; }
    .form-group input, .form-group select { width: 100%; height: 40px; padding: 0 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; background: #f9fafb; }
    .form-group input:focus, .form-group select:focus { outline: none; border-color: #0054cb; background: #fff; }
    .form-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-primary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .btn-secondary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 14px; font-weight: 500; text-decoration: none; }
</style>
<div class="form-card">
    <h3>Schedule New Session</h3>
    <form method="POST" action="<?php echo url('/admin/sessions/store'); ?>">
        <div class="form-group"><label>Session ID</label><input type="text" name="session_id" required></div>
        <div class="form-group"><label>Student</label><select name="student_id" required><option value="">Select student</option><?php foreach ($students as $st): ?><option value="<?php echo e($st['id']); ?>"><?php echo e($st['name']); ?></option><?php endforeach; ?></select></div>
        <div class="form-group"><label>Counselor</label><select name="counselor_id" required><option value="">Select counselor</option><?php foreach ($counselors as $c): ?><option value="<?php echo e($c['id']); ?>"><?php echo e($c['name']); ?></option><?php endforeach; ?></select></div>
        <div class="form-group"><label>Mode</label><select name="mode"><option value="In-Person">In-Person</option><option value="Video Call">Video Call</option></select></div>
        <div class="form-group"><label>Date & Time</label><input type="datetime-local" name="datetime" required></div>
        <div class="form-actions"><button type="submit" class="btn-primary">Schedule</button><a href="<?php echo url('/admin/sessions'); ?>" class="btn-secondary">Cancel</a></div>
    </form>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';