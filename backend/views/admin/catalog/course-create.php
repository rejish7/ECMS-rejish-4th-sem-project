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
    <h3>Add Course</h3>
    <form method="POST" action="<?php echo url('/admin/catalog/course/store'); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label>College *</label>
            <select name="college_id" required>
                <option value="">Select College</option>
                <?php if (!empty($colleges)): ?>
                    <?php foreach ($colleges as $college): ?>
                        <option value="<?php echo e($college['id']); ?>"><?php echo e($college['name'] . ' (' . $college['country'] . ')'); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Course Name *</label>
                <input type="text" name="name" required placeholder="e.g., BSc Computer Science">
            </div>
            <div class="form-group">
                <label>Course Code *</label>
                <input type="text" name="code" required placeholder="e.g., CS-101" maxlength="20">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Level *</label>
                <select name="level" required>
                    <option value="">Select Level</option>
                    <option value="bachelor">Bachelor</option>
                    <option value="master">Master</option>
                    <option value="diploma">Diploma</option>
                    <option value="phd">PhD</option>
                </select>
            </div>
            <div class="form-group">
                <label>Duration *</label>
                <input type="text" name="duration" required placeholder="e.g., 3 Years">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Tuition Fee</label>
                <input type="number" name="tuition_fee" step="0.01" placeholder="e.g., 25000">
            </div>
            <div class="form-group">
                <label>Currency</label>
                <select name="currency">
                    <option value="USD">USD</option>
                    <option value="GBP">GBP</option>
                    <option value="AUD">AUD</option>
                    <option value="CAD">CAD</option>
                    <option value="EUR">EUR</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Requirements</label>
            <textarea name="requirements" placeholder="e.g., IELTS 6.5, A-Levels (ABB)"></textarea>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" placeholder="Course description..."></textarea>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="active">Active</option>
                <option value="review">Review</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">Add Course</button>
            <a href="<?php echo url('/admin/catalog'); ?>" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';
