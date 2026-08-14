<?php
$assetPath = url('/frontend/assets');
$studentsAssetPath = $assetPath . '/images/students';

ob_start();
?>
<style>
    .students-page { display: flex; flex-direction: column; gap: 24px; }
    .students-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; }
    .students-header h2 { margin: 0; color: #0b1c30; font-size: 32px; line-height: 1.2; font-weight: 700; letter-spacing: -0.64px; }
    .students-header p { margin: 4px 0 0; color: #73777f; font-size: 14px; line-height: 1.5; }
    .students-primary-button { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; box-shadow: 0 1px 2px rgba(0, 84, 203, 0.2); font-size: 14px; line-height: 1; font-weight: 500; letter-spacing: 0.14px; white-space: nowrap; transition: background 0.15s; text-decoration: none; }
    .students-primary-button:hover { background: #004aaf; }
    .students-card { overflow: hidden; border: 1px solid #e5e7eb; border-radius: 12px; background: #fff; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04); }
    .students-card__filters { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 16px 24px; background: #fafbfc; border-bottom: 1px solid #e5e7eb; }
    .students-filter-group { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
    .students-select { position: relative; display: flex; align-items: center; width: 192px; height: 40px; padding: 0 33px 0 13px; border: 1px solid #e5e7eb; border-radius: 8px; background: #fff; color: #43474f; font-size: 14px; line-height: 1; font-weight: 500; cursor: pointer; transition: background 0.15s; }
    .students-select:hover { background: #f9fafb; }
    .students-table-wrap { overflow-x: auto; }
    .students-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .students-table col:nth-child(1) { width: 17%; }
    .students-table col:nth-child(2) { width: 21%; }
    .students-table col:nth-child(3) { width: 18%; }
    .students-table col:nth-child(4) { width: 19%; }
    .students-table col:nth-child(5) { width: 17%; }
    .students-table col:nth-child(6) { width: 8%; }
    .students-table thead th { padding: 12px 24px; border-bottom: 1px solid #e5e7eb; background: #fafbfc; color: #9ca3af; font-size: 11px; line-height: 1; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; text-align: left; vertical-align: middle; white-space: nowrap; }
    .students-table thead th:last-child { text-align: right; }
    .students-table tbody td { padding: 16px 24px; border-bottom: 1px solid #f3f4f6; color: #43474f; font-size: 14px; line-height: 1.5; vertical-align: middle; }
    .students-table tbody tr:last-child td { border-bottom: none; }
    .students-table tbody tr:hover { background: #fafbff; }
    .students-id, .students-date { white-space: nowrap; }
    .students-person { display: flex; align-items: center; gap: 12px; }
    .students-avatar { width: 32px; height: 32px; border-radius: 9999px; display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto; overflow: hidden; }
    .students-avatar--blue { background: #2d6deb; color: #fff; }
    .students-avatar--teal { background: #00423b; color: #13b8a6; }
    .students-avatar--navy { background: #163b65; color: #86a6d6; }
    .students-avatar span { font-size: 12px; line-height: 16px; font-weight: 700; }
    .students-name { color: #0b1c30; font-size: 14px; line-height: 21px; font-weight: 500; white-space: nowrap; }
    .students-chip { display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 9999px; background: #dce9ff; color: #0b1c30; font-size: 12px; line-height: 16px; font-weight: 500; white-space: nowrap; }
    .students-actions { display: flex; gap: 4px; justify-content: flex-end; }
    .students-action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: none; background: none; cursor: pointer; transition: background 0.15s; }
    .students-action-btn:hover { background: #f3f4f6; }
    .students-action-btn svg { width: 16px; height: 16px; }
    .students-action-btn--view svg { color: #2563eb; }
    .students-action-btn--edit svg { color: #6b7280; }
    .students-action-btn--delete svg { color: #ef4444; }
    .students-pagination { display: flex; align-items: center; justify-content: space-between; padding: 14px 24px; border-top: 1px solid #e5e7eb; }
    .students-pagination__info { font-size: 13px; color: #73777f; }
    .students-pagination__controls { display: flex; align-items: center; gap: 4px; }
    .students-pagination__page { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 13px; font-weight: 500; color: #6b7280; cursor: pointer; transition: all 0.15s; text-decoration: none; }
    .students-pagination__page:hover { background: #f3f4f6; }
    .students-pagination__page--active { background: #0054cb; color: #fff; }
</style>

<div class="students-page">
    <section class="students-header" aria-labelledby="students-title">
        <div>
            <h2 id="students-title">Students</h2>
            <p>Manage and track student profiles and counseling progress.</p>
        </div>
        <a href="<?php echo url('/admin/students/create'); ?>" class="students-primary-button">
            <span>Register Student</span>
        </a>
    </section>

    <section class="students-card" aria-label="Student list filters and table">
        <div class="students-card__filters">
            <div class="students-filter-group">
                <form method="GET" action="<?php echo url('/admin/students'); ?>" style="display:flex;gap:16px;flex-wrap:wrap;">
                    <select name="level" class="students-select" onchange="this.form.submit()">
                        <option value="">All Education Levels</option>
                        <option value="Undergraduate" <?php echo ($filters['level'] ?? '') === 'Undergraduate' ? 'selected' : ''; ?>>Undergraduate</option>
                        <option value="Postgraduate" <?php echo ($filters['level'] ?? '') === 'Postgraduate' ? 'selected' : ''; ?>>Postgraduate</option>
                        <option value="High School" <?php echo ($filters['level'] ?? '') === 'High School' ? 'selected' : ''; ?>>High School</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="students-table-wrap">
            <table class="students-table">
                <colgroup><col><col><col><col><col><col></colgroup>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Education Level</th>
                        <th>Assigned Counselor</th>
                        <th>Registered Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($students)): ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="students-id"><?php echo e($student['student_id'] ?? $student['id']); ?></td>
                                <td>
                                    <div class="students-person">
                                        <div class="students-avatar students-avatar--blue">
                                            <span><?php echo e(substr($student['name'], 0, 2)); ?></span>
                                        </div>
                                        <span class="students-name"><?php echo e($student['name']); ?></span>
                                    </div>
                                </td>
                                <td><span class="students-chip"><?php echo e($student['education_level'] ?? '-'); ?></span></td>
                                <td>
                                    <?php if (!empty($student['counselor_name'])): ?>
                                        <?php echo e($student['counselor_name']); ?>
                                    <?php else: ?>
                                        <span style="color:#9ca3af; font-style:italic;">Unassigned</span>
                                    <?php endif; ?>
                                </td>
                                <td class="students-date"><?php echo e($student['created_at'] ?? ''); ?></td>
                                <td>
                                    <div class="students-actions">
                                        <a href="<?php echo url('/admin/students/' . $student['id']); ?>" class="students-action-btn students-action-btn--view" aria-label="View student">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                        </a>
                                        <a href="<?php echo url('/admin/students/' . $student['id'] . '/edit'); ?>" class="students-action-btn students-action-btn--edit" aria-label="Edit student">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                        </a>
                                        <form method="POST" action="<?php echo url('/admin/students/' . $student['id'] . '/delete'); ?>" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                            <button type="submit" class="students-action-btn students-action-btn--delete" aria-label="Delete student">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center;padding:40px;color:#9ca3af;">No students found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="students-pagination">
            <div class="students-pagination__info">Showing <?php echo count($students); ?> of <?php echo e($total); ?> entries</div>
            <div class="students-pagination__controls">
                <?php
                $paginationPage = (int)($filters['page'] ?? 1);
                $totalPages = max(1, ceil($total / 10));
                for ($i = 1; $i <= min($totalPages, 5); $i++): ?>
                    <a href="?page=<?php echo $i; ?>&level=<?php echo e($filters['level'] ?? ''); ?>" class="students-pagination__page <?php echo $i === $paginationPage ? 'students-pagination__page--active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>
    </section>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin-layout.php';