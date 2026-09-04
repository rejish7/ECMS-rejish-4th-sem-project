<?php ob_start(); ?>
<style>
    .form-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; max-width: 720px; }
    .form-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 500; color: #43474f; margin-bottom: 6px; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; height: 40px; padding: 0 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; background: #f9fafb; box-sizing: border-box; }
    .form-group textarea { height: 80px; padding: 10px 12px; resize: vertical; }
    .form-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-primary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .btn-primary:hover { background: #004aaf; }
    .btn-secondary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 14px; font-weight: 500; text-decoration: none; }
    .btn-secondary:hover { background: #f9fafb; }
    .doc-badge { display: inline-flex; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 500; margin-left: 8px; }
    .doc-badge--pending { background: #fef3c7; color: #d97706; }
    .doc-badge--approved { background: #d1fae5; color: #059669; }
    .doc-badge--rejected { background: #fee2e2; color: #dc2626; }
    .doc-badge--resubmit { background: #dbeafe; color: #2563eb; }
</style>
<div class="form-card">
    <h3>Edit Document <span class="doc-badge doc-badge--<?php echo e($document['status']); ?>"><?php echo e(ucfirst($document['status'])); ?></span></h3>
    <?php $docId = $document['id']; ?>
    <form method="POST" action="<?php echo url('/admin/documents/' . $docId . '/update'); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label>Student</label>
            <input type="text" value="<?php echo e($document['student_name'] ?? 'Unknown'); ?> (<?php echo e($document['student_code'] ?? 'N/A'); ?>)" disabled style="background:#f3f4f6;">
        </div>
        <div class="form-group">
            <label>Document Name</label>
            <input type="text" name="name" value="<?php echo e($document['name']); ?>">
            <?php if (!empty($_SESSION['errors']['name'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['name']); ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Category</label>
            <select name="category">
                <option value="education" <?php echo $document['category'] === 'education' ? 'selected' : ''; ?>>Education Documents</option>
                <option value="visa" <?php echo $document['category'] === 'visa' ? 'selected' : ''; ?>>Visa Documents</option>
            </select>
            <?php if (!empty($_SESSION['errors']['category'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['category']); ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description"><?php echo e($document['description'] ?? ''); ?></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">Update</button>
            <a href="<?php echo url('/admin/documents'); ?>" class="btn-secondary">Cancel</a>
        </div>
        <?php unset($_SESSION['errors'], $_SESSION['old']); ?>
    </form>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';
