<?php
$pageTitle = $pageTitle ?? 'My Inquiries';
$pageDescription = $pageDescription ?? 'Submit and track your inquiries.';
$currentPage = $currentPage ?? 'inquiries';
$assetPath = url('/frontend/assets');
$inquiredCountries = $inquiredCountries ?? [];
ob_start();
?>
<style>
.page-header{margin-bottom:24px}
.page-header h2{margin:0;color:#0b1c30;font-size:32px;font-weight:700;letter-spacing:-0.64px}
.page-header p{margin:4px 0 0;color:#73777f;font-size:14px}
.card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 1px 2px rgba(0,0,0,0.04);overflow:hidden}
.card__header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid #e5e7eb}
.card__header h3{margin:0;font-size:18px;font-weight:700;color:#0b1c30}

.new-inquiry{margin-top:24px}
.form{padding:24px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}
.form-group label{display:block;font-size:13px;font-weight:500;color:#43474f;margin-bottom:6px}
.form-group select,.form-group textarea{width:100%;padding:10px 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;color:#101828;background:#fff;box-sizing:border-box}
.form-group select:focus,.form-group textarea:focus{outline:none;border-color:#0054cb;box-shadow:0 0 0 3px rgba(0,84,203,0.1)}
.form-group textarea{resize:vertical}
.hint{font-size:12px;color:#73777f;margin-top:6px;line-height:1.5}
.hint.inquired{color:#d97706}
.form-actions{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-top:20px}
.btn{display:inline-flex;align-items:center;justify-content:center;height:42px;padding:0 24px;border-radius:8px;background:#0054cb;color:#fff;font-size:14px;font-weight:500;border:none;cursor:pointer}
.btn:hover{background:#004aaf}

@media(max-width:768px){
  .form-row{grid-template-columns:1fr}
}
</style>
<div class="page-header">
    <h2>My Inquiries</h2>
    <p>Submit new inquiries for your study destinations.</p>
</div>

<div class="card new-inquiry">
    <div class="card__header"><h3>New Inquiry</h3></div>
    <form class="form" method="POST" action="<?php echo url('/student/inquiries/store'); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-row">
            <div class="form-group">
                <label>Preferred Study Destination</label>
                <select name="country_of_interest">
                    <option value="" disabled selected></option>
                    <?php
                    $countryOptions = [
                        'USA' => 'United States',
                        'UK' => 'United Kingdom',
                        'Canada' => 'Canada',
                        'Australia' => 'Australia',
                        'Germany' => 'Germany',
                        'New Zealand' => 'New Zealand',
                        'Other' => 'Other',
                    ];
                    foreach ($countryOptions as $value => $label):
                        if (in_array($value, $inquiredCountries, true)) {
                            continue;
                        }
                    ?>
                        <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($_SESSION['errors']['country_of_interest'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['country_of_interest']); ?></div><?php endif; ?>
                <?php if (!empty($inquiredCountries)): ?>
                    <div class="hint inquired">
                        You have already inquired for these countries: <?php echo e(implode(', ', array_map(function ($c) use ($countryOptions) { return $countryOptions[$c] ?? $c; }, array_unique($inquiredCountries)))); ?>.
                        You can only submit a new inquiry for the countries not listed above.
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Study Level</label>
                <select name="level_of_study">
                    <option value="" disabled selected></option>
                    <option value="High School">High School</option>
                    <option value="Undergraduate">Undergraduate</option>
                    <option value="Postgraduate">Postgraduate</option>
                </select>
                <?php if (!empty($_SESSION['errors']['level_of_study'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['level_of_study']); ?></div><?php endif; ?>
            </div>
        </div>
        <div class="form-group">
            <label>Message</label>
            <textarea name="message" rows="3" placeholder="Tell us what you would like to know about your study destination..."></textarea>
            <?php if (!empty($_SESSION['errors']['message'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['message']); ?></div><?php endif; ?>
        </div>
        <div class="form-actions">
            <span class="hint">All fields are validated server-side.</span>
            <button type="submit" class="btn">Submit Inquiry</button>
        </div>
        <?php unset($_SESSION['errors'], $_SESSION['old']); ?>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/student-layout.php';