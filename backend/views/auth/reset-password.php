<?php
$assetPath = url('/frontend/assets');
$token = $_GET['token'] ?? $_SESSION['reset_token'] ?? '';
unset($_SESSION['reset_token']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | ECMS</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f0f4ff; padding: 24px; }
        .login-shell { display: flex; width: 100%; max-width: 1080px; min-height: 640px; border-radius: 20px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.08); background: #fff; }
        .login-left { flex: 1.1; background: linear-gradient(135deg, #e8eeff 0%, #d4ddff 100%); padding: 56px 48px; display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden; }
        .login-left h1 { font-size: 40px; font-weight: 800; color: #0044cb; line-height: 1.15; margin-bottom: 16px; }
        .login-left p { font-size: 15px; color: #43474f; line-height: 1.6; max-width: 380px; }
        .login-illustration { margin-top: 32px; border-radius: 12px; overflow: hidden; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        .login-right { flex: 1; padding: 56px 48px; display: flex; flex-direction: column; justify-content: center; }
        .login-logo { text-align: center; margin-bottom: 24px; }
        .login-logo-icon { display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; border: 2px solid #0044cb; border-radius: 8px; margin-bottom: 4px; }
        .login-logo-icon svg { width: 28px; height: 28px; color: #0044cb; }
        .login-logo-text { font-size: 11px; font-weight: 700; color: #0044cb; letter-spacing: 1px; }
        .login-subtitle { text-align: center; font-size: 14px; color: #73777f; margin-bottom: 32px; }
        .login-form .form-group { margin-bottom: 16px; }
        .login-form label { display: block; font-size: 14px; font-weight: 500; color: #344054; margin-bottom: 6px; }
        .login-form input[type="password"] { width: 100%; height: 44px; padding: 0 14px; border: 1px solid #d0d5dd; border-radius: 8px; font-size: 14px; color: #101828; background: #fff; transition: border-color 0.2s; }
        .login-form input:focus { outline: none; border-color: #0054cb; box-shadow: 0 0 0 3px rgba(0,84,203,0.1); }
        .login-form input::placeholder { color: #9ca3af; }
        .form-error { display: none; font-size: 12px; color: #dc2626; margin-top: 6px; }
        .has-error input { border-color: #dc2626; }
        .has-error input:focus { box-shadow: 0 0 0 3px rgba(220,38,38,0.1); }
        .has-error .form-error { display: block; }
        .btn-signin { width: 100%; height: 44px; border: none; border-radius: 8px; background: #0054cb; color: #fff; font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-signin:hover { background: #0044a8; }
        .login-footer { text-align: center; margin-top: 24px; font-size: 14px; color: #73777f; }
        .login-footer a { color: #0054cb; text-decoration: none; font-weight: 500; }
        .login-footer a:hover { text-decoration: underline; }
        .flash-error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }
        .flash-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }
        .password-wrap { position: relative; }
        .password-wrap input { padding-right: 44px; }
        .password-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9ca3af; padding: 4px; }
        .password-toggle:hover { color: #6b7280; }
        .password-toggle svg { width: 18px; height: 18px; }
        @media (max-width: 768px) {
            .login-shell { flex-direction: column; }
            .login-left { padding: 32px 24px; display: none; }
            .login-right { padding: 32px 24px; }
            .login-left h1 { font-size: 28px; }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="login-left">
            <div>
                <h1>Set New Password</h1>
                <p>Create a strong new password for your account. Make sure it's something memorable but hard for others to guess.</p>
            </div>
            <div class="login-illustration">
                <div style="width:100%;height:260px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);display:flex;align-items:center;justify-content:center;flex-direction:column;gap:12px;">
                    <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="32" cy="32" r="28" fill="#0054cb" fill-opacity="0.1" stroke="#0054cb" stroke-width="2"/>
                        <path d="M30 22l-8 8 8 8" stroke="#0054cb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M22 30h16a4 4 0 014 4v4a4 4 0 01-4 4H22" stroke="#0054cb" stroke-width="2" fill="none"/>
                    </svg>
                    <span style="font-size:13px;font-weight:600;color:#0054cb;letter-spacing:1px;">ECMS</span>
                </div>
            </div>
        </div>

        <div class="login-right">
            <div class="login-logo">
                <div class="login-logo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        <path d="M12 14l9-5-9-5-9 5 9 5zM12 14v7m0 0l-3-3m3 3l3-3"/>
                    </svg>
                </div>
                <div class="login-logo-text">ECMS</div>
            </div>
            <p class="login-subtitle">Create your new password below.</p>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="flash-error"><?php echo e($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="flash-success"><?php echo e($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <?php if (empty($token)): ?>
                <div class="flash-error">Invalid or missing reset token. <a href="<?php echo url('/forgot-password'); ?>" style="color:#dc2626;font-weight:600;">Request a new one</a></div>
            <?php else: ?>
                <form class="login-form" method="POST" action="<?php echo url('/reset-password'); ?>" novalidate>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="token" value="<?php echo e($token); ?>">

                    <div class="form-group" id="password-group">
                        <label for="password">New Password</label>
                        <div class="password-wrap">
                            <input type="password" id="password" name="password" placeholder="Enter new password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password', 'eye-off-1', 'eye-on-1')" aria-label="Toggle password visibility">
                                <svg id="eye-off-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                                <svg id="eye-on-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                        <div class="form-error" id="password-error"></div>
                    </div>

                    <div class="form-group" id="password_confirmation-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="password-wrap">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', 'eye-off-2', 'eye-on-2')" aria-label="Toggle password visibility">
                                <svg id="eye-off-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                                <svg id="eye-on-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                        <div class="form-error" id="password_confirmation-error"></div>
                    </div>

                    <button type="submit" class="btn-signin">Reset Password</button>
                </form>
            <?php endif; ?>

            <div class="login-footer">
                <a href="<?php echo url('/login'); ?>">&#8592; Back to Login</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, offId, onId) {
            const pw = document.getElementById(inputId);
            const eyeOff = document.getElementById(offId);
            const eyeOn = document.getElementById(onId);
            if (pw.type === 'password') {
                pw.type = 'text';
                eyeOff.style.display = 'none';
                eyeOn.style.display = 'block';
            } else {
                pw.type = 'password';
                eyeOff.style.display = 'block';
                eyeOn.style.display = 'none';
            }
        }

        function setError(id, message) {
            const group = document.getElementById(id + '-group');
            const input = document.getElementById(id);
            const error = document.getElementById(id + '-error');
            if (message) {
                error.textContent = message;
                group.classList.add('has-error');
                input.classList.add('has-error');
            } else {
                error.textContent = '';
                group.classList.remove('has-error');
                input.classList.remove('has-error');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('.login-form');
            if (!form) return;

            form.addEventListener('submit', function (e) {
                const password = document.getElementById('password').value;
                const confirm = document.getElementById('password_confirmation').value;
                let valid = true;

                if (password === '') {
                    setError('password', 'New password is required.');
                    valid = false;
                } else if (password.length < 6) {
                    setError('password', 'Password must be at least 6 characters.');
                    valid = false;
                } else {
                    setError('password', '');
                }

                if (confirm === '') {
                    setError('password_confirmation', 'Please confirm your password.');
                    valid = false;
                } else if (password !== confirm) {
                    setError('password_confirmation', 'Passwords do not match.');
                    valid = false;
                } else {
                    setError('password_confirmation', '');
                }

                if (!valid) e.preventDefault();
            });

            ['password', 'password_confirmation'].forEach(function (id) {
                document.getElementById(id).addEventListener('input', function () {
                    const group = document.getElementById(id + '-group');
                    if (group.classList.contains('has-error')) {
                        const password = document.getElementById('password').value;
                        const confirm = document.getElementById('password_confirmation').value;
                        if (id === 'password') {
                            if (password === '') setError('password', 'New password is required.');
                            else if (password.length < 6) setError('password', 'Password must be at least 6 characters.');
                            else setError('password', '');
                        } else {
                            if (confirm === '') setError('password_confirmation', 'Please confirm your password.');
                            else if (password !== confirm) setError('password_confirmation', 'Passwords do not match.');
                            else setError('password_confirmation', '');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
