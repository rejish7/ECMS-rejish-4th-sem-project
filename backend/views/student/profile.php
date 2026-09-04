<?php
$pageTitle = $pageTitle ?? 'My Profile';
$pageDescription = $pageDescription ?? 'View and update your profile.';
$currentPage = $currentPage ?? 'profile';
$assetPath = url('/frontend/assets');
$student = $student ?? null;
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
.form{padding:20px 24px;display:flex;flex-direction:column;gap:16px}
.form-group label{display:block;font-size:13px;font-weight:500;color:#43474f;margin-bottom:6px}
.form-group input,.form-group select{width:100%;height:40px;padding:0 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;color:#101828;background:#fff;box-sizing:border-box}
.form-group input:focus,.form-group select:focus{outline:none;border-color:#0054cb;box-shadow:0 0 0 3px rgba(0,84,203,0.1)}
.form-group input[readonly]{background:#f9fafb;color:#9ca3af}
.btn{display:inline-flex;align-items:center;justify-content:center;height:40px;padding:0 20px;border-radius:8px;background:#0054cb;color:#fff;font-size:14px;font-weight:500;border:none;cursor:pointer}
.btn:hover{background:#004aaf}
</style>
<div class="page-header">
    <h2>My Profile</h2>
    <p>Update your personal information.</p>
</div>
<div class="cards">
    <div class="card">
        <div class="card__header"><h3>Profile Picture</h3></div>
        <form class="form" method="POST" action="<?php echo url('/student/profile/avatar'); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div style="display:flex;align-items:center;gap:20px;">
                <div class="profile-avatar-wrap" onclick="document.getElementById('avatarInput').click();">
                    <?php
                    $avatarPath = $student['avatar'] ?? '';
                    $avatarUrl = !empty($avatarPath) ? e($avatarPath) : e(url('/frontend/assets/images/user-management/admin-avatar.jpg'));
                    ?>
                    <img src="<?php echo $avatarUrl; ?>" alt="Profile picture" id="avatarPreview">
                    <div class="avatar-edit-overlay">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    </div>
                </div>
                <div>
                    <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display:none;" onchange="previewAvatar(this);">
                    <p style="font-size:13px;color:#73777f;margin:0 0 8px;">Click the image to select a new photo.</p>
                    <p style="font-size:12px;color:#9ca3af;margin:0;">JPG, PNG, GIF, or WebP. Max 5 MB.</p>
                </div>
            </div>
            <div>
                <button type="submit" class="btn">Upload Photo</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card__header"><h3>Profile Details</h3></div>
        <form class="form" method="POST" action="<?php echo url('/student/profile/update'); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label>Student ID</label>
                <input type="text" value="<?php echo e($student['student_id'] ?? '-'); ?>" readonly>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" value="<?php echo e($student['email'] ?? '-'); ?>" readonly>
            </div>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" value="<?php echo e($student['name'] ?? ''); ?>">
                <?php if (!empty($_SESSION['errors']['name'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['name']); ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label>Education Level</label>
                <select name="education_level">
                    <option value="High School" <?php echo ($student['education_level'] ?? '') === 'High School' ? 'selected' : ''; ?>>High School</option>
                    <option value="Undergraduate" <?php echo ($student['education_level'] ?? '') === 'Undergraduate' ? 'selected' : ''; ?>>Undergraduate</option>
                    <option value="Postgraduate" <?php echo ($student['education_level'] ?? '') === 'Postgraduate' ? 'selected' : ''; ?>>Postgraduate</option>
                </select>
                <?php if (!empty($_SESSION['errors']['education_level'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['education_level']); ?></div><?php endif; ?>
            </div>
            <div>
                <button type="submit" class="btn">Save Changes</button>
            </div>
            <?php unset($_SESSION['errors'], $_SESSION['old']); ?>
        </form>
    </div>

    <div class="card">
        <div class="card__header"><h3>Change Password</h3></div>
        <form class="form" method="POST" action="<?php echo url('/student/profile/password'); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password">
                <?php if (!empty($_SESSION['errors']['current_password'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['current_password']); ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" minlength="8">
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="password_confirm" minlength="8">
            </div>
            <div>
                <button type="submit" class="btn" onclick="if(this.form.password.value !== this.form.password_confirm.value){alert('Passwords do not match');return false;}">Update Password</button>
            </div>
            <?php unset($_SESSION['errors'], $_SESSION['old']); ?>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/student-layout.php';
?>
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { document.getElementById('avatarPreview').src = e.target.result; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
