<?php ob_start(); ?>
<style>
    .detail-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; max-width: 720px; }
    .detail-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .detail-row { display: flex; padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
    .detail-label { width: 180px; font-size: 14px; font-weight: 500; color: #73777f; flex-shrink: 0; }
    .detail-value { font-size: 14px; color: #0b1c30; }
    .detail-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-primary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; }
    .btn-secondary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 14px; font-weight: 500; text-decoration: none; }
    .btn-danger { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #ef4444; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .doc-badge { display: inline-flex; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 500; }
    .doc-badge--education { background: #dbeafe; color: #1d4ed8; }
    .doc-badge--visa { background: #fef3c7; color: #d97706; }
    .doc-badge--pending { background: #fef3c7; color: #d97706; }
    .doc-badge--approved { background: #d1fae5; color: #059669; }
    .doc-badge--rejected { background: #fee2e2; color: #dc2626; }
    .doc-badge--resubmit { background: #dbeafe; color: #2563eb; }
    .doc-remarks { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; margin-top: 8px; font-size: 13px; color: #43474f; }
</style>
<div class="detail-card">
    <h3>Document Details</h3>
    <div class="detail-row">
        <span class="detail-label">Student</span>
        <span class="detail-value"><?php echo e($document['student_name'] ?? 'Unknown'); ?> (<?php echo e($document['student_code'] ?? 'N/A'); ?>)</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Document Name</span>
        <span class="detail-value"><?php echo e($document['name']); ?></span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Category</span>
        <span class="detail-value"><span class="doc-badge doc-badge--<?php echo e($document['category']); ?>"><?php echo e(ucfirst($document['category'])); ?></span></span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="detail-value"><span class="doc-badge doc-badge--<?php echo e($document['status']); ?>"><?php echo e(ucfirst($document['status'])); ?></span></span>
    </div>
    <?php if (!empty($document['description'])): ?>
        <div class="detail-row">
            <span class="detail-label">Description</span>
            <span class="detail-value"><?php echo e($document['description']); ?></span>
        </div>
    <?php endif; ?>
    <div class="detail-row">
        <span class="detail-label">File</span>
        <span class="detail-value"><a href="<?php echo url('/uploads/documents/' . $document['file_path']); ?>" target="_blank"><?php echo e($document['file_path']); ?></a></span>
    </div>
    <?php if ($document['status'] !== 'pending'): ?>
        <div class="detail-row">
            <span class="detail-label">Reviewed By</span>
            <span class="detail-value"><?php echo e($document['reviewed_by_name'] ?? '-'); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Reviewed At</span>
            <span class="detail-value"><?php echo e($document['reviewed_at'] ?? '-'); ?></span>
        </div>
        <?php if (!empty($document['remarks'])): ?>
            <div class="detail-row" style="flex-direction:column;">
                <span class="detail-label" style="margin-bottom:6px;">Remarks</span>
                <div class="doc-remarks"><?php echo e($document['remarks']); ?></div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="detail-row">
        <span class="detail-label">Uploaded By</span>
        <span class="detail-value"><?php echo e($document['uploaded_by_name'] ?? '-'); ?></span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Date</span>
        <span class="detail-value"><?php echo e($document['created_at'] ?? '-'); ?></span>
    </div>
    <div class="detail-actions">
        <?php if ($document['status'] === 'pending'): ?>
            <a href="<?php echo url('/admin/documents/' . $document['id'] . '/edit'); ?>" class="btn-primary">Edit</a>
        <?php endif; ?>
        <form method="POST" action="<?php echo url('/admin/documents/' . $document['id'] . '/delete'); ?>" onsubmit="return confirm('Are you sure?')"><button type="submit" class="btn-danger">Delete</button></form>
        <a href="<?php echo url('/admin/documents'); ?>" class="btn-secondary">Back to List</a>
    </div>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';
