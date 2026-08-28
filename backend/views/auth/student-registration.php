<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | ECMS</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #eef2ff 0%, #e8eeff 50%, #f0f4ff 100%); padding: 32px 16px; }

        .register-shell { width: 100%; max-width: 700px; }

        .register-header { text-align: center; margin-bottom: 32px; }
        .register-icon { display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; background: #fff; border: 2px solid #d4ddff; border-radius: 12px; margin-bottom: 16px; }
        .register-icon svg { width: 28px; height: 28px; color: #0054cb; }
        .register-header h1 { font-size: 36px; font-weight: 800; color: #0b1c30; margin-bottom: 8px; }
        .register-header p { font-size: 15px; color: #73777f; }

        .register-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.04); overflow: hidden; }

        /* Stepper */
        .stepper { display: flex; align-items: flex-start; padding: 32px 40px 0; position: relative; }
        .step { flex: 1; text-align: center; position: relative; z-index: 1; }
        .step-circle { width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 600; margin-bottom: 8px; border: 2px solid #d0d5dd; color: #9ca3af; background: #fff; transition: all 0.3s; }
        .step.active .step-circle { background: #0054cb; border-color: #0054cb; color: #fff; }
        .step.done .step-circle { background: #0054cb; border-color: #0054cb; color: #fff; }
        .step-label { font-size: 13px; font-weight: 500; color: #9ca3af; }
        .step.active .step-label { color: #0054cb; font-weight: 600; }
        .step.done .step-label { color: #0054cb; }
        .step-line { position: absolute; top: 20px; left: calc(50% + 24px); width: calc(100% - 48px); height: 2px; background: #e5e7eb; z-index: 0; }
        .step.active .step-line { background: #0054cb; }

        /* Form Sections */
        .form-body { padding: 32px 40px 24px; }
        .form-section { margin-bottom: 32px; }
        .form-section:last-child { margin-bottom: 0; }
        .form-section h3 { font-size: 18px; font-weight: 700; color: #0b1c30; padding-bottom: 12px; border-bottom: 1px solid #e5e7eb; margin-bottom: 20px; }

        .form-row { display: flex; gap: 16px; margin-bottom: 0; }
        .form-group { flex: 1; margin-bottom: 20px; position: relative; }
        .form-group:last-child { margin-bottom: 0; }

        .form-group label { display: block; font-size: 13px; font-weight: 500; color: #344054; margin-bottom: 6px; }

        .form-group input,
        .form-group select { width: 100%; height: 44px; padding: 0 14px; border: 1px solid #d0d5dd; border-radius: 8px; font-size: 14px; color: #101828; background: #fff; transition: border-color 0.2s; appearance: none; -webkit-appearance: none; }
        .form-group input:focus,
        .form-group select:focus { outline: none; border-color: #0054cb; box-shadow: 0 0 0 3px rgba(0,84,203,0.1); }
        .form-group input::placeholder { color: #9ca3af; }

        .select-wrap { position: relative; }
        .select-wrap select { padding-right: 40px; cursor: pointer; }
        .select-wrap::after { content: ''; position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 0; height: 0; border-left: 5px solid transparent; border-right: 5px solid transparent; border-top: 5px solid #6b7280; pointer-events: none; }

        .floating-label { position: relative; }
        .floating-label label { position: absolute; top: -8px; left: 12px; font-size: 11px; color: #0054cb; background: #fff; padding: 0 4px; font-weight: 500; z-index: 1; }

        /* Divider */
        .section-divider { border: none; border-top: 1px solid #e5e7eb; margin: 28px 0; }

        /* Footer */
        .form-footer { display: flex; align-items: center; justify-content: space-between; padding: 16px 40px 32px; }
        .btn-cancel { font-size: 14px; font-weight: 500; color: #344054; background: none; border: none; cursor: pointer; padding: 8px 0; }
        .btn-cancel:hover { color: #0b1c30; }
        .btn-create { display: inline-flex; align-items: center; gap: 8px; height: 44px; padding: 0 28px; border-radius: 8px; background: linear-gradient(135deg, #0054cb 0%, #4f46e5 100%); color: #fff; font-size: 14px; font-weight: 600; border: none; cursor: pointer; transition: opacity 0.2s; }
        .btn-create:hover { opacity: 0.9; }
        .btn-create svg { width: 16px; height: 16px; }

        .register-footer { text-align: center; margin-top: 24px; font-size: 14px; color: #73777f; }
        .register-footer a { color: #0054cb; text-decoration: none; font-weight: 600; }
        .register-footer a:hover { text-decoration: underline; }

        .flash-error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }
        .flash-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }

        .form-group .field-error { color: #dc2626; font-size: 12px; font-weight: 500; margin-top: 6px; }
        .form-group.has-error input,
        .form-group.has-error select { border-color: #dc2626; background-color: #fef2f2; }
        .form-group.has-error input:focus,
        .form-group.has-error select:focus { box-shadow: 0 0 0 3px rgba(220,38,38,0.15); border-color: #dc2626; }
        .form-group.has-error input::placeholder,
        .form-group.has-error select::placeholder { color: #fca5a5; }

        @media (max-width: 600px) {
            .form-row { flex-direction: column; gap: 0; }
            .stepper { padding: 24px 20px 0; }
            .form-body { padding: 24px 20px 16px; }
            .form-footer { padding: 12px 20px 24px; }
        }
    </style>
</head>
<body>
    <div class="register-shell">
        <div class="register-header">
            <div class="register-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4-4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <line x1="19" y1="8" x2="19" y2="14"/>
                    <line x1="22" y1="11" x2="16" y2="11"/>
                </svg>
            </div>
            <h1>Join ECMS </h1>
            <p>Set up your student profile to start your journey.</p>
        </div>

        <div class="register-card">
            <!-- Stepper -->
            <div class="stepper">
                <div class="step active">
                    <div class="step-line"></div>
                    <div class="step-circle">1</div>
                    <div class="step-label">Personal</div>
                </div>
                <div class="step">
                    <div class="step-line"></div>
                    <div class="step-circle">2</div>
                    <div class="step-label">Academic</div>
                </div>
                <div class="step">
                    <div class="step-circle">3</div>
                    <div class="step-label">Preferences</div>
                </div>
            </div>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="flash-error" style="margin:16px 40px 0;"><?php echo e($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="flash-success" style="margin:16px 40px 0;"><?php echo e($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo url('/register'); ?>" id="register-form" novalidate>
                <?php echo csrf_field(); ?>
                <div class="form-body">
                    <!-- Personal Details -->
                    <div class="form-section">
                        <h3>Personal Details</h3>
                        <div class="form-row">
                            <div class="form-group<?php echo field_error('name') ? ' has-error' : ''; ?>">
                                <input type="text" name="name" placeholder="Full Name" value="<?php echo e(old('name')); ?>" data-required>
                                <?php if (field_error('name')): ?><div class="field-error"><?php echo e(field_error('name')); ?></div><?php endif; ?>
                            </div>
                            <div class="form-group<?php echo field_error('email') ? ' has-error' : ''; ?>">
                                <input type="email" name="email" placeholder="Email Address" value="<?php echo e(old('email')); ?>" data-required>
                                <?php if (field_error('email')): ?><div class="field-error"><?php echo e(field_error('email')); ?></div><?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo field_error('phone') ? ' has-error' : ''; ?>">
                            <input type="tel" name="phone" id="phone" placeholder="Phone Number" pattern="[0-9]{10}" maxlength="10" title="Phone number must be exactly 10 digits." value="<?php echo e(old('phone')); ?>">
                            <?php if (field_error('phone')): ?><div class="field-error"><?php echo e(field_error('phone')); ?></div><?php endif; ?>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <!-- Login Credentials -->
                    <div class="form-section">
                        <h3>Login Credentials</h3>
                        <div class="form-row">
                            <div class="form-group<?php echo field_error('password') ? ' has-error' : ''; ?>">
                                <input type="password" name="password" placeholder="Create a password (min 6 characters)" data-required>
                                <?php if (field_error('password')): ?><div class="field-error"><?php echo e(field_error('password')); ?></div><?php endif; ?>
                            </div>
                            <div class="form-group<?php echo field_error('password_confirmation') ? ' has-error' : ''; ?>">
                                <input type="password" name="password_confirmation" placeholder="Confirm password" data-required>
                                <?php if (field_error('password_confirmation')): ?><div class="field-error"><?php echo e(field_error('password_confirmation')); ?></div><?php endif; ?>
                            </div>
                        </div>
                        <p style="font-size:12px;color:#9ca3af;margin-top:8px;">You will use your email and this password to sign in.</p>
                    </div>

                    <hr class="section-divider">

                    <!-- Academic Background -->
                    <div class="form-section">
                        <h3>Academic Background</h3>
                        <div class="form-row">
                            <div class="form-group floating-label<?php echo field_error('qualification') ? ' has-error' : ''; ?>">
                                <label>Highest Qualification</label>
                                <div class="select-wrap">
                                    <select name="qualification">
                                        <option value="" disabled<?php echo old('qualification') === '' ? ' selected' : ''; ?>></option>
                                        <option value="High School" <?php echo old('qualification') === 'High School' ? 'selected' : ''; ?>>High School</option>
                                        <option value="Associate Degree" <?php echo old('qualification') === 'Associate Degree' ? 'selected' : ''; ?>>Associate Degree</option>
                                        <option value="Bachelor's Degree" <?php echo old('qualification') === "Bachelor's Degree" ? 'selected' : ''; ?>>Bachelor's Degree</option>
                                        <option value="Master's Degree" <?php echo old('qualification') === "Master's Degree" ? 'selected' : ''; ?>>Master's Degree</option>
                                        <option value="PhD" <?php echo old('qualification') === 'PhD' ? 'selected' : ''; ?>>PhD</option>
                                    </select>
                                </div>
                                <?php if (field_error('qualification')): ?><div class="field-error"><?php echo e(field_error('qualification')); ?></div><?php endif; ?>
                            </div>
                            <div class="form-group floating-label<?php echo field_error('education_level') ? ' has-error' : ''; ?>">
                                <label>Desired Study Level</label>
                                <div class="select-wrap">
                                    <select name="education_level" data-required>
                                        <option value="" disabled<?php echo old('education_level') === '' ? ' selected' : ''; ?>></option>
                                        <option value="High School" <?php echo old('education_level') === 'High School' ? 'selected' : ''; ?>>High School</option>
                                        <option value="Undergraduate" <?php echo old('education_level') === 'Undergraduate' ? 'selected' : ''; ?>>Undergraduate</option>
                                        <option value="Postgraduate" <?php echo old('education_level') === 'Postgraduate' ? 'selected' : ''; ?>>Postgraduate</option>
                                    </select>
                                </div>
                                <?php if (field_error('education_level')): ?><div class="field-error"><?php echo e(field_error('education_level')); ?></div><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <!-- Preferences -->
                    <div class="form-section">
                        <h3>Preferences</h3>
                        <div class="form-group floating-label<?php echo field_error('destination') ? ' has-error' : ''; ?>">
                            <label>Preferred Study Destination</label>
                            <div class="select-wrap">
                                <select name="destination">
                                    <option value="" disabled<?php echo old('destination') === '' ? ' selected' : ''; ?>></option>
                                    <option value="USA" <?php echo old('destination') === 'USA' ? 'selected' : ''; ?>>United States</option>
                                    <option value="UK" <?php echo old('destination') === 'UK' ? 'selected' : ''; ?>>United Kingdom</option>
                                    <option value="Canada" <?php echo old('destination') === 'Canada' ? 'selected' : ''; ?>>Canada</option>
                                    <option value="Australia" <?php echo old('destination') === 'Australia' ? 'selected' : ''; ?>>Australia</option>
                                    <option value="Germany" <?php echo old('destination') === 'Germany' ? 'selected' : ''; ?>>Germany</option>
                                    <option value="New Zealand" <?php echo old('destination') === 'New Zealand' ? 'selected' : ''; ?>>New Zealand</option>
                                    <option value="Other" <?php echo old('destination') === 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            <?php if (field_error('destination')): ?><div class="field-error"><?php echo e(field_error('destination')); ?></div><?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <a href="<?php echo url('/login'); ?>" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-create">
                        Create Account
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="register-footer">
            Already have an account? <a href="<?php echo url('/login'); ?>">Sign in instead</a>
        </div>
    </div>

    <script>
        // Visual stepper progress
        const steps = document.querySelectorAll('.step');
        const inputs = document.querySelectorAll('.form-body input, .form-body select');

        function updateStepper() {
            const sections = document.querySelectorAll('.form-section');
            let currentStep = 0;

            sections.forEach((section, i) => {
                const sectionInputs = section.querySelectorAll('[data-required]');
                let filled = 0;
                sectionInputs.forEach(inp => { if (inp.value) filled++; });
                if (sectionInputs.length > 0 && filled === sectionInputs.length) {
                    currentStep = i + 1;
                }
            });

            steps.forEach((step, i) => {
                step.classList.remove('active', 'done');
                if (i < currentStep) step.classList.add('done');
                else if (i === currentStep) step.classList.add('active');
            });
        }

        inputs.forEach(inp => inp.addEventListener('change', updateStepper));
        inputs.forEach(inp => inp.addEventListener('input', updateStepper));

        // Field validation helpers
        function setFieldError(input, message) {
            const group = input.closest('.form-group');
            let errEl = group.querySelector('.field-error');
            if (!errEl) {
                errEl = document.createElement('div');
                errEl.className = 'field-error';
                group.appendChild(errEl);
            }
            errEl.textContent = message || '';
            group.classList.toggle('has-error', !!message);
        }

        const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        function validateField(field) {
            const name = field.name;
            const value = field.value.trim();

            if (name === 'name') {
                if (!value) { setFieldError(field, 'Full name is required.'); return false; }
                setFieldError(field, ''); return true;
            }
            if (name === 'email') {
                if (!value) { setFieldError(field, 'Email address is required.'); return false; }
                if (!emailRe.test(value)) { setFieldError(field, 'Please enter a valid email address.'); return false; }
                setFieldError(field, ''); return true;
            }
            if (name === 'phone') {
                if (value && !/^\d{10}$/.test(value)) { setFieldError(field, 'Phone number must be exactly 10 digits.'); return false; }
                setFieldError(field, ''); return true;
            }
            if (name === 'password') {
                if (!field.value) { setFieldError(field, 'Password is required.'); return false; }
                if (field.value.length < 6) { setFieldError(field, 'Password must be at least 6 characters.'); return false; }
                setFieldError(field, ''); return true;
            }
            if (name === 'password_confirmation') {
                const pw = document.getElementById('register-form').querySelector('input[name="password"]');
                if (field.value !== pw.value) { setFieldError(field, 'Passwords do not match.'); return false; }
                setFieldError(field, ''); return true;
            }
            if (name === 'education_level') {
                if (!value) { setFieldError(field, 'Please select a desired study level.'); return false; }
                setFieldError(field, ''); return true;
            }
            return true;
        }

        function validateRegisterForm() {
            const fields = ['name', 'email', 'phone', 'password', 'password_confirmation', 'education_level'];
            let valid = true;
            fields.forEach(function (n) {
                const field = document.getElementById('register-form').querySelector('[name="' + n + '"]');
                if (!validateField(field)) valid = false;
            });
            return valid;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('register-form');
            const fields = ['name', 'email', 'phone', 'password', 'password_confirmation', 'education_level'];

            form.addEventListener('submit', function (e) {
                if (!validateRegisterForm()) {
                    e.preventDefault();
                }
            });

            fields.forEach(function (n) {
                const field = form.querySelector('[name="' + n + '"]');
                field.addEventListener('input', function () {
                    if (field.name === 'phone') {
                        this.value = this.value.replace(/\D/g, '').slice(0, 10);
                    }
                    setFieldError(field, '');
                });
                field.addEventListener('blur', function () {
                    validateField(field);
                });
            });
        });
    </script>
</body>
</html>
