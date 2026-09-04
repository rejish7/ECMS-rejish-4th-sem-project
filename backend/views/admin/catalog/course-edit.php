<?php ob_start(); ?>
<style>
    .form-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; max-width: 720px; }
    .form-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 500; color: #43474f; margin-bottom: 6px; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; height: 40px; padding: 0 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; background: #f9fafb; box-sizing: border-box; }
    .form-group textarea { height: 80px; padding: 10px 12px; resize: vertical; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-primary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .btn-primary:hover { background: #004aaf; }
    .btn-secondary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 14px; font-weight: 500; text-decoration: none; }
    .btn-secondary:hover { background: #f9fafb; }
</style>
<div class="form-card">
    <h3>Edit Course</h3>
    <form method="POST" action="<?php echo url('/admin/catalog/course/' . $course['id'] . '/update'); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label>College *</label>
            <select name="college_id">
                <option value="">Select College</option>
                <?php if (!empty($colleges)): ?>
                    <?php foreach ($colleges as $college): ?>
                        <option value="<?php echo e($college['id']); ?>" <?php echo $course['college_id'] == $college['id'] ? 'selected' : ''; ?>><?php echo e($college['name'] . ' (' . $college['country'] . ')'); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <?php if (!empty($_SESSION['errors']['college_id'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['college_id']); ?></div><?php endif; ?>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Course Name *</label>
                <input type="text" name="name" value="<?php echo e($course['name']); ?>">
                <?php if (!empty($_SESSION['errors']['name'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['name']); ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label>Course Code *</label>
                <input type="text" name="code" value="<?php echo e($course['code']); ?>" maxlength="20">
                <?php if (!empty($_SESSION['errors']['code'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['code']); ?></div><?php endif; ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Level *</label>
                <select name="level">
                    <option value="bachelor" <?php echo $course['level'] === 'bachelor' ? 'selected' : ''; ?>>Bachelor</option>
                    <option value="master" <?php echo $course['level'] === 'master' ? 'selected' : ''; ?>>Master</option>
                    <option value="diploma" <?php echo $course['level'] === 'diploma' ? 'selected' : ''; ?>>Diploma</option>
                    <option value="phd" <?php echo $course['level'] === 'phd' ? 'selected' : ''; ?>>PhD</option>
                </select>
                <?php if (!empty($_SESSION['errors']['level'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['level']); ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label>Duration *</label>
                <input type="text" name="duration" value="<?php echo e($course['duration']); ?>">
                <?php if (!empty($_SESSION['errors']['duration'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['duration']); ?></div><?php endif; ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Tuition Fee</label>
                <input type="number" name="tuition_fee" step="0.01" value="<?php echo e($course['tuition_fee'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Currency</label>
                <select name="currency">
                    <option value="USD" <?php echo ($course['currency'] ?? 'USD') === 'USD' ? 'selected' : ''; ?>>USD</option>
                    <option value="GBP" <?php echo ($course['currency'] ?? '') === 'GBP' ? 'selected' : ''; ?>>GBP</option>
                    <option value="AUD" <?php echo ($course['currency'] ?? '') === 'AUD' ? 'selected' : ''; ?>>AUD</option>
                    <option value="CAD" <?php echo ($course['currency'] ?? '') === 'CAD' ? 'selected' : ''; ?>>CAD</option>
                    <option value="EUR" <?php echo ($course['currency'] ?? '') === 'EUR' ? 'selected' : ''; ?>>EUR</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Requirements</label>
            <textarea name="requirements"><?php echo e($course['requirements'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description"><?php echo e($course['description'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="active" <?php echo $course['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="review" <?php echo $course['status'] === 'review' ? 'selected' : ''; ?>>Review</option>
                <option value="inactive" <?php echo $course['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Course</button>
            <a href="<?php echo url('/admin/catalog'); ?>" class="btn-secondary">Cancel</a>
        </div>
        <?php unset($_SESSION['errors'], $_SESSION['old']); ?>
    </form>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';
