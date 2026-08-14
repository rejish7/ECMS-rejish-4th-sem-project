<?php ob_start(); ?>
<style>
    .cat-page { display: flex; flex-direction: column; gap: 24px; }
    .cat-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; }
    .cat-header h2 { margin: 0; color: #0b1c30; font-size: 32px; font-weight: 700; }
    .cat-header p { margin: 4px 0 0; color: #73777f; font-size: 14px; }
    .cat-actions { display: flex; gap: 10px; }
    .cat-btn { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 20px; border-radius: 8px; font-size: 14px; font-weight: 500; text-decoration: none; cursor: pointer; border: none; }
    .cat-btn--outline { background: #fff; color: #344054; border: 1px solid #d0d5dd; }
    .cat-btn--outline:hover { background: #f9fafb; }
    .cat-btn--primary { background: #0054cb; color: #fff; }
    .cat-btn--primary:hover { background: #004aaf; }

    .cat-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; max-width: 400px; }
    .cat-stat { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; }
    .cat-stat__icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .cat-stat__icon--college { background: #eff6ff; color: #2563eb; }
    .cat-stat__icon--course { background: #f5f3ff; color: #7c3aed; }
    .cat-stat__icon svg { width: 24px; height: 24px; }
    .cat-stat__info h4 { margin: 0; font-size: 28px; font-weight: 700; color: #0b1c30; }
    .cat-stat__info p { margin: 2px 0 0; font-size: 13px; color: #73777f; }
    .cat-stat__info span { color: #059669; font-weight: 600; }

    .cat-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
    .cat-card__header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid #e5e7eb; }
    .cat-card__title { font-size: 18px; font-weight: 700; color: #0b1c30; }
    .cat-card__link { font-size: 14px; color: #0054cb; text-decoration: none; font-weight: 500; }
    .cat-card__link:hover { text-decoration: underline; }

    .cat-table { width: 100%; border-collapse: collapse; }
    .cat-table th { padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: #9ca3af; background: #fafbfc; border-bottom: 1px solid #e5e7eb; }
    .cat-table th:last-child { text-align: right; }
    .cat-table td { padding: 16px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #43474f; }
    .cat-table tbody tr:last-child td { border-bottom: none; }
    .cat-table tbody tr:hover { background: #fafbff; }

    .cat-inst { display: flex; align-items: center; gap: 12px; }
    .cat-inst__avatar { width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; flex-shrink: 0; }
    .cat-inst__avatar--lu { background: #dbeafe; color: #2563eb; }
    .cat-inst__avatar--mu { background: #e5e7eb; color: #6b7280; }
    .cat-inst__avatar--tu { background: #fee2e2; color: #dc2626; }
    .cat-inst__avatar--su { background: #fef3c7; color: #d97706; }
    .cat-inst__avatar--us { background: #d1fae5; color: #059669; }
    .cat-inst__name { font-weight: 600; color: #0b1c30; }
    .cat-inst__code { font-size: 12px; color: #73777f; }

    .cat-badge { display: inline-flex; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 500; }
    .cat-badge--active { background: #d1fae5; color: #059669; }
    .cat-badge--inactive { background: #f3f4f6; color: #6b7280; }
    .cat-badge--review { background: #fef3c7; color: #d97706; }
    .cat-badge--bachelor { background: #dbeafe; color: #1d4ed8; }
    .cat-badge--master { background: #e5e7eb; color: #374151; }
    .cat-badge--diploma { background: #f5f3ff; color: #7c3aed; }
    .cat-badge--phd { background: #fee2e2; color: #dc2626; }

    .cat-actions-cell { display: flex; gap: 4px; justify-content: flex-end; }
    .cat-action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: none; background: none; cursor: pointer; text-decoration: none; }
    .cat-action-btn:hover { background: #f3f4f6; }
    .cat-action-btn svg { width: 16px; height: 16px; color: #6b7280; }

    .cat-empty { text-align: center; padding: 40px 20px; color: #9ca3af; font-size: 14px; }
    .cat-filters { display: flex; gap: 12px; align-items: center; }
    .cat-filters select, .cat-filters input { height: 36px; padding: 0 12px; border: 1px solid #d0d5dd; border-radius: 8px; font-size: 13px; color: #344054; background: #fff; }
    .cat-filters button { height: 36px; padding: 0 16px; border: 1px solid #d0d5dd; border-radius: 8px; background: #fff; font-size: 13px; color: #344054; cursor: pointer; }
</style>
<div class="cat-page">
    <section class="cat-header">
        <div>
            <h2>College & Course Catalog</h2>
            <p>Manage institutional partnerships and academic offerings.</p>
        </div>
        <div class="cat-actions">
            <a href="<?php echo url('/admin/catalog/college/create'); ?>" class="cat-btn cat-btn--outline">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Add College
            </a>
            <a href="<?php echo url('/admin/catalog/course/create'); ?>" class="cat-btn cat-btn--primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Add Course
            </a>
        </div>
    </section>

    <section class="cat-stats">
        <div class="cat-stat">
            <div class="cat-stat__icon cat-stat__icon--college">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" /></svg>
            </div>
            <div class="cat-stat__info">
                <h4><?php echo e($collegeTotal); ?></h4>
                <p>Total Colleges</p>
            </div>
        </div>
        <div class="cat-stat">
            <div class="cat-stat__icon cat-stat__icon--course">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
            </div>
            <div class="cat-stat__info">
                <h4><?php echo e($courseStats['total'] ?? 0); ?></h4>
                <p>Active Courses</p>
                <span><?php echo e($courseStats['active'] ?? 0); ?> active</span>
            </div>
        </div>
    </section>

    <section class="cat-card">
        <div class="cat-card__header">
            <h3 class="cat-card__title">Partner Colleges</h3>
            <a href="<?php echo url('/admin/catalog'); ?>" class="cat-card__link">View All</a>
        </div>
        <?php if (!empty($colleges)): ?>
            <table class="cat-table">
                <thead>
                    <tr>
                        <th>Institution</th>
                        <th>Location</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($colleges as $college): ?>
                        <tr>
                            <td>
                                <div class="cat-inst">
                                    <div class="cat-inst__avatar cat-inst__avatar--<?php echo e(strtolower($college['code'])); ?>">
                                        <?php echo e(substr($college['code'], 0, 2)); ?>
                                    </div>
                                    <div>
                                        <div class="cat-inst__name"><?php echo e($college['name']); ?></div>
                                        <div class="cat-inst__code"><?php echo e(strtolower($college['code'])); ?>.edu</div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($college['city'] ? $college['city'] . ', ' : '') . e($college['country']); ?></td>
                            <td><?php echo e($college['contact_email'] ?? '-'); ?></td>
                            <td><span class="cat-badge cat-badge--<?php echo e($college['status']); ?>"><?php echo e(ucfirst($college['status'])); ?></span></td>
                            <td>
                                <div class="cat-actions-cell">
                                    <a href="<?php echo url('/admin/catalog/college/' . $college['id'] . '/edit'); ?>" class="cat-action-btn"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg></a>
                                    <form method="POST" action="<?php echo url('/admin/catalog/college/' . $college['id'] . '/delete'); ?>" style="display:inline;" onsubmit="return confirm('Are you sure?')"><button type="submit" class="cat-action-btn"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color:#ef4444;"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg></button></form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="cat-empty">No colleges found.</div>
        <?php endif; ?>
    </section>

    <section class="cat-card">
        <div class="cat-card__header">
            <div>
                <h3 class="cat-card__title">Course Offerings</h3>
                <p style="margin:4px 0 0;font-size:13px;color:#73777f;">Detailed list of programs across all partner institutions.</p>
            </div>
            <form class="cat-filters" method="GET" action="<?php echo url('/admin/catalog'); ?>">
                <select name="level">
                    <option value="">All Levels</option>
                    <option value="bachelor" <?php echo ($filters['level'] ?? '') === 'bachelor' ? 'selected' : ''; ?>>Bachelor</option>
                    <option value="master" <?php echo ($filters['level'] ?? '') === 'master' ? 'selected' : ''; ?>>Master</option>
                    <option value="diploma" <?php echo ($filters['level'] ?? '') === 'diploma' ? 'selected' : ''; ?>>Diploma</option>
                    <option value="phd" <?php echo ($filters['level'] ?? '') === 'phd' ? 'selected' : ''; ?>>PhD</option>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>
        <?php if (!empty($courses)): ?>
            <table class="cat-table">
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Institution</th>
                        <th>Level & Duration</th>
                        <th>Requirements</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td>
                                <div>
                                    <div style="font-weight:600;color:#0b1c30;"><?php echo e($course['name']); ?></div>
                                    <div style="font-size:12px;color:#73777f;"><?php echo e($course['code']); ?></div>
                                </div>
                            </td>
                            <td><?php echo e($course['college_name']); ?></td>
                            <td>
                                <div>
                                    <span class="cat-badge cat-badge--<?php echo e($course['level']); ?>"><?php echo e(ucfirst($course['level'])); ?></span>
                                    <div style="font-size:12px;color:#73777f;margin-top:4px;"><?php echo e($course['duration']); ?></div>
                                </div>
                            </td>
                            <td style="max-width:200px;"><?php echo e($course['requirements'] ?? '-'); ?></td>
                            <td><span class="cat-badge cat-badge--<?php echo e($course['status']); ?>"><?php echo e(ucfirst($course['status'])); ?></span></td>
                            <td>
                                <div class="cat-actions-cell">
                                    <a href="<?php echo url('/admin/catalog/course/' . $course['id'] . '/edit'); ?>" class="cat-action-btn"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg></a>
                                    <form method="POST" action="<?php echo url('/admin/catalog/course/' . $course['id'] . '/delete'); ?>" style="display:inline;" onsubmit="return confirm('Are you sure?')"><button type="submit" class="cat-action-btn"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color:#ef4444;"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg></button></form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="cat-empty">No courses found.</div>
        <?php endif; ?>
    </section>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';
