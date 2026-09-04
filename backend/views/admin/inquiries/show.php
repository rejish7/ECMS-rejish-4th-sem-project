<?php ob_start(); ?>
<style>
    .detail-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; max-width: 720px; }
    .detail-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .detail-row { display: flex; padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
    .detail-label { width: 180px; font-size: 14px; font-weight: 500; color: #73777f; }
    .detail-value { font-size: 14px; color: #0b1c30; }
    .detail-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-secondary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 14px; font-weight: 500; text-decoration: none; }
    .btn-primary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #059669; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .btn-primary:hover { background: #047857; }
    .btn-auto { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #7c3aed; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .btn-auto:hover { background: #6d28d9; }
    .btn-danger { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #ef4444; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .btn-close { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #6b7280; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .btn-close:hover { background: #4b5563; }
    .inq-badge { display: inline-flex; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
    .inq-badge--new { background: #fef3c7; color: #d97706; }
    .inq-badge--assigned { background: #dbeafe; color: #2563eb; }
    .inq-badge--in-progress { background: #fef3c7; color: #d97706; border: 1px solid #fcd34d; }
    .inq-badge--closed { background: #f3f4f6; color: #6b7280; }

    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
    .modal-overlay.active { display: flex; }
    .modal { background: #fff; border-radius: 12px; width: 100%; max-width: 440px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
    .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid #e5e7eb; }
    .modal-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #0b1c30; }
    .modal-close { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border: none; background: none; cursor: pointer; border-radius: 6px; color: #73777f; }
    .modal-close:hover { background: #f3f4f6; }
    .modal-body { padding: 24px; }
    .modal-body label { display: block; font-size: 14px; font-weight: 500; color: #344054; margin-bottom: 8px; }
    .modal-body select { width: 100%; height: 42px; padding: 0 12px; border: 1px solid #d0d5dd; border-radius: 8px; font-size: 14px; color: #344054; background: #fff; }
    .modal-body select:focus { outline: none; border-color: #0054cb; }
    .modal-footer { display: flex; gap: 12px; justify-content: flex-end; padding: 16px 24px; border-top: 1px solid #e5e7eb; }
    .modal-btn { height: 40px; padding: 0 20px; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; border: none; }
    .modal-btn--cancel { background: #fff; color: #43474f; border: 1px solid #e5e7eb; }
    .modal-btn--cancel:hover { background: #f9fafb; }
    .modal-btn--primary { background: #0054cb; color: #fff; }
    .modal-btn--primary:hover { background: #0043a3; }
</style>
<div class="detail-card">
    <h3>Inquiry Details</h3>
    <div class="detail-row"><span class="detail-label">Inquiry ID</span><span class="detail-value">#<?php echo e($inquiry['inquiry_id']); ?></span></div>
    <div class="detail-row"><span class="detail-label">Student</span><span class="detail-value"><?php echo e($inquiry['student_name'] ?? '-'); ?> (<?php echo e($inquiry['student_code'] ?? ''); ?>)</span></div>
    <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value"><?php echo e($inquiry['student_email'] ?? '-'); ?></span></div>
    <div class="detail-row"><span class="detail-label">Country</span><span class="detail-value"><?php echo e($inquiry['country_of_interest'] ?? '-'); ?></span></div>
    <div class="detail-row"><span class="detail-label">Level of Study</span><span class="detail-value"><?php echo e($inquiry['level_of_study'] ?? '-'); ?></span></div>
    <div class="detail-row"><span class="detail-label">Message</span><span class="detail-value"><?php echo e($inquiry['message'] ?? '-'); ?></span></div>
    <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value"><span class="inq-badge inq-badge--<?php echo e($inquiry['status']); ?>"><?php echo e(ucwords(str_replace('-', ' ', $inquiry['status']))); ?></span></span></div>
    <div class="detail-row"><span class="detail-label">Counselor</span><span class="detail-value"><?php echo e($inquiry['counselor_name'] ?? 'Unassigned'); ?></span></div>
    <div class="detail-row"><span class="detail-label">Created</span><span class="detail-value"><?php echo e($inquiry['created_at'] ?? '-'); ?></span></div>
    <div class="detail-actions">
        <?php if (($inquiry['status'] ?? '') !== 'closed'): ?>
            <form method="POST" action="<?php echo url('/admin/inquiries/' . $inquiry['id'] . '/close'); ?>" style="display:inline;" onsubmit="return confirm('Mark this inquiry as closed? The student will be able to submit a new inquiry for this country.')">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn-close">Mark as Closed</button>
            </form>
            <button type="button" class="btn-primary" onclick="document.getElementById('assignModal').classList.add('active')">Assign Counselor</button>
            <form method="POST" action="<?php echo url('/admin/inquiries/' . $inquiry['id'] . '/auto-assign'); ?>" style="display:inline;" onsubmit="return confirm('Auto-assign to least busy counselor?')">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn-auto">Auto-Assign</button>
            </form>
        <?php endif; ?>
        <form method="POST" action="<?php echo url('/admin/inquiries/' . $inquiry['id'] . '/delete'); ?>" onsubmit="return confirm('Are you sure you want to delete this inquiry?')">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-danger">Delete</button>
        </form>
        <a href="<?php echo url('/admin/inquiries'); ?>" class="btn-secondary">Back to List</a>
    </div>
</div>

<!-- Assign Counselor Modal -->
<div class="modal-overlay" id="assignModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Assign Counselor</h3>
            <button type="button" class="modal-close" onclick="document.getElementById('assignModal').classList.remove('active')">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form id="assignForm" method="POST" action="<?php echo url('/admin/inquiries/' . $inquiry['id'] . '/assign'); ?>">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <p style="margin:0 0 16px;font-size:13px;color:#73777f;">Assign a counselor to this inquiry</p>
                <label for="counselor_id">Select Counselor</label>
                <select name="counselor_id" id="counselor_id">
                    <option value="">-- Choose a counselor --</option>
                    <?php foreach ($counselors as $counselor): ?>
                        <option value="<?php echo e($counselor['id']); ?>" <?php echo ($inquiry['counselor_id'] ?? '') == $counselor['id'] ? 'selected' : ''; ?>><?php echo e($counselor['name']); ?> (<?php echo e($counselor['specialization']); ?>)</option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($_SESSION['errors']['counselor_id'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['counselor_id']); ?></div><?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn modal-btn--cancel" onclick="document.getElementById('assignModal').classList.remove('active')">Cancel</button>
                <button type="submit" class="modal-btn modal-btn--primary">Assign Counselor</button>
            </div>
            <?php unset($_SESSION['errors'], $_SESSION['old']); ?>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            overlay.classList.remove('active');
        }
    });
});
</script>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';