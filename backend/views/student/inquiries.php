<?php
$pageTitle = $pageTitle ?? 'My Inquiries';
$pageDescription = $pageDescription ?? 'Submit and track your inquiries.';
$currentPage = $currentPage ?? 'inquiries';
$assetPath = url('/frontend/assets');
$inquiries = $inquiries ?? [];
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
.card__body{padding:0}

.table{width:100%;border-collapse:collapse}
.table th{padding:12px 20px;text-align:left;font-size:11px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;color:#9ca3af;background:#fafbfc;border-bottom:1px solid #e5e7eb}
.table th:last-child{text-align:right}
.table td{padding:16px 20px;font-size:14px;color:#43474f;border-bottom:1px solid #f3f4f6;vertical-align:middle}
.table tbody tr:last-child td{border-bottom:none}
.table tbody tr:hover{background:#fafbff}

.badge{display:inline-flex;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:500;white-space:nowrap}
.badge--new{background:#dbeafe;color:#2563eb}
.badge--assigned{background:#fef3c7;color:#d97706}
.badge--in-progress{background:#fef3c7;color:#d97706}
.badge--closed{background:#e5e7eb;color:#6b7280}

.inquiry-id{font-size:12px;color:#9ca3af;font-weight:500}
.msg-preview{font-size:13px;color:#73777f;max-width:260px;overflow:hidden;text-overflow:ellipsis;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical}

.empty{text-align:center;padding:48px 24px}
.empty svg{width:40px;height:40px;color:#d0d5dd;margin:0 auto 12px;display:block}
.empty p{margin:0;color:#9ca3af;font-size:14px}

.new-inquiry{margin-top:24px}
.form{padding:24px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}
.form-group label{display:block;font-size:13px;font-weight:500;color:#43474f;margin-bottom:6px}
.form-group select,.form-group textarea{width:100%;padding:10px 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;color:#101828;background:#fff;box-sizing:border-box}
.form-group select:focus,.form-group textarea:focus{outline:none;border-color:#0054cb;box-shadow:0 0 0 3px rgba(0,84,203,0.1)}
.form-group select:disabled{background:#f9fafb;color:#9ca3af;cursor:not-allowed}
.form-group textarea{resize:vertical}
.hint{font-size:12px;color:#73777f;margin-top:6px;line-height:1.5}
.hint.inquired{color:#d97706}
.form-actions{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-top:20px}
.btn{display:inline-flex;align-items:center;justify-content:center;height:42px;padding:0 24px;border-radius:8px;background:#0054cb;color:#fff;font-size:14px;font-weight:500;border:none;cursor:pointer}
.btn:hover{background:#004aaf}

@media(max-width:768px){
  .form-row{grid-template-columns:1fr}
  .table th:nth-child(2),.table td:nth-child(2){display:none}
  .msg-preview{max-width:160px}
}
</style>
<div class="page-header">
    <h2>My Inquiries</h2>
    <p>Submit new inquiries and track their status.</p>
</div>

<div class="card">
    <div class="card__header">
        <h3>Submitted Inquiries</h3>
        <span style="font-size:13px;color:#73777f;"><?php echo count($inquiries); ?> total</span>
    </div>
    <div class="card__body">
        <?php if (!empty($inquiries)): ?>
            <table class="table">
                <thead><tr><th>Date</th><th>Destination</th><th>Study Level</th><th>Message</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($inquiries as $inq): ?>
                        <tr>
                            <td style="white-space:nowrap;">
                                <div style="font-weight:500;color:#0b1c30;"><?php echo e(date('M d, Y', strtotime($inq['created_at']))); ?></div>
                                <div class="inquiry-id"><?php echo e($inq['inquiry_id'] ?? ''); ?></div>
                            </td>
                            <td><?php echo e($inq['country_of_interest'] ?? '-'); ?></td>
                            <td><?php echo e($inq['level_of_study'] ?? '-'); ?></td>
                            <td><div class="msg-preview"><?php echo e($inq['message'] ?? '-'); ?></div></td>
                            <td>
                                <span class="status-pill">
                                    <span class="badge badge--<?php echo e($inq['status']); ?>"><?php echo e(ucfirst($inq['status'])); ?></span>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                <p>No inquiries submitted yet. Start one below.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card new-inquiry">
    <div class="card__header"><h3>New Inquiry</h3></div>
    <form class="form" method="POST" action="<?php echo url('/student/inquiries/store'); ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Preferred Study Destination</label>
                <select name="country_of_interest" required>
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
                        $alreadyInquired = in_array($value, $inquiredCountries, true);
                    ?>
                        <?php if ($alreadyInquired): ?>
                            <option value="<?php echo e($value); ?>" disabled><?php echo e($label); ?> (Already inquired)</option>
                        <?php else: ?>
                            <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Study Level</label>
                <select name="level_of_study" required>
                    <option value="" disabled selected></option>
                    <option value="High School">High School</option>
                    <option value="Undergraduate">Undergraduate</option>
                    <option value="Postgraduate">Postgraduate</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Message</label>
            <textarea name="message" rows="3" placeholder="Tell us what you would like to know about your study destination..." required></textarea>
        </div>
        <?php if (!empty($inquiredCountries)): ?>
            <div class="hint inquired">
                You already have an inquiry for: <?php echo e(implode(', ', array_unique($inquiredCountries))); ?>.
                For those destinations you can only ask for their status.
            </div>
        <?php endif; ?>
        <div class="form-actions">
            <span class="hint">Fields marked required must be filled in.</span>
            <button type="submit" class="btn">Submit Inquiry</button>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/student-layout.php';