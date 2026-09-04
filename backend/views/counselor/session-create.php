<?php
$students = $students ?? [];
$preselectedStudent = $preselectedStudent ?? 0;
ob_start();
?>
<style>
    .form-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; max-width: 720px; }
    .form-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 500; color: #43474f; margin-bottom: 6px; }
    .form-group select, .form-group input { width: 100%; height: 40px; padding: 0 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; background: #f9fafb; box-sizing: border-box; }
    .form-group select:focus, .form-group input:focus { outline: none; border-color: #0054cb; background: #fff; }
    .form-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-primary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .btn-primary:hover { background: #004aaf; }
    .btn-secondary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 14px; font-weight: 500; text-decoration: none; }
</style>
<div class="form-card">
    <h3>Schedule New Session</h3>
    <form method="POST" action="<?php echo url('/counselor/sessions/store'); ?>" id="sessionForm">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label>Student</label>
            <select name="student_id">
                <option value="">Select student</option>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $st): ?>
                        <option value="<?php echo e($st['id']); ?>" <?php echo $preselectedStudent === (int)$st['id'] ? 'selected' : ''; ?>><?php echo e($st['name'] . ' (' . ($st['student_id'] ?? 'N/A') . ')'); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <?php if (!empty($_SESSION['errors']['student_id'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['student_id']); ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Mode</label>
            <select name="mode">
                <option value="In-Person">In-Person</option>
                <option value="Video Call">Video Call</option>
            </select>
            <?php if (!empty($_SESSION['errors']['mode'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['mode']); ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Subject / Purpose</label>
            <input type="text" name="subject" value="<?php echo e($_SESSION['old']['subject'] ?? ''); ?>" placeholder="e.g. Initial consultation, Follow-up, Document review...">
            <?php if (!empty($_SESSION['errors']['subject'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['subject']); ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Select Date</label>
            <?php include VIEW_PATH . '/partials/calendar-widget.php'; ?>
        </div>
        <div class="form-group">
            <label>Time</label>
            <input type="time" name="time" id="sessionTime">
            <?php if (!empty($_SESSION['errors']['time'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['time']); ?></div><?php endif; ?>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">Schedule</button>
            <a href="<?php echo url('/counselor/sessions'); ?>" class="btn-secondary">Cancel</a>
        </div>
        <?php unset($_SESSION['errors'], $_SESSION['old']); ?>
    </form>
</div>
<script>
document.getElementById('sessionForm').addEventListener('submit', function(e) {
    var dateVal = document.getElementById('calendarWidget_value').value;
    var timeVal = document.getElementById('sessionTime').value;
    if (!dateVal) {
        e.preventDefault();
        alert('Please select a date from the calendar.');
        return false;
    }
    if (!timeVal) {
        e.preventDefault();
        alert('Please select a time.');
        return false;
    }
    var hiddatetime = document.createElement('input');
    hiddatetime.type = 'hidden';
    hiddatetime.name = 'datetime';
    hiddatetime.value = dateVal + ' ' + timeVal + ':00';
    this.appendChild(hiddatetime);
});
</script>
<?php $content = ob_get_clean(); include __DIR__ . '/../layouts/counselor-layout.php';