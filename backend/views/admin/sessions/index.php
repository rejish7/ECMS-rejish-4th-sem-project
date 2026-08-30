<?php ob_start(); ?>
<style>
    .sessions-page { display: flex; flex-direction: column; gap: 24px; }
    .sessions-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; }
    .sessions-header h2 { margin: 0; color: #0b1c30; font-size: 32px; line-height: 1.2; font-weight: 700; }
    .sessions-header p { margin: 4px 0 0; color: #73777f; font-size: 14px; }
    .sessions-primary-button { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; text-decoration: none; }
    .sessions-primary-button:hover { background: #004aaf; }
    .sessions-card { overflow: hidden; border: 1px solid #e5e7eb; border-radius: 12px; background: #fff; }
    .sessions-table-wrap { overflow-x: auto; }
    .sessions-table { width: 100%; border-collapse: collapse; }
    .sessions-table th { padding: 12px 24px; border-bottom: 1px solid #e5e7eb; background: #fafbfc; color: #9ca3af; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; text-align: left; }
    .sessions-table th:last-child { text-align: right; }
    .sessions-table td { padding: 16px 24px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #43474f; }
    .sessions-table tbody tr:last-child td { border-bottom: none; }
    .sessions-table tbody tr:hover { background: #fafbff; }
    .sessions-chip { display: inline-flex; padding: 2px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500; }
    .sessions-chip--in-person { background: #dce9ff; color: #0054cb; }
    .sessions-chip--video { background: #d1fae5; color: #065f46; }
    .sessions-status { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; }
    .sessions-status__dot { width: 8px; height: 8px; border-radius: 9999px; }
    .sessions-status--completed { color: #059669; } .sessions-status--completed .sessions-status__dot { background: #10b981; }
    .sessions-status--scheduled { color: #2563eb; } .sessions-status--scheduled .sessions-status__dot { background: #3b82f6; }
    .sessions-status--cancelled { color: #9ca3af; } .sessions-status--cancelled .sessions-status__dot { background: #d1d5db; }
    .sessions-actions { display: flex; gap: 4px; justify-content: flex-end; }
    .sessions-action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: none; background: none; cursor: pointer; text-decoration: none; }
    .sessions-action-btn:hover { background: #f3f4f6; }
    .sessions-action-btn svg { width: 16px; height: 16px; }
    .sessions-action-btn--view svg { color: #2563eb; }
    .sessions-action-btn--edit svg { color: #6b7280; }
    .sessions-action-btn--delete svg { color: #ef4444; }
    .sessions-pagination { display: flex; align-items: center; justify-content: space-between; padding: 14px 24px; border-top: 1px solid #e5e7eb; }
    .sessions-pagination__info { font-size: 13px; color: #73777f; }
</style>
<div class="sessions-page">
    <section class="sessions-header">
        <div><h2>Counseling Sessions</h2><p>Manage and track counseling sessions.</p></div>
        <a href="<?php echo url('/admin/sessions/create'); ?>" class="sessions-primary-button">Schedule Session</a>
    </section>
    <section class="sessions-card">
        <div class="sessions-table-wrap">
            <table class="sessions-table">
                <thead><tr><th>Session ID</th><th>Student</th><th>Counselor</th><th>Mode</th><th>Date & Time</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php if (!empty($sessions)): ?>
                        <?php foreach ($sessions as $s): ?>
                            <tr>
                                <td><?php echo e($s['session_id'] ?? $s['id']); ?></td>
                                <td><?php echo e($s['student_name'] ?? '-'); ?></td>
                                <td><?php echo e($s['counselor_name'] ?? '-'); ?></td>
                                <td><span class="sessions-chip sessions-chip--<?php echo e($s['mode'] ?? 'in-person') === 'Video Call' ? 'video' : 'in-person'; ?>"><?php echo e($s['mode'] ?? '-'); ?></span></td>
                                <td><?php echo e($s['datetime'] ?? '-'); ?></td>
                                <td><span class="sessions-status sessions-status--<?php echo e($s['status'] ?? 'scheduled'); ?>"><span class="sessions-status__dot"></span><?php echo e($s['status'] ?? '-'); ?></span></td>
                                <td>
                                    <div class="sessions-actions">
                                        <a href="<?php echo url('/admin/sessions/' . $s['id']); ?>" class="sessions-action-btn sessions-action-btn--view"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg></a>
                                        <a href="<?php echo url('/admin/sessions/' . $s['id'] . '/edit'); ?>" class="sessions-action-btn sessions-action-btn--edit"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg></a>
                                        <form method="POST" action="<?php echo url('/admin/sessions/' . $s['id'] . '/delete'); ?>" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="sessions-action-btn sessions-action-btn--delete"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg></button></form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align:center;padding:40px;color:#9ca3af;">No sessions found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="sessions-pagination"><div class="sessions-pagination__info">Showing <?php echo count($sessions); ?> of <?php echo e($total); ?> entries</div></div>
    </section>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';