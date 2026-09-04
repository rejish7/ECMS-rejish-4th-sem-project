<?php ob_start(); ?>
<style>
.detail-page{max-width:800px}
.detail-page__header{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:24px}
.detail-page__header h2{margin:0;color:#0b1c30;font-size:32px;font-weight:700;letter-spacing:-0.64px}
.detail-page__header p{margin:4px 0 0;color:#73777f;font-size:14px}
.detail-page__actions{display:flex;gap:8px;flex-shrink:0}
.detail-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 1px 2px rgba(0,0,0,0.04);overflow:hidden}
.detail-card__header{padding:20px 24px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between}
.detail-card__header h3{margin:0;font-size:18px;font-weight:700;color:#0b1c30}
.detail-card__body{padding:0}
.detail-row{display:flex;padding:16px 24px;border-bottom:1px solid #f3f4f6}
.detail-row:last-child{border-bottom:none}
.detail-label{width:180px;font-size:13px;font-weight:500;color:#73777f;flex-shrink:0}
.detail-value{font-size:14px;color:#0b1c30;font-weight:500}
.detail-badge{display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:9999px;font-size:13px;font-weight:500}
.detail-badge--in-person{background:#dce9ff;color:#0054cb}
.detail-badge--video{background:#d1fae5;color:#065f46}
.detail-badge--completed{background:#d1fae5;color:#059669}
.detail-badge--scheduled{background:#dbeafe;color:#2563eb}
.detail-badge--cancelled{background:#f3f4f6;color:#6b7280}
.detail-badge--in-progress{background:#fef3c7;color:#d97706}
.detail-badge__dot{width:8px;height:8px;border-radius:9999px}
.detail-badge--completed .detail-badge__dot{background:#10b981}
.detail-badge--scheduled .detail-badge__dot{background:#3b82f6}
.detail-badge--cancelled .detail-badge__dot{background:#d1d5db}
.detail-badge--in-progress .detail-badge__dot{background:#f59e0b}
.detail-actions{display:flex;gap:12px;padding:16px 24px;border-top:1px solid #e5e7eb;background:#fafbfc}
.btn-primary{display:inline-flex;align-items:center;height:40px;padding:0 24px;border-radius:8px;background:#0054cb;color:#fff;font-size:14px;font-weight:500;border:none;cursor:pointer;text-decoration:none;transition:background 0.15s}
.btn-primary:hover{background:#004aaf}
.btn-secondary{display:inline-flex;align-items:center;height:40px;padding:0 24px;border-radius:8px;background:#fff;color:#43474f;border:1px solid #e5e7eb;font-size:14px;font-weight:500;text-decoration:none;transition:background 0.15s}
.btn-secondary:hover{background:#f9fafb}
.btn-danger{display:inline-flex;align-items:center;height:40px;padding:0 24px;border-radius:8px;background:#ef4444;color:#fff;font-size:14px;font-weight:500;border:none;cursor:pointer;transition:background 0.15s}
.btn-danger:hover{background:#dc2626}
</style>
<div class="detail-page">
    <div class="detail-page__header">
        <div>
            <h2>Session Details</h2>
            <p>Viewing session <?php echo e($session['session_id'] ?? $session['id']); ?></p>
        </div>
        <div class="detail-page__actions">
            <a href="<?php echo url('/admin/sessions/' . $session['id'] . '/edit'); ?>" class="btn-primary">Edit Session</a>
            <a href="<?php echo url('/admin/sessions'); ?>" class="btn-secondary">Back to List</a>
        </div>
    </div>
    <div class="detail-card">
        <div class="detail-card__header">
            <h3>Session Information</h3>
            <span class="detail-badge detail-badge--<?php echo e($session['status'] ?? 'scheduled'); ?>">
                <span class="detail-badge__dot"></span>
                <?php echo e(ucfirst(str_replace('-', ' ', $session['status'] ?? '-'))); ?>
            </span>
        </div>
        <div class="detail-card__body">
            <div class="detail-row">
                <span class="detail-label">Session ID</span>
                <span class="detail-value"><?php echo e($session['session_id'] ?? $session['id']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Student</span>
                <span class="detail-value"><?php echo e($session['student_name'] ?? '-'); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Counselor</span>
                <span class="detail-value"><?php echo e($session['counselor_name'] ?? '-'); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Mode</span>
                <span class="detail-value">
                    <span class="detail-badge detail-badge--<?php echo e(($session['mode'] ?? '') === 'Video Call' ? 'video' : 'in-person'); ?>">
                        <?php echo e($session['mode'] ?? '-'); ?>
                    </span>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Subject</span>
                <span class="detail-value"><?php echo e($session['subject'] ?? '-'); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date &amp; Time</span>
                <span class="detail-value">
                    <?php if (!empty($session['datetime'])): ?>
                        <?php echo e(date('l, F j, Y \a\t g:i A', strtotime($session['datetime']))); ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Created At</span>
                <span class="detail-value"><?php echo e($session['created_at'] ?? '-'); ?></span>
            </div>
        </div>
        <div class="detail-actions">
            <a href="<?php echo url('/admin/sessions/' . $session['id'] . '/edit'); ?>" class="btn-primary">Edit</a>
            <form method="POST" action="<?php echo url('/admin/sessions/' . $session['id'] . '/delete'); ?>" onsubmit="return confirm('Are you sure you want to delete this session?')">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';