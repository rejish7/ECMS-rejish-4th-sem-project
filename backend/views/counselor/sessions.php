<?php
$sessions = $sessions ?? [];
$upcoming = $upcoming ?? [];
$completed = $completed ?? [];
ob_start();
?>
<style>
    .ses-page { display: flex; flex-direction: column; gap: 24px; }
    .ses-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; }
    .ses-header h2 { margin: 0; color: #0b1c30; font-size: 32px; font-weight: 700; }
    .ses-header p { margin: 4px 0 0; color: #73777f; font-size: 14px; }
    .ses-primary-btn { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 20px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; text-decoration: none; }
    .ses-primary-btn:hover { background: #004aaf; }

    .ses-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .ses-stat { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; }
    .ses-stat__value { font-size: 28px; font-weight: 700; color: #0b1c30; }
    .ses-stat__label { font-size: 13px; color: #73777f; margin-top: 4px; }

    .ses-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
    .ses-card-header { padding: 20px 24px; border-bottom: 1px solid #e5e7eb; }
    .ses-card-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #0b1c30; }
    .ses-table { width: 100%; border-collapse: collapse; }
    .ses-table th { padding: 12px 24px; text-align: left; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: #9ca3af; background: #fafbfc; border-bottom: 1px solid #e5e7eb; }
    .ses-table td { padding: 14px 24px; font-size: 14px; color: #43474f; border-bottom: 1px solid #f3f4f6; }
    .ses-table tbody tr:last-child td { border-bottom: none; }
    .ses-table tbody tr:hover { background: #fafbff; }
    .ses-student { display: flex; align-items: center; gap: 10px; }
    .ses-avatar { width: 34px; height: 34px; border-radius: 9999px; background: #dbeafe; color: #2563eb; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; flex: 0 0 auto; }
    .ses-student-name { font-weight: 600; color: #0b1c30; }
    .ses-student-id { font-size: 12px; color: #73777f; }
    .ses-chip { display: inline-flex; padding: 2px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500; }
    .ses-chip--video { background: #d1fae5; color: #065f46; }
    .ses-chip--in-person { background: #dce9ff; color: #0054cb; }
    .ses-status { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; }
    .ses-status__dot { width: 8px; height: 8px; border-radius: 9999px; }
    .ses-status--completed { color: #059669; } .ses-status--completed .ses-status__dot { background: #10b981; }
    .ses-status--scheduled { color: #2563eb; } .ses-status--scheduled .ses-status__dot { background: #3b82f6; }
    .ses-status--in-progress { color: #d97706; } .ses-status--in-progress .ses-status__dot { background: #f59e0b; }
    .ses-status--cancelled { color: #9ca3af; } .ses-status--cancelled .ses-status__dot { background: #d1d5db; }
    .ses-actions { display: flex; gap: 8px; align-items: center; justify-content: flex-end; }
    .ses-status-select { height: 34px; padding: 0 8px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 13px; color: #344054; background: #fff; }
    .ses-status-select:focus { outline: none; border-color: #0054cb; }
    .ses-action-btn { display: inline-flex; align-items: center; height: 34px; padding: 0 12px; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; font-size: 13px; font-weight: 500; color: #43474f; cursor: pointer; }
    .ses-action-btn:hover { background: #f9fafb; border-color: #d1d5db; }
    .ses-empty { text-align: center; padding: 40px 20px; color: #9ca3af; font-size: 14px; }
    @media (max-width: 768px) { .ses-stats { grid-template-columns: 1fr; } }
</style>
<div class="ses-page">
    <section class="ses-header">
        <div>
            <h2>Counseling Sessions</h2>
            <p>Sessions with your assigned students.</p>
        </div>
        <a href="<?php echo url('/counselor/sessions/create'); ?>" class="ses-primary-btn">Schedule Session</a>
    </section>

    <section class="ses-stats">
        <div class="ses-stat"><div class="ses-stat__value"><?php echo e(count($sessions)); ?></div><div class="ses-stat__label">Total Sessions</div></div>
        <div class="ses-stat"><div class="ses-stat__value" style="color:#2563eb;"><?php echo e(count($upcoming)); ?></div><div class="ses-stat__label">Upcoming</div></div>
        <div class="ses-stat"><div class="ses-stat__value" style="color:#059669;"><?php echo e(count($completed)); ?></div><div class="ses-stat__label">Completed</div></div>
    </section>

    <section class="ses-card">
        <div class="ses-card-header"><h3>All Sessions</h3></div>
        <?php if (!empty($sessions)): ?>
            <table class="ses-table">
                <thead><tr><th>Student</th><th>Session ID</th><th>Mode</th><th>Date &amp; Time</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($sessions as $s): ?>
                        <tr>
                            <td>
                                <div class="ses-student">
                                    <span class="ses-avatar"><?php echo e(strtoupper(substr($s['student_name'] ?? '?', 0, 2))); ?></span>
                                    <div>
                                        <div class="ses-student-name"><?php echo e($s['student_name'] ?? '-'); ?></div>
                                        <div class="ses-student-id"><?php echo e($s['student_code'] ?? ''); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($s['session_id'] ?? $s['id']); ?></td>
                            <td><span class="ses-chip ses-chip--<?php echo e(($s['mode'] ?? 'In-Person') === 'Video Call' ? 'video' : 'in-person'); ?>"><?php echo e($s['mode'] ?? '-'); ?></span></td>
                            <td><?php echo e(date('M d, Y g:i A', strtotime($s['datetime']))); ?></td>
                            <td><span class="ses-status ses-status--<?php echo e($s['status']); ?>"><span class="ses-status__dot"></span><?php echo e(ucfirst($s['status'])); ?></span></td>
                            <td>
                                <?php if (in_array($s['status'] ?? '', ['scheduled', 'in-progress'], true)): ?>
                                    <form method="POST" action="<?php echo url('/counselor/sessions/' . $s['id'] . '/status'); ?>" class="ses-actions">
                                        <select name="status" class="ses-status-select">
                                            <option value="in-progress" <?php echo ($s['status'] ?? '') === 'in-progress' ? 'selected' : ''; ?>>In Progress</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                        <button type="submit" class="ses-action-btn">Update</button>
                                    </form>
                                <?php else: ?>
                                    <span style="color:#9ca3af;font-size:12px;">&mdash;</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="ses-empty">No sessions scheduled yet. Click "Schedule Session" to create your first one.</div>
        <?php endif; ?>
    </section>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../layouts/counselor-layout.php';