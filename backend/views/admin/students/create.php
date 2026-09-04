<?php
ob_start();
?>
<style>
    .form-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); max-width: 720px; }
    .form-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 500; color: #43474f; margin-bottom: 6px; }
    .form-group input, .form-group select { width: 100%; height: 40px; padding: 0 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; color: #0b1c30; background: #f9fafb; box-sizing: border-box; }
    .form-group input:focus, .form-group select:focus { outline: none; border-color: #0054cb; background: #fff; }
    .form-group .hint { font-size: 12px; color: #73777f; margin-top: 6px; line-height: 1.5; }
    .pw-field { display: flex; gap: 8px; }
    .pw-field input { flex: 1; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; letter-spacing: 0.5px; }
    .pw-field input[readonly] { background: #f9fafb; color: #0b1c30; cursor: default; }
    .pw-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 40px; padding: 0 16px; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; font-size: 14px; font-weight: 500; color: #43474f; cursor: pointer; white-space: nowrap; }
    .pw-btn:hover { background: #f9fafb; border-color: #d1d5db; }
    .pw-btn svg { width: 16px; height: 16px; }
    .form-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-primary { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .btn-primary:hover { background: #004aaf; }
    .btn-secondary { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 24px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; }
</style>

<div class="form-card">
    <h3>Register New Student</h3>
    <form method="POST" action="<?php echo url('/admin/students/store'); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label for="student_id">Student ID</label>
            <div class="pw-field">
                <input type="text" id="student_id" name="student_id" readonly>
                <button type="button" class="pw-btn" id="generateIdBtn" title="Generate a new Student ID">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2v6h-6"/><path d="M3 10V4h6"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L3 10"/></svg>
                    Generate
                </button>
            </div>
            <div class="hint">Auto-generated unique student identifier.</div>
            <?php if (!empty($_SESSION['errors']['student_id'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['student_id']); ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name">
            <?php if (!empty($_SESSION['errors']['name'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['name']); ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email">
            <?php if (!empty($_SESSION['errors']['email'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['email']); ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label for="education_level">Education Level</label>
            <select id="education_level" name="education_level">
                <option value="">Select level</option>
                <option value="High School">High School</option>
                <option value="Undergraduate">Undergraduate</option>
                <option value="Postgraduate">Postgraduate</option>
            </select>
            <?php if (!empty($_SESSION['errors']['education_level'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['education_level']); ?></div><?php endif; ?>
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
        <div class="form-group">
            <label for="password">Login Password</label>
            <div class="pw-field">
                <input type="text" id="password" name="password" readonly autocomplete="off">
                <button type="button" class="pw-btn" id="generatePwBtn" title="Generate a new password">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2v6h-6"/><path d="M3 10V4h6"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L3 10"/></svg>
                    Generate
                </button>
                <button type="button" class="pw-btn" id="copyBtn" title="Copy password">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    Copy
                </button>
            </div>
            <div class="hint">Click "Generate" to create a secure password. It will be emailed to the student, and they can change it from their dashboard.</div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">Register Student</button>
            <a href="<?php echo url('/admin/students'); ?>" class="btn-secondary">Cancel</a>
        </div>
        <?php unset($_SESSION['errors'], $_SESSION['old']); ?>
    </form>
</div>

<script>
(function () {
    // Student ID generator
    function genStudentId() {
        var d = new Date();
        var datePart = d.getFullYear().toString() + String(d.getMonth() + 1).padStart(2, '0') + String(d.getDate()).padStart(2, '0');
        var num = Math.floor(Math.random() * 9999) + 1;
        return 'STU-' + datePart + '-' + String(num).padStart(4, '0');
    }
    var idInput = document.getElementById('student_id');
    var genIdBtn = document.getElementById('generateIdBtn');
    function setId() { idInput.value = genStudentId(); }
    setId();
    genIdBtn.addEventListener('click', setId);

    // Password generator
    var charset = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
    function genPassword(len) {
        len = len || 14;
        var arr = new Uint32Array(len);
        crypto.getRandomValues(arr);
        var out = '';
        for (var i = 0; i < len; i++) {
            out += charset[arr[i] % charset.length];
        }
        return out;
    }
    var pwInput = document.getElementById('password');
    var genPwBtn = document.getElementById('generatePwBtn');
    var copy = document.getElementById('copyBtn');
    genPwBtn.addEventListener('click', function () {
        pwInput.value = genPassword();
    });
    copy.addEventListener('click', function () {
        if (!pwInput.value) { alert('Generate a password first.'); return; }
        pwInput.select();
        pwInput.setSelectionRange(0, 99999);
        try { navigator.clipboard.writeText(pwInput.value); } catch (e) {}
        var old = copy.textContent;
        copy.textContent = 'Copied!';
        setTimeout(function () { copy.textContent = old; }, 1200);
    });
})();
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin-layout.php';