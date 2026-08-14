<?php ob_start(); ?>
<style>
    .form-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; max-width: 720px; }
    .form-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 500; color: #43474f; margin-bottom: 6px; }
    .form-group input, .form-group select { width: 100%; height: 40px; padding: 0 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; background: #f9fafb; box-sizing: border-box; }
    .form-group .hint { font-size: 12px; color: #73777f; margin-top: 4px; }
    .form-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-primary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .btn-primary:hover { background: #004aaf; }
    .btn-secondary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 14px; font-weight: 500; text-decoration: none; }
    .btn-secondary:hover { background: #f9fafb; }
</style>
<div class="form-card">
    <h3>Assign Required Document</h3>
    <form method="POST" action="<?php echo url('/counselor/documents/assign/store'); ?>">
        <div class="form-group">
            <label>Student</label>
            <select name="student_id" required>
                <option value="">Select Student</option>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo e($student['id']); ?>" <?php echo $preselectedStudent === (int)$student['id'] ? 'selected' : ''; ?>><?php echo e($student['name'] . ' (' . ($student['student_id'] ?? 'N/A') . ')'); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Document Name</label>
            <input type="text" name="name" required placeholder="e.g., 10th Marksheet, Passport Copy, Bank Statement">
        </div>
        <div class="form-group">
            <label>Category</label>
            <select name="category" required>
                <option value="education">Education Documents</option>
                <option value="visa">Visa Documents</option>
            </select>
            <div class="hint">The student will see this as a required document and submit the file from their dashboard.</div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">Assign</button>
            <a href="<?php echo url('/counselor/documents'); ?>" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../layouts/counselor-layout.php';