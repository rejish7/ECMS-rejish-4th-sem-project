<?php
$pageTitle = $pageTitle ?? 'My Profile';
$pageDescription = $pageDescription ?? 'View and update your profile.';
$currentPage = $currentPage ?? 'profile';
$assetPath = url('/frontend/assets');
$counselor = $counselor ?? null;
ob_start();
?>
<style>
.page-header{margin-bottom:24px}
.page-header h2{margin:0;color:#0b1c30;font-size:32px;font-weight:700;letter-spacing:-0.64px}
.page-header p{margin:4px 0 0;color:#73777f;font-size:14px}
.cards{display:flex;flex-direction:column;gap:24px;max-width:640px}
.card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 1px 2px rgba(0,0,0,0.04);overflow:hidden}
.card__header{padding:20px 24px 0}
.card__header h3{margin:0;font-size:18px;font-weight:700;color:#0b1c30}
.cprofile{padding:20px 24px;display:flex;flex-direction:column;gap:12px}
.cprofile-top{display:flex;align-items:center;gap:14px;padding-bottom:16px}
.cprofile-avatar{width:56px;height:56px;border-radius:9999px;background:#e0edff;color:#0054cb;display:inline-flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;flex:0 0 auto}
.cprofile-name{font-size:17px;font-weight:700;color:#0b1c30}
.cprofile-role{font-size:13px;color:#73777f;margin-top:2px}
.cprofile-row{display:flex;justify-content:space-between;font-size:14px;padding:8px 0;border-bottom:1px solid #f3f4f6}
.cprofile-row:last-child{border-bottom:none}
.cprofile-row span:first-child{color:#73777f}
.cprofile-row span:last-child{color:#0b1c30;font-weight:500}
.form{padding:20px 24px;display:flex;flex-direction:column;gap:16px}
.form-group label{display:block;font-size:13px;font-weight:500;color:#43474f;margin-bottom:6px}
.form-group input{width:100%;height:40px;padding:0 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;color:#101828;background:#fff;box-sizing:border-box}
.form-group input:focus{outline:none;border-color:#0054cb;box-shadow:0 0 0 3px rgba(0,84,203,0.1)}
.btn{display:inline-flex;align-items:center;justify-content:center;height:40px;padding:0 20px;border-radius:8px;background:#0054cb;color:#fff;font-size:14px;font-weight:500;border:none;cursor:pointer}
.btn:hover{background:#004aaf}
</style>
<div class="page-header">
    <h2>My Profile</h2>
    <p>View your details and update your password.</p>
</div>
<div class="cards">
    <div class="card">
        <div class="card__header"><h3>Profile Details</h3></div>
        <div class="cprofile">
            <div class="cprofile-top">
                <span class="cprofile-avatar"><?php echo e(strtoupper(substr($counselor['name'] ?? 'C', 0, 2))); ?></span>
                <div>
                    <div class="cprofile-name"><?php echo e($counselor['name'] ?? '-'); ?></div>
                    <div class="cprofile-role"><?php echo e($counselor['specialization'] ?? 'Counselor'); ?></div>
                </div>
            </div>
            <div class="cprofile-row"><span>Email</span><span><?php echo e($counselor['email'] ?? '-'); ?></span></div>
            <div class="cprofile-row"><span>Specialization</span><span><?php echo e($counselor['specialization'] ?? '-'); ?></span></div>
            <div class="cprofile-row"><span>Max Students</span><span><?php echo e($counselor['max_students'] ?? '-'); ?></span></div>
            <div class="cprofile-row"><span>Status</span><span><?php echo e(ucfirst($counselor['status'] ?? 'available')); ?></span></div>
        </div>
    </div>

    <div class="card">
        <div class="card__header"><h3>Change Password</h3></div>
        <form class="form" method="POST" action="<?php echo url('/counselor/profile/password'); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" required>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" minlength="8" required>
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="password_confirm" minlength="8" required>
            </div>
            <div>
                <button type="submit" class="btn" onclick="if(this.form.password.value !== this.form.password_confirm.value){alert('Passwords do not match');return false;}">Update Password</button>
            </div>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/counselor-layout.php';