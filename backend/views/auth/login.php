<?php
$assetPath = url('/frontend/assets');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | ECMS</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f0f4ff; padding: 24px; }
        .login-shell { display: flex; width: 100%; max-width: 1080px; min-height: 640px; border-radius: 20px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.08); background: #fff; }

        /* Left Panel */
        .login-left { flex: 1.1; background: linear-gradient(135deg, #e8eeff 0%, #d4ddff 100%); padding: 56px 48px; display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden; }
        .login-left h1 { font-size: 40px; font-weight: 800; color: #0044cb; line-height: 1.15; margin-bottom: 16px; }
        .login-left p { font-size: 15px; color: #43474f; line-height: 1.6; max-width: 380px; }
        .login-illustration { margin-top: 32px; border-radius: 12px; overflow: hidden; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        .login-illustration img { width: 100%; display: block; }

        /* Right Panel */
        .login-right { flex: 1; padding: 56px 48px; display: flex; flex-direction: column; justify-content: center; }
        .login-logo { text-align: center; margin-bottom: 24px; }
        .login-logo-icon { display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; border: 2px solid #0044cb; border-radius: 8px; margin-bottom: 4px; }
        .login-logo-icon svg { width: 28px; height: 28px; color: #0044cb; }
        .login-logo-text { font-size: 11px; font-weight: 700; color: #0044cb; letter-spacing: 1px; }
        .login-subtitle { text-align: center; font-size: 14px; color: #73777f; margin-bottom: 32px; }

        .login-form .form-group { margin-bottom: 16px; }
        .login-form label { display: block; font-size: 14px; font-weight: 500; color: #344054; margin-bottom: 6px; }
        .login-form input[type="email"],
        .login-form input[type="password"],
        .login-form input[type="text"] { width: 100%; height: 44px; padding: 0 14px; border: 1px solid #d0d5dd; border-radius: 8px; font-size: 14px; color: #101828; background: #fff; transition: border-color 0.2s; }
        .login-form input:focus { outline: none; border-color: #0054cb; box-shadow: 0 0 0 3px rgba(0,84,203,0.1); }
        .login-form input::placeholder { color: #9ca3af; }
        .login-form .field-error { display: none; font-size: 12px; color: #dc2626; margin-top: 6px; }
        .login-form .has-error input { border-color: #dc2626; }
        .login-form .has-error input:focus { box-shadow: 0 0 0 3px rgba(220,38,38,0.1); }
        .login-form .has-error .field-error { display: block; }

        .password-wrap { position: relative; }
        .password-wrap input { padding-right: 44px; }
        .password-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9ca3af; padding: 4px; }
        .password-toggle:hover { color: #6b7280; }
        .password-toggle svg { width: 18px; height: 18px; }

        .login-options { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
        .remember-check { display: flex; align-items: center; gap: 8px; font-size: 14px; color: #344054; cursor: pointer; }
        .remember-check input[type="checkbox"] { width: 16px; height: 16px; border-radius: 4px; border: 1px solid #d0d5dd; accent-color: #0054cb; cursor: pointer; }
        .forgot-link { font-size: 14px; color: #0054cb; text-decoration: none; font-weight: 500; }
        .forgot-link:hover { text-decoration: underline; }

        .btn-signin { width: 100%; height: 44px; border: none; border-radius: 8px; background: #0054cb; color: #fff; font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-signin:hover { background: #0044a8; }

        .btn-google { width: 100%; height: 44px; border: 1px solid #d0d5dd; border-radius: 8px; background: #fff; color: #344054; font-size: 14px; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 12px; transition: background 0.2s; }
        .btn-google:hover { background: #f9fafb; }
        .btn-google svg { width: 18px; height: 18px; }

        .login-footer { text-align: center; margin-top: 24px; font-size: 14px; color: #73777f; }
        .login-footer a { color: #0054cb; text-decoration: none; font-weight: 500; }
        .login-footer a:hover { text-decoration: underline; }

        .flash-error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }

        @media (max-width: 768px) {
            .login-shell { flex-direction: column; }
            .login-left { padding: 32px 24px; }
            .login-right { padding: 32px 24px; }
            .login-left h1 { font-size: 28px; }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <!-- Left Panel -->
        <div class="login-left">
            <div>
                <h1>Global Education,<br>Simplified.</h1>
                <p>Empowering educational consultants with professional tools to manage applications, track progress, and guide students toward their international academic goals.</p>
            </div>
            <div class="login-illustration">
                <div style="width:100%;height:260px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);display:flex;align-items:center;justify-content:center;flex-direction:column;gap:12px;">
                    <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="32" cy="32" r="28" fill="#0054cb" fill-opacity="0.1" stroke="#0054cb" stroke-width="2"/>
                        <path d="M32 12C20.954 12 12 20.954 12 32s8.954 20 20 20 20-8.954 20-20S43.046 12 32 12zm0 36c-8.837 0-16-7.163-16-16S23.163 16 32 16s16 7.163 16 16-7.163 16-16 16z" fill="#0054cb" fill-opacity="0.3"/>
                        <path d="M32 20c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm0 20c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z" fill="#0054cb" fill-opacity="0.5"/>
                        <circle cx="32" cy="32" r="3" fill="#0054cb"/>
                    </svg>
                    <span style="font-size:13px;font-weight:600;color:#0054cb;letter-spacing:1px;">ECMS</span>
                </div>
            </div>
        </div>

        <!-- Right Panel -->
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
            <p class="login-subtitle">Welcome back. Please enter your details.</p>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="flash-error"><?php echo e($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['success'])): ?>
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;"><?php echo e($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <form class="login-form" method="POST" action="<?php echo url('/login'); ?>" novalidate>
                <?php echo csrf_field(); ?>
                <div class="form-group" id="email-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo e(old('email')); ?>" autofocus>
                    <div class="field-error" id="email-error"></div>
                </div>
                <div class="form-group" id="password-group">
                    <label for="password">Password</label>
                    <div class="password-wrap">
                        <input type="password" id="password" name="password" placeholder="Enter your password">
                        <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                            <svg id="eye-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                            <svg id="eye-on" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </button>
                    </div>
                    <div class="field-error" id="password-error"></div>
                </div>

                <div class="login-options">
                    <label class="remember-check">
                        <input type="checkbox" name="remember"> Remember for 30 days
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-signin">Sign in</button>
                <button type="button" class="btn-google">
                    <svg viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                    Sign in with Google
                </button>
            </form>

            <div class="login-footer">
                Don't have an account? <a href="<?php echo url('/register'); ?>">Request access</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pw = document.getElementById('password');
            const eyeOff = document.getElementById('eye-off');
            const eyeOn = document.getElementById('eye-on');
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

        function validateLoginForm() {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
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

            if (password === '') {
                setError('password', 'Password is required.');
                valid = false;
            } else {
                setError('password', '');
            }

            return valid;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('.login-form');
            form.addEventListener('submit', function (e) {
                if (!validateLoginForm()) {
                    e.preventDefault();
                }
            });
            ['email', 'password'].forEach(function (id) {
                document.getElementById(id).addEventListener('input', function () {
                    const group = document.getElementById(id + '-group');
                    if (group.classList.contains('has-error')) {
                        validateLoginForm();
                    }
                });
            });
        });
    </script>
</body>
</html>
