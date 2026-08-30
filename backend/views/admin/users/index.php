<?php ob_start(); ?>
<style>
    .um-page { display: flex; flex-direction: column; gap: 24px; }
    .um-header { display: flex; justify-content: space-between; align-items: center; }
    .um-header h2 { margin: 0; font-size: 32px; font-weight: 700; color: #0b1c30; }
    .um-header p { margin: 4px 0 0; color: #73777f; font-size: 14px; }
    .um-add-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #0054cb; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; }
    .um-add-btn:hover { background: #0044a8; }
    .um-table-card { background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
    .um-table-wrap { overflow-x: auto; }
    .um-table { width: 100%; border-collapse: collapse; }
    .um-table th { padding: 12px 24px; background: #f9fafb; font-size: 11px; font-weight: 600; color: #73777f; text-transform: uppercase; letter-spacing: 0.5px; text-align: left; border-bottom: 1px solid #e5e7eb; }
    .um-table th:last-child { text-align: right; }
    .um-table td { padding: 16px 24px; border-bottom: 1px solid #f3f4f6; font-size: 14px; vertical-align: middle; }
    .um-table tbody tr:hover { background: #f9fafb; }
    .um-table tbody tr:last-child td { border-bottom: none; }
    .um-user { display: flex; align-items: center; gap: 12px; }
    .um-user-initials { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 600; color: #fff; background: #3b82f6; }
    .um-role-badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
    .um-role-badge--administrator { background: #ede9fe; color: #7c3aed; }
    .um-role-badge--counselor { background: #dbeafe; color: #2563eb; }
    .um-role-badge--student { background: #fef3c7; color: #d97706; }
    .um-status { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; }
    .um-status-dot { width: 7px; height: 7px; border-radius: 50%; }
    .um-status--active .um-status-dot { background: #10b981; }
    .um-status--active { color: #059669; }
    .um-status--inactive .um-status-dot { background: #d1d5db; }
    .um-status--inactive { color: #9ca3af; }
    .um-actions { display: flex; gap: 4px; justify-content: flex-end; }
    .um-action-btn { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 6px; border: none; background: none; cursor: pointer; text-decoration: none; }
    .um-action-btn:hover { background: #f3f4f6; }
    .um-action-btn svg { width: 16px; height: 16px; }
    .um-action-btn--view svg { color: #2563eb; }
    .um-action-btn--delete svg { color: #ef4444; }
    .um-pagination { display: flex; justify-content: space-between; align-items: center; padding: 14px 24px; border-top: 1px solid #e5e7eb; }
    .um-pagination-info { font-size: 13px; color: #73777f; }
</style>
<div class="um-page">
    <div class="um-header">
        <div><h2>User Management</h2><p>Manage all system users across roles.</p></div>
        <a href="<?php echo url('/admin/users/create'); ?>" class="um-add-btn">+ Add New User</a>
    </div>
    <div class="um-table-card">
        <div class="um-table-wrap">
            <table class="um-table">
                <thead><tr><th>User</th><th>Role</th><th>Email</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><div class="um-user"><span class="um-user-initials"><?php echo e(substr($u['name'], 0, 2)); ?></span><div><strong><?php echo e($u['name']); ?></strong><br><small style="color:#73777f;"><?php echo e($u['user_id'] ?? $u['id']); ?></small></div></div></td>
                                <td><span class="um-role-badge um-role-badge--<?php echo e(strtolower($u['role'])); ?>"><?php echo e($u['role']); ?></span></td>
                                <td><?php echo e($u['email']); ?></td>
                                <td><span class="um-status um-status--<?php echo e($u['status'] ?? 'active'); ?>"><span class="um-status-dot"></span><?php echo e($u['status'] ?? 'active'); ?></span></td>
                                <td>
                                    <div class="um-actions">
                                        <a href="<?php echo url('/admin/users/' . $u['id']); ?>" class="um-action-btn um-action-btn--view"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg></a>
                                        <form method="POST" action="<?php echo url('/admin/users/' . $u['id'] . '/delete'); ?>" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="um-action-btn um-action-btn--delete"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg></button></form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center;padding:40px;color:#9ca3af;">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="um-pagination"><span class="um-pagination-info">Showing <?php echo count($users); ?> of <?php echo e($total); ?> users</span></div>
    </div>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';