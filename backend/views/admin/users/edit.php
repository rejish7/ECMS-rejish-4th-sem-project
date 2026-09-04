<?php ob_start(); ?>
<style>
    .form-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; max-width: 720px; }
    .form-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 500; color: #43474f; margin-bottom: 6px; }
    .form-group input, .form-group select { width: 100%; height: 40px; padding: 0 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; background: #f9fafb; }
    .form-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-primary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .btn-secondary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 14px; font-weight: 500; text-decoration: none; }
</style>
<div class="form-card">
    <h3>Edit User</h3>
    <form method="POST" action="<?php echo url('/admin/users/' . $user['id'] . '/update'); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group"><label>Name</label><input type="text" name="name" value="<?php echo e($user['name']); ?>"><?php if (!empty($_SESSION['errors']['name'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['name']); ?></div><?php endif; ?></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" value="<?php echo e($user['email']); ?>"><?php if (!empty($_SESSION['errors']['email'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['email']); ?></div><?php endif; ?></div>
        <div class="form-group"><label>Password (leave blank to keep current)</label><input type="password" name="password"></div>
        <div class="form-group"><label>Role</label><select name="role"><option value="student" <?php echo ($user['role'] ?? '') === 'student' ? 'selected' : ''; ?>>Student</option><option value="counselor" <?php echo ($user['role'] ?? '') === 'counselor' ? 'selected' : ''; ?>>Counselor</option><option value="admin" <?php echo ($user['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Administrator</option></select></div>
        <div class="form-group"><label>Status</label><select name="status"><option value="active" <?php echo ($user['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option><option value="inactive" <?php echo ($user['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option></select></div>
        <div class="form-actions"><button type="submit" class="btn-primary">Update User</button><a href="<?php echo url('/admin/users'); ?>" class="btn-secondary">Cancel</a></div>
        <?php unset($_SESSION['errors'], $_SESSION['old']); ?>
    </form>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';