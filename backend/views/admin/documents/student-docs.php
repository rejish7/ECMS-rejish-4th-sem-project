<?php ob_start(); ?>
<style>
    .student-docs-page { display: flex; flex-direction: column; gap: 24px; max-width: 900px; }
    .student-info { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; display: flex; align-items: center; gap: 20px; }
    .student-avatar { width: 60px; height: 60px; border-radius: 50%; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 700; flex-shrink: 0; }
    .student-details h2 { margin: 0; font-size: 20px; font-weight: 700; color: #0b1c30; }
    .student-details p { margin: 4px 0 0; font-size: 13px; color: #73777f; }
    .doc-section { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
    .doc-section__header { display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; border-bottom: 1px solid #e5e7eb; }
    .doc-section__title { font-size: 16px; font-weight: 700; color: #0b1c30; }
    .doc-table { width: 100%; border-collapse: collapse; }
    .doc-table th { padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: #9ca3af; background: #fafbfc; border-bottom: 1px solid #e5e7eb; }
    .doc-table td { padding: 14px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #43474f; }
    .doc-table tbody tr:last-child td { border-bottom: none; }
    .doc-badge { display: inline-flex; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 500; }
    .doc-badge--education { background: #dbeafe; color: #1d4ed8; }
    .doc-badge--visa { background: #fef3c7; color: #d97706; }
    .doc-badge--pending { background: #fef3c7; color: #d97706; }
    .doc-badge--approved { background: #d1fae5; color: #059669; }
    .doc-badge--rejected { background: #fee2e2; color: #dc2626; }
    .doc-badge--resubmit { background: #dbeafe; color: #2563eb; }
    .doc-empty { text-align: center; padding: 40px 20px; color: #9ca3af; font-size: 14px; }
    .btn-secondary { display: inline-flex; align-items: center; height: 36px; padding: 0 16px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 13px; font-weight: 500; text-decoration: none; }
    .btn-secondary:hover { background: #f9fafb; }
</style>
<div class="student-docs-page">
    <section class="student-info">
        <div class="student-avatar"><?php echo e(substr($student['name'], 0, 2)); ?></div>
        <div class="student-details">
            <h2><?php echo e($student['name']); ?></h2>
            <p><?php echo e($student['email'] ?? ''); ?> | <?php echo e($student['phone'] ?? ''); ?></p>
            <p>Student Code: <?php echo e($student['student_code'] ?? 'N/A'); ?></p>
        </div>
    </section>

    <section class="doc-section">
        <div class="doc-section__header">
            <span class="doc-section__title">All Documents (<?php echo count($documents); ?>)</span>
            <a href="<?php echo url('/admin/documents/create'); ?>" class="btn-secondary">+ Upload New</a>
        </div>
        <?php if (!empty($documents)): ?>
            <table class="doc-table">
                <thead><tr><th>Document</th><th>Category</th><th>Status</th><th>Date</th><th>Remarks</th></tr></thead>
                <tbody>
                    <?php foreach ($documents as $doc): ?>
                        <tr>
                            <td><a href="<?php echo url('/admin/documents/' . $doc['id']); ?>" style="color:#0054cb;text-decoration:none;font-weight:500;"><?php echo e($doc['name']); ?></a></td>
                            <td><span class="doc-badge doc-badge--<?php echo e($doc['category']); ?>"><?php echo e(ucfirst($doc['category'])); ?></span></td>
                            <td><span class="doc-badge doc-badge--<?php echo e($doc['status']); ?>"><?php echo e(ucfirst($doc['status'])); ?></span></td>
                            <td><?php echo e(date('M d, Y', strtotime($doc['created_at']))); ?></td>
                            <td><?php echo e($doc['remarks'] ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="doc-empty">No documents uploaded yet.</div>
        <?php endif; ?>
    </section>

    <div style="margin-top:8px;">
        <a href="<?php echo url('/admin/documents'); ?>" class="btn-secondary">Back to All Documents</a>
    </div>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';
