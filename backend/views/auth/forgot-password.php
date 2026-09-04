<?php
$assetPath = url('/frontend/assets');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | ECMS</title>
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
        .login-form input[type="email"] { width: 100%; height: 44px; padding: 0 14px; border: 1px solid #d0d5dd; border-radius: 8px; font-size: 14px; color: #101828; background: #fff; transition: border-color 0.2s; }
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
                <h1>Forgot Your Password?</h1>
                <p>No worries. Enter your email address and we'll help you reset your password to get back into your account.</p>
            </div>
            <div class="login-illustration">
                <div style="width:100%;height:260px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);display:flex;align-items:center;justify-content:center;flex-direction:column;gap:12px;">
                    <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="32" cy="32" r="28" fill="#0054cb" fill-opacity="0.1" stroke="#0054cb" stroke-width="2"/>
                        <rect x="20" y="26" width="24" height="18" rx="3" stroke="#0054cb" stroke-width="2" fill="none"/>
                        <path d="M28 26V22a4 4 0 018 0v4" stroke="#0054cb" stroke-width="2" fill="none"/>
                        <circle cx="32" cy="35" r="2" fill="#0054cb"/>
                        <path d="M32 37v3" stroke="#0054cb" stroke-width="2" stroke-linecap="round"/>
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
            <p class="login-subtitle">Enter your email and we'll send you a reset link.</p>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="flash-error"><?php echo e($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="flash-success"><?php echo e($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <form class="login-form" method="POST" action="<?php echo url('/forgot-password'); ?>" novalidate>
                <?php echo csrf_field(); ?>
                <div class="form-group" id="email-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your registered email" value="<?php echo e(old('email')); ?>" autofocus>
                    <div class="form-error" id="email-error"></div>
                </div>

                <button type="submit" class="btn-signin">Send Reset Link</button>
            </form>

            <div class="login-footer">
                <a href="<?php echo url('/login'); ?>">&#8592; Back to Login</a>
            </div>
        </div>
    </div>

    <script>
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
            form.addEventListener('submit', function (e) {
                const email = document.getElementById('email').value.trim();
                let valid = true;

                if (email === '') {
                    setError('email', 'Email address is required.');
                    valid = false;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    setError('email', 'Please enter a valid email address.');
                    valid = false;
                } else {
                    setError('email', '');
                }

                if (!valid) e.preventDefault();
            });

            document.getElementById('email').addEventListener('input', function () {
                const group = document.getElementById('email-group');
                if (group.classList.contains('has-error')) {
                    const email = this.value.trim();
                    if (email === '') setError('email', 'Email address is required.');
                    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) setError('email', 'Please enter a valid email address.');
                    else setError('email', '');
                }
            });
        });
    </script>
</body>
</html>
