<?php
$student = $student ?? null;
$documents = $documents ?? [];
$sessions = $sessions ?? [];
$inquiries = $inquiries ?? [];
ob_start();
?>
<style>
    .spro-page { display: flex; flex-direction: column; gap: 24px; }
    .spro-header { display: flex; align-items: center; justify-content: space-between; gap: 24px; }
    .spro-title { display: flex; align-items: center; gap: 16px; }
    .spro-avatar { width: 64px; height: 64px; border-radius: 50%; background: #e0edff; color: #0054cb; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 700; }
    .spro-title h2 { margin: 0; font-size: 28px; font-weight: 700; color: #0b1c30; }
    .spro-title p { margin: 4px 0 0; color: #73777f; font-size: 14px; }
    .spro-actions { display: flex; gap: 12px; }
    .spro-btn { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 20px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; text-decoration: none; }
    .spro-btn:hover { background: #004aaf; }
    .spro-btn--outline { background: #fff; color: #43474f; border: 1px solid #e5e7eb; }
    .spro-btn--outline:hover { background: #f9fafb; border-color: #d1d5db; }

    .spro-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
    .spro-card-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid #e5e7eb; }
    .spro-card-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #0b1c30; }
    .spro-count { font-size: 13px; color: #73777f; font-weight: 400; }
    .spro-card-body { padding: 20px 24px; }
    .spro-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px 40px; }
    .spro-row { display: flex; flex-direction: column; gap: 2px; }
    .spro-label { font-size: 12px; color: #73777f; }
    .spro-value { font-size: 14px; color: #0b1c30; font-weight: 500; }

    .spro-table { width: 100%; border-collapse: collapse; }
    .spro-table th { padding: 12px 24px; text-align: left; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: #9ca3af; background: #fafbfc; border-bottom: 1px solid #e5e7eb; }
    .spro-table td { padding: 14px 24px; font-size: 14px; color: #43474f; border-bottom: 1px solid #f3f4f6; }
    .spro-table tbody tr:last-child td { border-bottom: none; }
    .spro-table tbody tr:hover { background: #fafbff; }
    .spro-badge { display: inline-flex; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 500; }
    .spro-badge--new { background: #fef3c7; color: #d97706; }
    .spro-badge--assigned { background: #e0e7ff; color: #4338ca; }
    .spro-badge--in-progress { background: #fef3c7; color: #d97706; }
    .spro-badge--closed { background: #e5e7eb; color: #6b7280; }
    .spro-badge--scheduled { background: #dbeafe; color: #1d4ed8; }
    .spro-badge--completed { background: #d1fae5; color: #059669; }
    .spro-badge--cancelled { background: #fee2e2; color: #dc2626; }
    .spro-badge--pending { background: #fef3c7; color: #d97706; }
    .spro-badge--approved { background: #d1fae5; color: #059669; }
    .spro-badge--rejected { background: #fee2e2; color: #dc2626; }
    .spro-badge--resubmit { background: #dbeafe; color: #2563eb; }
    .spro-badge--education { background: #dbeafe; color: #1d4ed8; }
    .spro-badge--visa { background: #fef3c7; color: #d97706; }
    .spro-link { color: #0054cb; text-decoration: none; font-weight: 500; }
    .spro-link:hover { text-decoration: underline; }
    .spro-empty { text-align: center; padding: 32px 20px; color: #9ca3af; font-size: 14px; }
    @media (max-width: 600px) { .spro-grid { grid-template-columns: 1fr; } }
</style>
<div class="spro-page">
    <section class="spro-header">
        <div class="spro-title">
            <span class="spro-avatar"><?php echo e(strtoupper(substr($student['name'] ?? '?', 0, 2))); ?></span>
            <div>
                <h2><?php echo e($student['name'] ?? '-'); ?></h2>
                <p><?php echo e($student['student_id'] ?? $student['id']); ?> &middot; <?php echo e($student['education_level'] ?? 'N/A'); ?></p>
            </div>
        </div>
        <div class="spro-actions">
            <a class="spro-btn" href="<?php echo url('/counselor/sessions/create?student_id=' . $student['id']); ?>">Schedule Session</a>
            <a class="spro-btn spro-btn--outline" href="<?php echo url('/counselor/documents/assign?student_id=' . $student['id']); ?>">Assign Document</a>
        </div>
    </section>

    <section class="spro-card">
        <div class="spro-card-header"><h3>Profile Details</h3></div>
        <div class="spro-card-body">
            <div class="spro-grid">
                <div class="spro-row"><span class="spro-label">Student ID</span><span class="spro-value"><?php echo e($student['student_id'] ?? '-'); ?></span></div>
                <div class="spro-row"><span class="spro-label">Email</span><span class="spro-value"><?php echo e($student['email'] ?? '-'); ?></span></div>
                <div class="spro-row"><span class="spro-label">Education Level</span><span class="spro-value"><?php echo e($student['education_level'] ?? '-'); ?></span></div>
                <div class="spro-row"><span class="spro-label">Status</span><span class="spro-value"><?php echo e(ucfirst($student['status'] ?? 'active')); ?></span></div>
                <div class="spro-row"><span class="spro-label">Registered</span><span class="spro-value"><?php echo e(date('M d, Y', strtotime($student['created_at']))); ?></span></div>
            </div>
        </div>
    </section>

    <section class="spro-card">
        <div class="spro-card-header"><h3>Sessions <span class="spro-count">(<?php echo count($sessions); ?>)</span></h3></div>
        <?php if (!empty($sessions)): ?>
            <table class="spro-table">
                <thead><tr><th>Session ID</th><th>Mode</th><th>Date &amp; Time</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($sessions as $s): ?>
                        <tr>
                            <td><?php echo e($s['session_id'] ?? $s['id']); ?></td>
                            <td><?php echo e($s['mode'] ?? '-'); ?></td>
                            <td><?php echo e(date('M d, Y g:i A', strtotime($s['datetime']))); ?></td>
                            <td><span class="spro-badge spro-badge--<?php echo e($s['status']); ?>"><?php echo e(ucfirst($s['status'])); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="spro-empty">No sessions scheduled for this student.</div>
        <?php endif; ?>
    </section>

    <section class="spro-card">
        <div class="spro-card-header"><h3>Documents <span class="spro-count">(<?php echo count($documents); ?>)</span></h3></div>
        <?php if (!empty($documents)): ?>
            <table class="spro-table">
                <thead><tr><th>Document</th><th>Category</th><th>Status</th><th>Remarks</th></tr></thead>
                <tbody>
                    <?php foreach ($documents as $doc): ?>
                        <tr>
                            <td>
                                <div style="font-weight:500;color:#0b1c30;"><?php echo e($doc['name']); ?></div>
                                <?php if (!empty($doc['file_path'])): ?>
                                    <a class="spro-link" href="<?php echo e(url('/' . ltrim($doc['file_path'], '/'))); ?>" target="_blank" rel="noopener">View file</a>
                                <?php endif; ?>
                            </td>
                            <td><span class="spro-badge spro-badge--<?php echo e($doc['category']); ?>"><?php echo e(ucfirst($doc['category'])); ?></span></td>
                            <td><span class="spro-badge spro-badge--<?php echo e($doc['status']); ?>"><?php echo e(ucfirst($doc['status'])); ?></span></td>
                            <td><?php echo e($doc['remarks'] ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="spro-empty">No documents for this student.</div>
        <?php endif; ?>
    </section>

    <section class="spro-card">
        <div class="spro-card-header"><h3>Inquiries <span class="spro-count">(<?php echo count($inquiries); ?>)</span></h3></div>
        <?php if (!empty($inquiries)): ?>
            <table class="spro-table">
                <thead><tr><th>Inquiry ID</th><th>Country</th><th>Level</th><th>Date</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($inquiries as $inq): ?>
                        <tr>
                            <td><span style="color:#0054cb;font-weight:600;">#<?php echo e($inq['inquiry_id']); ?></span></td>
                            <td><?php echo e($inq['country_of_interest'] ?? '-'); ?></td>
                            <td><?php echo e($inq['level_of_study'] ?? '-'); ?></td>
                            <td><?php echo e(date('M d, Y', strtotime($inq['created_at']))); ?></td>
                            <td><span class="spro-badge spro-badge--<?php echo e($inq['status']); ?>"><?php echo e(ucwords(str_replace('-', ' ', $inq['status']))); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="spro-empty">No inquiries from this student.</div>
        <?php endif; ?>
    </section>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../layouts/counselor-layout.php';