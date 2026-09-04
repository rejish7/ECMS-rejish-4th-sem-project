<?php ob_start();
$assetPath = url('/frontend/assets');
?>
<style>
.form-page{max-width:800px}
.form-page__header{margin-bottom:24px}
.form-page__header h2{margin:0;color:#0b1c30;font-size:32px;font-weight:700;letter-spacing:-0.64px}
.form-page__header p{margin:4px 0 0;color:#73777f;font-size:14px}
.form-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 1px 2px rgba(0,0,0,0.04);overflow:hidden}
.form-card__header{padding:20px 24px;border-bottom:1px solid #e5e7eb}
.form-card__header h3{margin:0;font-size:18px;font-weight:700;color:#0b1c30}
.form-card__body{padding:24px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:20px}
.form-group{margin-bottom:20px}
.form-group:last-child{margin-bottom:0}
.form-group label{display:block;font-size:13px;font-weight:500;color:#43474f;margin-bottom:6px}
.form-group input,.form-group select{width:100%;height:40px;padding:0 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;color:#101828;background:#fff;box-sizing:border-box}
.form-group input:focus,.form-group select:focus{outline:none;border-color:#0054cb;box-shadow:0 0 0 3px rgba(0,84,203,0.1)}
.form-group input::placeholder{color:#9ca3af}
.form-group--error input,.form-group--error select{border-color:#dc2626;box-shadow:0 0 0 3px rgba(220,38,38,0.1)}
.form-error{font-size:12px;color:#dc2626;margin-top:4px}
.form-actions{display:flex;gap:12px;padding:16px 24px;border-top:1px solid #e5e7eb;background:#fafbfc}
.btn-primary{display:inline-flex;align-items:center;height:40px;padding:0 24px;border-radius:8px;background:#0054cb;color:#fff;font-size:14px;font-weight:500;border:none;cursor:pointer;transition:background 0.15s;box-shadow:0 1px 2px rgba(0,84,203,0.2)}
.btn-primary:hover{background:#004aaf}
.btn-secondary{display:inline-flex;align-items:center;height:40px;padding:0 24px;border-radius:8px;background:#fff;color:#43474f;border:1px solid #e5e7eb;font-size:14px;font-weight:500;text-decoration:none;transition:background 0.15s}
.btn-secondary:hover{background:#f9fafb}
.cal-section{margin-top:4px}
</style>
<div class="form-page">
    <div class="form-page__header">
        <h2>Schedule Session</h2>
        <p>Create a new counseling session for a student.</p>
    </div>
    <div class="form-card">
        <div class="form-card__header"><h3>Session Details</h3></div>
        <form method="POST" action="<?php echo url('/admin/sessions/store'); ?>" id="sessionForm">
            <?php echo csrf_field(); ?>
            <div class="form-card__body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Student</label>
                        <select name="student_id">
                            <option value="">Select student</option>
                            <?php foreach ($students as $st): ?>
                                <option value="<?php echo e($st['id']); ?>" <?php echo ($_SESSION['old']['student_id'] ?? '') == $st['id'] ? 'selected' : ''; ?>><?php echo e($st['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($_SESSION['errors']['student_id'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['student_id']); ?></div><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Counselor</label>
                        <select name="counselor_id">
                            <option value="">Select counselor</option>
                            <?php foreach ($counselors as $c): ?>
                                <option value="<?php echo e($c['id']); ?>" <?php echo ($_SESSION['old']['counselor_id'] ?? '') == $c['id'] ? 'selected' : ''; ?>><?php echo e($c['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($_SESSION['errors']['counselor_id'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['counselor_id']); ?></div><?php endif; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Mode</label>
                    <select name="mode">
                        <option value="In-Person" <?php echo ($_SESSION['old']['mode'] ?? 'In-Person') === 'In-Person' ? 'selected' : ''; ?>>In-Person</option>
                        <option value="Video Call" <?php echo ($_SESSION['old']['mode'] ?? '') === 'Video Call' ? 'selected' : ''; ?>>Video Call</option>
                    </select>
                    <?php if (!empty($_SESSION['errors']['mode'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['mode']); ?></div><?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Subject / Purpose</label>
                    <input type="text" name="subject" value="<?php echo e($_SESSION['old']['subject'] ?? ''); ?>" placeholder="e.g. Initial consultation, Follow-up, Document review...">
                    <?php if (!empty($_SESSION['errors']['subject'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['subject']); ?></div><?php endif; ?>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Select Date</label>
                        <div class="cal-section">
                            <?php include VIEW_PATH . '/partials/calendar-widget.php'; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Time</label>
                        <input type="time" name="time" id="sessionTime">
                        <?php if (!empty($_SESSION['errors']['time'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['time']); ?></div><?php endif; ?>
                        <p style="font-size:12px;color:#9ca3af;margin-top:6px;">Choose a date from the calendar, then pick a time.</p>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">Schedule Session</button>
                <a href="<?php echo url('/admin/sessions'); ?>" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
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
<?php
unset($_SESSION['errors'], $_SESSION['old']);
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin-layout.php';
