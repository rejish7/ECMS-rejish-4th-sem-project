<?php ob_start(); ?>
<style>
    .doc-page { display: flex; flex-direction: column; gap: 24px; }
    .doc-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; }
    .doc-header h2 { margin: 0; color: #0b1c30; font-size: 32px; font-weight: 700; }
    .doc-header p { margin: 4px 0 0; color: #73777f; font-size: 14px; }
    .doc-btn-assign { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 20px; border-radius: 8px; background: #4338ca; color: #fff; font-size: 14px; font-weight: 500; text-decoration: none; }
    .doc-btn-assign:hover { background: #3730a3; }
    .doc-btn-upload { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 20px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; text-decoration: none; }
    .doc-btn-upload:hover { background: #004aaf; }
    .doc-btn-review { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 20px; border-radius: 8px; background: #059669; color: #fff; font-size: 14px; font-weight: 500; text-decoration: none; }
    .doc-btn-review:hover { background: #047857; }

    .doc-stats { display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; }
    @media (max-width: 900px) { .doc-stats { grid-template-columns: repeat(3, 1fr); } }
    .doc-stat { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 14px; text-align: center; }
    .doc-stat__value { font-size: 22px; font-weight: 700; color: #0b1c30; }
    .doc-stat__label { font-size: 11px; color: #73777f; margin-top: 2px; text-transform: uppercase; }

    .doc-filters { display: flex; gap: 12px; align-items: center; }
    .doc-filters select, .doc-filters input { height: 36px; padding: 0 12px; border: 1px solid #d0d5dd; border-radius: 8px; font-size: 13px; color: #344054; background: #fff; }
    .doc-filters select:focus, .doc-filters input:focus { outline: none; border-color: #0054cb; }
    .doc-filters button { height: 36px; padding: 0 16px; border: 1px solid #d0d5dd; border-radius: 8px; background: #fff; font-size: 13px; color: #344054; cursor: pointer; }
    .doc-filters button:hover { background: #f9fafb; }

    .doc-table-card { border: 1px solid #e5e7eb; border-radius: 12px; background: #fff; overflow: hidden; }
    .doc-table-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; border-bottom: 1px solid #e5e7eb; }
    .doc-table-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #0b1c30; }
    .doc-table-wrap { overflow-x: auto; }
    .doc-table { width: 100%; border-collapse: collapse; }
    .doc-table th { padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: #9ca3af; background: #fafbfc; border-bottom: 1px solid #e5e7eb; }
    .doc-table th:last-child { text-align: right; }
    .doc-table td { padding: 14px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #43474f; }
    .doc-table tbody tr:last-child td { border-bottom: none; }
    .doc-table tbody tr:hover { background: #fafbff; }

    .doc-student { display: flex; align-items: center; gap: 10px; }
    .doc-avatar { width: 32px; height: 32px; border-radius: 50%; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; flex-shrink: 0; }
    .doc-student-name { font-weight: 500; color: #0b1c30; }
    .doc-student-id { font-size: 12px; color: #73777f; }

    .doc-badge { display: inline-flex; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 500; }
    .doc-badge--education { background: #dbeafe; color: #1d4ed8; }
    .doc-badge--visa { background: #fef3c7; color: #d97706; }
    .doc-badge--assigned { background: #e0e7ff; color: #4338ca; }
    .doc-badge--pending { background: #fef3c7; color: #d97706; }
    .doc-badge--approved { background: #d1fae5; color: #059669; }
    .doc-badge--rejected { background: #fee2e2; color: #dc2626; }
    .doc-badge--resubmit { background: #dbeafe; color: #2563eb; }

    .doc-actions { display: flex; gap: 4px; justify-content: flex-end; }
    .doc-action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: none; background: none; cursor: pointer; text-decoration: none; }
    .doc-action-btn:hover { background: #f3f4f6; }
    .doc-action-btn svg { width: 16px; height: 16px; }
    .doc-action-btn--view svg { color: #2563eb; }
    .doc-action-btn--assign svg { color: #4338ca; }
    .doc-action-btn--delete svg { color: #ef4444; }

    .doc-empty { text-align: center; padding: 40px 20px; color: #9ca3af; font-size: 14px; }
    .doc-pagination { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-top: 1px solid #e5e7eb; }
    .doc-pagination-info { font-size: 13px; color: #73777f; }
</style>
<div class="doc-page">
    <section class="doc-header">
        <div><h2>Documents</h2><p>Manage student documents for education and visa processing.</p></div>
        <div style="display:flex;gap:10px;">
            <a href="<?php echo url('/admin/documents/assign'); ?>" class="doc-btn-assign">+ Assign Required</a>
            <a href="<?php echo url('/admin/documents/review-queue'); ?>" class="doc-btn-review">Review Queue</a>
            <a href="<?php echo url('/admin/documents/create'); ?>" class="doc-btn-upload">Upload Document</a>
        </div>
    </section>

    <section class="doc-stats">
        <div class="doc-stat"><div class="doc-stat__value"><?php echo e($stats['total'] ?? 0); ?></div><div class="doc-stat__label">Total</div></div>
        <div class="doc-stat"><div class="doc-stat__value" style="color:#4338ca;"><?php echo e($stats['assigned'] ?? 0); ?></div><div class="doc-stat__label">Assigned</div></div>
        <div class="doc-stat"><div class="doc-stat__value" style="color:#d97706;"><?php echo e($stats['pending'] ?? 0); ?></div><div class="doc-stat__label">Pending</div></div>
        <div class="doc-stat"><div class="doc-stat__value" style="color:#059669;"><?php echo e($stats['approved'] ?? 0); ?></div><div class="doc-stat__label">Approved</div></div>
        <div class="doc-stat"><div class="doc-stat__value" style="color:#2563eb;"><?php echo e($stats['resubmit'] ?? 0); ?></div><div class="doc-stat__label">Resubmit</div></div>
        <div class="doc-stat"><div class="doc-stat__value" style="color:#dc2626;"><?php echo e($stats['rejected'] ?? 0); ?></div><div class="doc-stat__label">Rejected</div></div>
    </section>

    <section class="doc-table-card">
        <div class="doc-table-header">
            <h3>All Documents</h3>
            <form class="doc-filters" method="GET" action="<?php echo url('/admin/documents'); ?>">
                <input type="text" name="search" placeholder="Search student or document..." value="<?php echo e($filters['search'] ?? ''); ?>">
                <select name="category">
                    <option value="">All Categories</option>
                    <option value="education" <?php echo ($filters['category'] ?? '') === 'education' ? 'selected' : ''; ?>>Education</option>
                    <option value="visa" <?php echo ($filters['category'] ?? '') === 'visa' ? 'selected' : ''; ?>>Visa</option>
                </select>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="assigned" <?php echo ($filters['status'] ?? '') === 'assigned' ? 'selected' : ''; ?>>Assigned</option>
                    <option value="pending" <?php echo ($filters['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="approved" <?php echo ($filters['status'] ?? '') === 'approved' ? 'selected' : ''; ?>>Approved</option>
                    <option value="rejected" <?php echo ($filters['status'] ?? '') === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                    <option value="resubmit" <?php echo ($filters['status'] ?? '') === 'resubmit' ? 'selected' : ''; ?>>Resubmit</option>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>
        <div class="doc-table-wrap">
            <table class="doc-table">
                <thead><tr><th>Student</th><th>Document Name</th><th>Category</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php if (!empty($documents)): ?>
                        <?php foreach ($documents as $doc): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo url('/admin/documents/student/' . $doc['student_id']); ?>" class="doc-student" style="text-decoration:none;color:inherit;">
                                        <div class="doc-avatar"><?php echo e(substr($doc['student_name'] ?? 'NA', 0, 2)); ?></div>
                                        <div>
                                            <div class="doc-student-name"><?php echo e($doc['student_name'] ?? 'Unknown'); ?></div>
                                            <div class="doc-student-id"><?php echo e($doc['student_code'] ?? '-'); ?></div>
                                        </div>
                                    </a>
                                </td>
                                <td><?php echo e($doc['name']); ?></td>
                                <td><span class="doc-badge doc-badge--<?php echo e($doc['category']); ?>"><?php echo e(ucfirst($doc['category'])); ?></span></td>
                                <td><span class="doc-badge doc-badge--<?php echo e($doc['status']); ?>"><?php echo e(ucfirst($doc['status'])); ?></span></td>
                                <td><?php echo e(date('M d, Y', strtotime($doc['created_at']))); ?></td>
                                <td>
                                    <div class="doc-actions">
                                        <a href="<?php echo url('/admin/documents/assign?student_id=' . $doc['student_id']); ?>" class="doc-action-btn doc-action-btn--assign" title="Assign required document to this student"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg></a>
                                        <a href="<?php echo url('/admin/documents/' . $doc['id']); ?>" class="doc-action-btn doc-action-btn--view"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg></a>
                                        <form method="POST" action="<?php echo url('/admin/documents/' . $doc['id'] . '/delete'); ?>" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="doc-action-btn doc-action-btn--delete"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg></button></form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="doc-empty">No documents found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="doc-pagination"><span class="doc-pagination-info">Showing <?php echo count($documents); ?> of <?php echo e($total); ?> documents</span></div>
    </section>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';
