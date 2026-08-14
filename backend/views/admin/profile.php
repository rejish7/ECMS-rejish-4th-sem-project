<?php ob_start(); ?>
<style>
    .profile-page { display: flex; flex-direction: column; gap: 24px; max-width: 720px; }
    .profile-header { display: flex; align-items: center; gap: 20px; }
    .profile-avatar { width: 80px; height: 80px; border-radius: 50%; background: #0054cb; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 700; }
    .profile-header-text h2 { margin: 0; font-size: 24px; font-weight: 700; color: #0b1c30; }
    .profile-header-text p { margin: 4px 0 0; color: #73777f; font-size: 14px; }
    .profile-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; }
    .profile-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .profile-row { display: flex; padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
    .profile-row:last-child { border-bottom: none; }
    .profile-label { width: 180px; font-size: 14px; font-weight: 500; color: #73777f; }
    .profile-value { font-size: 14px; color: #0b1c30; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 500; color: #43474f; margin-bottom: 6px; }
    .form-group input { width: 100%; height: 40px; padding: 0 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; background: #f9fafb; }
    .form-group input:focus { outline: none; border-color: #0054cb; background: #fff; }
    .form-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-primary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .btn-primary:hover { background: #004aaf; }
</style>
<div class="profile-page">
    <div class="profile-header">
        <div class="profile-avatar"><?php echo e(substr($user['name'], 0, 1)); ?></div>
        <div class="profile-header-text">
            <h2><?php echo e($user['name']); ?></h2>
            <p><?php echo e($user['role'] ?? 'admin'); ?></p>
        </div>
    </div>
    <div class="profile-card">
        <h3>Profile Information</h3>
        <div class="profile-row"><span class="profile-label">Name</span><span class="profile-value"><?php echo e($user['name']); ?></span></div>
        <div class="profile-row"><span class="profile-label">Email</span><span class="profile-value"><?php echo e($user['email']); ?></span></div>
        <div class="profile-row"><span class="profile-label">Role</span><span class="profile-value"><?php echo e($user['role'] ?? 'admin'); ?></span></div>
        <div class="profile-row"><span class="profile-label">Status</span><span class="profile-value"><?php echo e($user['status'] ?? 'active'); ?></span></div>
    </div>
    <div class="profile-card">
        <h3>Change Password</h3>
        <form method="POST" action="<?php echo url('/admin/profile/update'); ?>">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" minlength="6">
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirm" minlength="6">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary" onclick="if(this.form.password.value !== this.form.password_confirm.value){alert('Passwords do not match');return false;}">Update Password</button>
            </div>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin-layout.php';
