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
    .doc-preview-frame { border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; background: #f9fafb; }
    .doc-preview-media { display: block; margin: 0 auto; }
    .doc-preview-placeholder { display: flex; align-items: center; justify-content: center; gap: 10px; min-height: 120px; border: 1px dashed #d0d5dd; border-radius: 10px; background: #f9fafb; font-size: 13px; color: #73777f; }
    .doc-preview-link { font-size: 13px; margin-top: 10px; }
    .doc-preview-link a { color: #0054cb; font-weight: 500; }
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
    <?php
        $filePath = $document['file_path'] ?? '';
        $fileClean = '/' . ltrim($filePath, '/');
        $fileUrl = $filePath !== '' ? url($fileClean) : '';
        $diskPath = $filePath !== '' ? BASE_PATH . $fileClean : '';
        $fileExists = $diskPath !== '' && is_file($diskPath);
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg']);
        $isPdf = $ext === 'pdf';
    ?>
    <div class="detail-row" style="flex-direction:column;">
        <span class="detail-label" style="margin-bottom:10px;">Preview</span>
        <?php if ($fileUrl === ''): ?>
            <div class="doc-preview-placeholder">No file attached to this document.</div>
        <?php elseif (!$fileExists): ?>
            <div class="doc-preview-placeholder">File not found on the server.</div>
        <?php elseif ($isPdf): ?>
            <div class="doc-preview-frame">
                <iframe src="<?php echo e($fileUrl); ?>" width="100%" height="520" style="border:none;background:#fff;" title="Document preview"></iframe>
            </div>
        <?php elseif ($isImage): ?>
            <div class="doc-preview-frame">
                <img src="<?php echo e($fileUrl); ?>" alt="<?php echo e($document['name']); ?>" class="doc-preview-media" style="max-width:100%;max-height:520px;">
            </div>
        <?php else: ?>
            <div class="doc-preview-placeholder">
                <span>Preview not available for this file type.</span>
            </div>
        <?php endif; ?>
        <?php if ($fileUrl !== ''): ?>
            <div class="doc-preview-link"><a href="<?php echo e($fileUrl); ?>" target="_blank" rel="noopener">Open file in new tab</a></div>
        <?php endif; ?>
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
        <form method="POST" action="<?php echo url('/admin/documents/' . $document['id'] . '/delete'); ?>" onsubmit="return confirm('Are you sure?')">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-danger">Delete</button>
        </form>
        <a href="<?php echo url('/admin/documents'); ?>" class="btn-secondary">Back to List</a>
    </div>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';
