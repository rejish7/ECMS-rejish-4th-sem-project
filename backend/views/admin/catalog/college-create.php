<?php ob_start(); ?>
<style>
    .form-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; max-width: 720px; }
    .form-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 500; color: #43474f; margin-bottom: 6px; }
    .form-group input, .form-group select { width: 100%; height: 40px; padding: 0 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; background: #f9fafb; box-sizing: border-box; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-primary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .btn-primary:hover { background: #004aaf; }
    .btn-secondary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 14px; font-weight: 500; text-decoration: none; }
    .btn-secondary:hover { background: #f9fafb; }
</style>
<div class="form-card">
    <h3>Add College</h3>
    <form method="POST" action="<?php echo url('/admin/catalog/college/store'); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-row">
            <div class="form-group">
                <label>College Name *</label>
                <input type="text" name="name" placeholder="e.g., London University">
                <?php if (!empty($_SESSION['errors']['name'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['name']); ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label>Code *</label>
                <input type="text" name="code" placeholder="e.g., LU" maxlength="20">
                <?php if (!empty($_SESSION['errors']['code'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['code']); ?></div><?php endif; ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Country *</label>
                <input type="text" name="country" placeholder="e.g., United Kingdom">
                <?php if (!empty($_SESSION['errors']['country'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['country']); ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" placeholder="e.g., London">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Website</label>
                <input type="url" name="website" placeholder="https://example.edu">
            </div>
            <div class="form-group">
                <label>Contact Email</label>
                <input type="email" name="contact_email" placeholder="admissions@example.edu">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Contact Phone</label>
                <input type="text" name="contact_phone" placeholder="+44 20 1234 5678">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">Add College</button>
            <a href="<?php echo url('/admin/catalog'); ?>" class="btn-secondary">Cancel</a>
        </div>
        <?php unset($_SESSION['errors'], $_SESSION['old']); ?>
    </form>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';
