<?php
ob_start();
?>
<style>
    .form-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); max-width: 720px; }
    .form-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 500; color: #43474f; margin-bottom: 6px; }
    .form-group input, .form-group select { width: 100%; height: 40px; padding: 0 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; color: #0b1c30; background: #f9fafb; }
    .form-group input:focus, .form-group select:focus { outline: none; border-color: #0054cb; background: #fff; }
    .form-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-primary { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .btn-primary:hover { background: #004aaf; }
    .btn-secondary { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 24px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; }
</style>

<div class="form-card">
    <h3>Register New Student</h3>
    <form method="POST" action="<?php echo url('/admin/students/store'); ?>">
        <div class="form-group">
            <label for="student_id">Student ID</label>
            <input type="text" id="student_id" name="student_id" required>
        </div>
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="education_level">Education Level</label>
            <select id="education_level" name="education_level" required>
                <option value="">Select level</option>
                <option value="High School">High School</option>
                <option value="Undergraduate">Undergraduate</option>
                <option value="Postgraduate">Postgraduate</option>
            </select>
        </div>
        <div class="form-group">
            <label for="counselor_id">Assigned Counselor</label>
            <select id="counselor_id" name="counselor_id">
                <option value="">-- Unassigned --</option>
                <?php foreach ($counselors as $c): ?>
                    <option value="<?php echo e($c['id']); ?>"><?php echo e($c['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">Register Student</button>
            <a href="<?php echo url('/admin/students'); ?>" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin-layout.php';