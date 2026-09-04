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
    <h3>Edit Student</h3>
    <form method="POST" action="<?php echo url('/admin/students/' . $student['id'] . '/update'); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label for="student_id">Student ID</label>
            <input type="text" id="student_id" name="student_id" value="<?php echo e($student['student_id']); ?>">
            <?php if (!empty($_SESSION['errors']['student_id'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['student_id']); ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?php echo e($student['name']); ?>">
            <?php if (!empty($_SESSION['errors']['name'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['name']); ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo e($student['email']); ?>">
            <?php if (!empty($_SESSION['errors']['email'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['email']); ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label for="education_level">Education Level</label>
            <select id="education_level" name="education_level">
                <option value="High School" <?php echo ($student['education_level'] ?? '') === 'High School' ? 'selected' : ''; ?>>High School</option>
                <option value="Undergraduate" <?php echo ($student['education_level'] ?? '') === 'Undergraduate' ? 'selected' : ''; ?>>Undergraduate</option>
                <option value="Postgraduate" <?php echo ($student['education_level'] ?? '') === 'Postgraduate' ? 'selected' : ''; ?>>Postgraduate</option>
            </select>
            <?php if (!empty($_SESSION['errors']['education_level'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['education_level']); ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label for="counselor_id">Assigned Counselor</label>
            <select id="counselor_id" name="counselor_id">
                <option value="">-- Unassigned --</option>
                <?php
                $currentCounselorId = $student['counselor_id'] ?? null;
                $counselorIds = array_column($counselors, 'id');
                foreach ($counselors as $c):
                ?>
                    <option value="<?php echo e($c['id']); ?>" <?php echo $currentCounselorId == $c['id'] ? 'selected' : ''; ?>><?php echo e($c['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Student</button>
            <a href="<?php echo url('/admin/students'); ?>" class="btn-secondary">Cancel</a>
        </div>
        <?php unset($_SESSION['errors'], $_SESSION['old']); ?>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin-layout.php';