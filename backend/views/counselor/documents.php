<?php ob_start(); ?>
<style>
    .doc-page { display: flex; flex-direction: column; gap: 24px; }
    .doc-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; }
    .doc-header h2 { margin: 0; color: #0b1c30; font-size: 32px; font-weight: 700; }
    .doc-header p { margin: 4px 0 0; color: #73777f; font-size: 14px; }
    .doc-btn-assign { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 20px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; text-decoration: none; }
    .doc-btn-assign:hover { background: #004aaf; }

    .doc-table-card { border: 1px solid #e5e7eb; border-radius: 12px; background: #fff; overflow: hidden; }
    .doc-table-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; border-bottom: 1px solid #e5e7eb; }
    .doc-table-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #0b1c30; }
    .doc-table-wrap { overflow-x: auto; }
    .doc-table { width: 100%; border-collapse: collapse; }
    .doc-table th { padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: #9ca3af; background: #fafbfc; border-bottom: 1px solid #e5e7eb; }
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

    .doc-link { font-size: 13px; color: #0054cb; font-weight: 500; text-decoration: none; }
    .doc-link:hover { text-decoration: underline; }
    .doc-empty { text-align: center; padding: 40px 20px; color: #9ca3af; font-size: 14px; }

    .doc-review { display: flex; flex-direction: column; gap: 6px; }
    .doc-review .doc-review-top { display: flex; gap: 6px; }
    .doc-review select { height: 32px; padding: 0 8px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 13px; color: #344054; background: #fff; min-width: 120px; }
    .doc-review select:focus { outline: none; border-color: #0054cb; }
    .doc-review input[type="text"] { height: 32px; padding: 0 8px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 13px; color: #344054; background: #fff; flex: 1; }
    .doc-review input[type="text"]:focus { outline: none; border-color: #0054cb; }
    .doc-review button { height: 32px; padding: 0 12px; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; font-size: 13px; font-weight: 500; color: #43474f; cursor: pointer; white-space: nowrap; }
    .doc-review button:hover { background: #f9fafb; border-color: #d1d5db; }
    .doc-review .doc-review-remarks { display: flex; gap: 6px; }
</style>
<div class="doc-page">
    <section class="doc-header">
        <div><h2>Student Documents</h2><p>Documents for your assigned students.</p></div>
        <a href="<?php echo url('/counselor/documents/assign'); ?>" class="doc-btn-assign">Assign Required Document</a>
    </section>

    <section class="doc-table-card">
        <div class="doc-table-header"><h3>All Documents</h3></div>
        <?php if (!empty($documents)): ?>
            <div class="doc-table-wrap">
                <table class="doc-table">
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Student</th>
                            <th>Category</th>
                            <th>Assigned By</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $doc): ?>
                            <tr>
                                <td>
                                    <div style="font-weight:500;color:#0b1c30;"><?php echo e($doc['name']); ?></div>
                                    <?php if (!empty($doc['file_path'])): ?>
                                        <a class="doc-link" href="<?php echo e(url('/' . ltrim($doc['file_path'], '/'))); ?>" target="_blank" rel="noopener">View file</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="doc-student">
                                        <span class="doc-avatar"><?php echo e(substr($doc['student_name'] ?? '?', 0, 1)); ?></span>
                                        <div>
                                            <div class="doc-student-name"><?php echo e($doc['student_name'] ?? '-'); ?></div>
                                            <div class="doc-student-id"><?php echo e($doc['student_code'] ?? '-'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="doc-badge doc-badge--<?php echo e($doc['category']); ?>"><?php echo e(ucfirst($doc['category'])); ?></span></td>
                                <td><?php echo e($doc['assigned_by_name'] ?? '-'); ?></td>
                                <td><span class="doc-badge doc-badge--<?php echo e($doc['status']); ?>"><?php echo e(ucfirst($doc['status'])); ?></span></td>
                                <td><?php echo $doc['submitted_at'] ? e(date('M d, Y', strtotime($doc['submitted_at']))) : '<span style="color:#9ca3af;">Not yet</span>'; ?></td>
                                <td>
                                    <?php if (($doc['status'] ?? '') !== 'approved'): ?>
                                        <a href="<?php echo url('/counselor/documents/review-queue?status=' . e($doc['status']) . '&doc_id=' . $doc['id']); ?>" style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:6px;border:1px solid #d0d5dd;background:#fff;font-size:12px;font-weight:500;color:#344054;text-decoration:none;white-space:nowrap;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z"/></svg>
                                            Review
                                        </a>
                                    <?php else: ?>
                                        <div style="font-size:12px;color:#9ca3af;max-width:200px;"><?php echo e($doc['remarks'] ?? '-'); ?></div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="doc-empty">No documents found for your assigned students.</div>
        <?php endif; ?>
    </section>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../layouts/counselor-layout.php';