<?php ob_start(); ?>
<style>
    .inq-page { display: flex; flex-direction: column; gap: 24px; }
    .inq-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; }
    .inq-header h2 { margin: 0; color: #0b1c30; font-size: 32px; font-weight: 700; }
    .inq-header p { margin: 4px 0 0; color: #73777f; font-size: 14px; }

    .inq-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .inq-stat { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; text-align: center; }
    .inq-stat__value { font-size: 28px; font-weight: 700; color: #0b1c30; }
    .inq-stat__label { font-size: 13px; color: #73777f; margin-top: 4px; }

    .inq-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
    .inq-card-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid #e5e7eb; }
    .inq-card-header h3 { font-size: 16px; font-weight: 700; color: #0b1c30; margin: 0; }

    .inq-filters { display: flex; gap: 12px; align-items: center; }
    .inq-filters select, .inq-filters input { height: 36px; padding: 0 12px; border: 1px solid #d0d5dd; border-radius: 8px; font-size: 13px; color: #344054; background: #fff; }
    .inq-filters select:focus, .inq-filters input:focus { outline: none; border-color: #0054cb; }
    .inq-filters button { height: 36px; padding: 0 16px; border: 1px solid #d0d5dd; border-radius: 8px; background: #fff; font-size: 13px; color: #344054; cursor: pointer; }
    .inq-filters button:hover { background: #f9fafb; }

    .inq-table { width: 100%; border-collapse: collapse; }
    .inq-table th { padding: 12px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; background: #fafbfc; border-bottom: 1px solid #e5e7eb; }
    .inq-table th:last-child, .inq-table th:nth-child(5) { text-align: right; }
    .inq-table td { padding: 16px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #43474f; }
    .inq-table td:last-child, .inq-table td:nth-child(5) { text-align: right; }
    .inq-table tbody tr:last-child td { border-bottom: none; }
    .inq-table tbody tr:hover { background: #fafbff; cursor: pointer; }

    .inq-id { color: #0054cb; font-weight: 600; }
    .inq-date { font-size: 13px; color: #73777f; }
    .inq-badge { display: inline-flex; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
    .inq-badge--new { background: #fef3c7; color: #d97706; }
    .inq-badge--assigned { background: #dbeafe; color: #2563eb; }
    .inq-badge--in-progress { background: #fef3c7; color: #d97706; border: 1px solid #fcd34d; }
    .inq-badge--closed { background: #f3f4f6; color: #6b7280; }

    .inq-counselor { display: flex; align-items: center; gap: 8px; justify-content: flex-end; }
    .inq-counselor__avatar { width: 28px; height: 28px; border-radius: 50%; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; }
    .inq-counselor__name { font-size: 13px; color: #43474f; }

    .inq-actions { display: flex; gap: 4px; justify-content: flex-end; }
    .inq-action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: none; background: none; cursor: pointer; text-decoration: none; }
    .inq-action-btn:hover { background: #f3f4f6; }
    .inq-action-btn svg { width: 16px; height: 16px; }
    .inq-action-btn--view svg { color: #2563eb; }
    .inq-action-btn--assign svg { color: #059669; }
    .inq-action-btn--auto svg { color: #7c3aed; }
    .inq-action-btn--close svg { color: #6b7280; }
    .inq-action-btn--delete svg { color: #ef4444; }

    .inq-empty { text-align: center; padding: 40px 20px; color: #9ca3af; font-size: 14px; }
    .inq-pagination { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-top: 1px solid #e5e7eb; }
    .inq-pagination__info { font-size: 13px; color: #73777f; }

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

    @media (max-width: 768px) {
        .inq-stats { grid-template-columns: repeat(2, 1fr); }
    }</style>

<div class="inq-page">
    <section class="inq-header">
        <div><h2>Student Inquiries</h2><p>View and manage inquiries submitted by students.</p></div>
    </section>

    <section class="inq-stats">
        <div class="inq-stat"><div class="inq-stat__value"><?php echo e($stats['total'] ?? 0); ?></div><div class="inq-stat__label">Total Inquiries</div></div>
        <div class="inq-stat"><div class="inq-stat__value" style="color:#d97706;"><?php echo e($stats['new_count'] ?? 0); ?></div><div class="inq-stat__label">New</div></div>
        <div class="inq-stat"><div class="inq-stat__value" style="color:#2563eb;"><?php echo e($stats['in_progress'] ?? 0); ?></div><div class="inq-stat__label">In Progress</div></div>
        <div class="inq-stat"><div class="inq-stat__value" style="color:#059669;"><?php echo e($stats['closed'] ?? 0); ?></div><div class="inq-stat__label">Closed</div></div>
    </section>

    <section class="inq-card">
        <div class="inq-card-header">
            <h3>All Inquiries</h3>
            <form class="inq-filters" method="GET" action="<?php echo url('/admin/inquiries'); ?>">
                <input type="text" name="search" placeholder="Search..." value="<?php echo e($filters['search'] ?? ''); ?>">
                <select name="status">
                    <option value="">All Status</option>
                    <option value="new" <?php echo ($filters['status'] ?? '') === 'new' ? 'selected' : ''; ?>>New</option>
                    <option value="assigned" <?php echo ($filters['status'] ?? '') === 'assigned' ? 'selected' : ''; ?>>Assigned</option>
                    <option value="in-progress" <?php echo ($filters['status'] ?? '') === 'in-progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="closed" <?php echo ($filters['status'] ?? '') === 'closed' ? 'selected' : ''; ?>>Closed</option>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>
        <table class="inq-table">
            <thead>
                <tr>
                    <th>Inquiry ID</th>
                    <th>Student</th>
                    <th>Country</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Counselor</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($inquiries)): ?>
                    <?php foreach ($inquiries as $inq): ?>
                        <tr>
                            <td><span class="inq-id">#<?php echo e($inq['inquiry_id']); ?></span></td>
                            <td><?php echo e($inq['student_name'] ?? '-'); ?></td>
                            <td><?php echo e($inq['country_of_interest'] ?? '-'); ?></td>
                            <td>
                                <div class="inq-date"><?php echo e(date('M d, Y', strtotime($inq['created_at']))); ?></div>
                            </td>
                            <td><span class="inq-badge inq-badge--<?php echo e($inq['status']); ?>"><?php echo e(ucwords(str_replace('-', ' ', $inq['status']))); ?></span></td>
                            <td>
                                <?php if (!empty($inq['counselor_name'])): ?>
                                    <div class="inq-counselor">
                                        <div class="inq-counselor__avatar"><?php echo e(substr($inq['counselor_name'], 0, 2)); ?></div>
                                        <span class="inq-counselor__name"><?php echo e($inq['counselor_name']); ?></span>
                                    </div>
                                <?php else: ?>
                                    <span style="color:#9ca3af;font-style:italic;">Unassigned</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="inq-actions">
                                    <a href="<?php echo url('/admin/inquiries/' . $inq['id']); ?>" class="inq-action-btn inq-action-btn--view" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z" /></svg>
                                    </a>
                                    <?php if (($inq['status'] ?? '') !== 'closed'): ?>
                                    <form method="POST" action="<?php echo url('/admin/inquiries/' . $inq['id'] . '/auto-assign'); ?>" style="display:inline;" onsubmit="return confirm('Auto-assign to least busy counselor?')">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="inq-action-btn inq-action-btn--auto" title="Auto-Assign">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="<?php echo url('/admin/inquiries/' . $inq['id'] . '/close'); ?>" style="display:inline;" onsubmit="return confirm('Mark this inquiry as closed? The student will be able to submit a new inquiry for this country.')">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="inq-action-btn inq-action-btn--close" title="Mark as Closed">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    <form method="POST" action="<?php echo url('/admin/inquiries/' . $inq['id'] . '/delete'); ?>" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="inq-action-btn inq-action-btn--delete" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="inq-empty">No inquiries found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="inq-pagination">
            <span class="inq-pagination__info">Showing <?php echo count($inquiries); ?> of <?php echo e($total); ?> inquiries</span>
        </div>
    </section>
</div>

<!-- Assign Counselor Modal -->
<div class="modal-overlay" id="assignModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Assign Counselor</h3>
            <button type="button" class="modal-close" onclick="closeAssignModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form id="assignForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <p style="margin:0 0 16px;font-size:13px;color:#73777f;">Assign a counselor to inquiry <strong id="assignInquiryId"></strong></p>
                <label for="counselor_id">Select Counselor</label>
                <select name="counselor_id" id="counselor_id">
                    <option value="">-- Choose a counselor --</option>
                    <?php foreach ($counselors as $counselor): ?>
                        <option value="<?php echo e($counselor['id']); ?>"><?php echo e($counselor['name']); ?> (<?php echo e($counselor['specialization']); ?>)</option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($_SESSION['errors']['counselor_id'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['counselor_id']); ?></div><?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn modal-btn--cancel" onclick="closeAssignModal()">Cancel</button>
                <button type="submit" class="modal-btn modal-btn--primary">Assign Counselor</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAssignModal(id, inquiryId) {
    document.getElementById('assignForm').action = '/admin/inquiries/' + id + '/assign';
    document.getElementById('assignInquiryId').textContent = '#' + inquiryId;
    document.getElementById('assignModal').classList.add('active');
}
function closeAssignModal() {
    document.getElementById('assignModal').classList.remove('active');
    document.getElementById('counselor_id').value = '';
}
document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            overlay.classList.remove('active');
        }
    });
});
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin-layout.php';
