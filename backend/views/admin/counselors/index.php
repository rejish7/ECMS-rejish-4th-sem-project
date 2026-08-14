<?php
$assetPath = url('/frontend/assets');
$imagesPath = $assetPath . '/images/counselors-dashboard';
ob_start();
?>
<style>
    .cr-page { display: flex; flex-direction: column; gap: 24px; }
    .cr-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; }
    .cr-header h2 { margin: 0; color: #0b1c30; font-size: 32px; font-weight: 700; line-height: 1.2; letter-spacing: -0.64px; }
    .cr-header p { margin: 4px 0 0; color: #73777f; font-size: 14px; line-height: 1.5; }
    .cr-btn-add { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; cursor: pointer; transition: background 0.15s; text-decoration: none; }
    .cr-btn-add:hover { background: #004aaf; }
    .cr-table-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); overflow: hidden; }
    .cr-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 16px 24px; background: #fafbfc; border-bottom: 1px solid #e5e7eb; }
    .cr-search { position: relative; display: flex; align-items: center; width: 320px; }
    .cr-search input { width: 100%; height: 40px; padding: 10px 17px 10px 40px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb; color: #0b1c30; font-size: 14px; outline: none; }
    .cr-search input:focus { border-color: #0054cb; background: #fff; }
    .cr-table-wrap { overflow-x: auto; }
    .cr-table { width: 100%; border-collapse: collapse; }
    .cr-table th { padding: 14px 24px; text-align: left; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: #9ca3af; background: #fafbfc; border-bottom: 1px solid #e5e7eb; }
    .cr-table th:last-child { text-align: right; }
    .cr-table td { padding: 16px 24px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #43474f; vertical-align: middle; }
    .cr-table tbody tr:last-child td { border-bottom: none; }
    .cr-table tbody tr:hover { background: #fafbff; }
    .cr-person { display: flex; align-items: center; gap: 12px; }
    .cr-avatar { width: 40px; height: 40px; border-radius: 9999px; display: inline-flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; flex: 0 0 auto; background: #163b65; color: #86a6d6; }
    .cr-person__info { display: flex; flex-direction: column; min-width: 0; }
    .cr-person__name { font-size: 15px; font-weight: 600; color: #0b1c30; }
    .cr-person__email { font-size: 13px; color: #73777f; }
    .cr-spec-badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 6px; background: #e0edff; color: #0054cb; font-size: 12px; font-weight: 500; }
    .cr-actions { display: flex; gap: 4px; justify-content: flex-end; }
    .cr-action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: none; background: none; cursor: pointer; }
    .cr-action-btn:hover { background: #f3f4f6; }
    .cr-action-btn svg { width: 16px; height: 16px; }
    .cr-action-btn--view svg { color: #2563eb; }
    .cr-action-btn--edit svg { color: #6b7280; }
    .cr-action-btn--delete svg { color: #ef4444; }
    .cr-pagination { display: flex; align-items: center; justify-content: space-between; padding: 14px 24px; border-top: 1px solid #e5e7eb; }
    .cr-pagination-info { font-size: 13px; color: #73777f; }
    .cr-pagination-pages { display: flex; align-items: center; gap: 4px; }
    .cr-pagination-page { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 13px; font-weight: 500; color: #6b7280; cursor: pointer; text-decoration: none; }
    .cr-pagination-page:hover { background: #f3f4f6; }
    .cr-pagination-page--active { background: #0054cb; color: #fff; }
</style>

<div class="cr-page">
    <section class="cr-header">
        <div>
            <h2>Counselors Management</h2>
            <p>Manage educational counselors, their specializations, and availability.</p>
        </div>
        <a href="<?php echo url('/admin/counselors/create'); ?>" class="cr-btn-add">+ Add Counselor</a>
    </section>

    <section class="cr-table-card">
        <div class="cr-toolbar">
            <form method="GET" action="<?php echo url('/admin/counselors'); ?>" style="width:100%;">
                <label class="cr-search">
                    <input type="search" name="search" placeholder="Search counselors..." value="<?php echo e($filters['search'] ?? ''); ?>">
                </label>
            </form>
        </div>
        <div class="cr-table-wrap">
            <table class="cr-table">
                <thead>
                    <tr><th>Counselor Name</th><th>Specialization</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php if (!empty($counselors)): ?>
                        <?php foreach ($counselors as $c): ?>
                            <tr>
                                <td>
                                    <div class="cr-person">
                                        <div class="cr-avatar"><?php echo e(substr($c['name'], 0, 2)); ?></div>
                                        <div class="cr-person__info">
                                            <span class="cr-person__name"><?php echo e($c['name']); ?></span>
                                            <span class="cr-person__email"><?php echo e($c['email']); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="cr-spec-badge"><?php echo e($c['specialization'] ?? '-'); ?></span></td>
                                <td><?php echo e($c['status'] ?? 'available'); ?></td>
                                <td>
                                    <div class="cr-actions">
                                        <a href="<?php echo url('/admin/counselors/' . $c['id']); ?>" class="cr-action-btn cr-action-btn--view"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg></a>
                                        <a href="<?php echo url('/admin/counselors/' . $c['id'] . '/edit'); ?>" class="cr-action-btn cr-action-btn--edit"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg></a>
                                        <form method="POST" action="<?php echo url('/admin/counselors/' . $c['id'] . '/delete'); ?>" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                            <button type="submit" class="cr-action-btn cr-action-btn--delete"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center;padding:40px;color:#9ca3af;">No counselors found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="cr-pagination">
            <span class="cr-pagination-info">Showing <?php echo count($counselors); ?> of <?php echo e($total); ?> entries</span>
        </div>
    </section>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';