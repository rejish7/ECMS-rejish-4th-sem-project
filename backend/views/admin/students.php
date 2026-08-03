<?php
$pageTitle = 'Students';
$pageDescription = 'Manage and track student profiles and counseling progress.';
$currentPage = 'students';

$assetPath = url('/frontend/assets');
$studentsAssetPath = $assetPath . '/images/students';

$students = [
    [
        'id' => 'STU-2023-089',
        'avatarType' => 'initials',
        'avatarClass' => 'students-avatar--blue',
        'avatarText' => 'AL',
        'name' => 'Alex Lawson',
        'level' => 'Undergraduate',
        'counselor' => 'Sarah Jenkins',
        'date' => 'Oct 12, 2023',
    ],
    [
        'id' => 'STU-2023-102',
        'avatarType' => 'initials',
        'avatarClass' => 'students-avatar--teal',
        'avatarText' => 'MR',
        'name' => 'Maria Rodriguez',
        'level' => 'High School',
        'counselor' => 'Michael Chang',
        'date' => 'Oct 15, 2023',
    ],
    [
        'id' => 'STU-2023-145',
        'avatarType' => 'initials',
        'avatarClass' => 'students-avatar--navy',
        'avatarText' => 'JD',
        'name' => 'James Duncan',
        'level' => 'Postgraduate',
        'counselor' => 'Elena Rostova',
        'date' => 'Oct 18, 2023',
    ],
    [
        'id' => 'STU-2023-210',
        'avatarType' => 'image',
        'avatarImage' => 'student-profile.jpg',
        'name' => 'Chloe Kim',
        'level' => 'Undergraduate',
        'counselor' => 'Sarah Jenkins',
        'date' => 'Oct 20, 2023',
    ],
];

ob_start();
?>
<style>
    .students-page {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .students-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
    }

    .students-header h2 {
        margin: 0;
        color: #0b1c30;
        font-size: 32px;
        line-height: 1.2;
        font-weight: 700;
        letter-spacing: -0.64px;
    }

    .students-header p {
        margin: 4px 0 0;
        color: #73777f;
        font-size: 14px;
        line-height: 1.5;
    }

    .students-primary-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 40px;
        padding: 0 24px;
        border-radius: 8px;
        background: #0054cb;
        color: #fff;
        box-shadow: 0 1px 2px rgba(0, 84, 203, 0.2);
        font-size: 14px;
        line-height: 1;
        font-weight: 500;
        letter-spacing: 0.14px;
        white-space: nowrap;
        transition: background 0.15s;
    }

    .students-primary-button:hover {
        background: #004aaf;
    }

    .students-primary-button img {
        width: 10.5px;
        height: 10.5px;
        flex: 0 0 auto;
    }

    .students-card {
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .students-card__filters {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 24px;
        background: #fafbfc;
        border-bottom: 1px solid #e5e7eb;
    }

    .students-filter-group {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .students-select {
        position: relative;
        display: flex;
        align-items: center;
        width: 192px;
        height: 40px;
        padding: 0 33px 0 13px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
        color: #43474f;
        font-size: 14px;
        line-height: 1;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.15s;
    }

    .students-select:hover {
        background: #f9fafb;
    }

    .students-select__icon {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }

    .students-select__icon img {
        width: 10px;
        height: 6.167px;
    }

    .students-more-filters {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 40px;
        padding: 0 17px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
        color: #43474f;
        font-size: 14px;
        line-height: 1;
        font-weight: 500;
        white-space: nowrap;
        cursor: pointer;
        transition: background 0.15s;
    }

    .students-more-filters:hover {
        background: #f9fafb;
    }

    .students-more-filters img {
        width: 13.5px;
        height: 9px;
        flex: 0 0 auto;
    }

    .students-table-wrap {
        overflow-x: auto;
    }

    .students-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .students-table col:nth-child(1) { width: 17%; }
    .students-table col:nth-child(2) { width: 21%; }
    .students-table col:nth-child(3) { width: 18%; }
    .students-table col:nth-child(4) { width: 19%; }
    .students-table col:nth-child(5) { width: 17%; }
    .students-table col:nth-child(6) { width: 8%; }

    .students-table thead th {
        padding: 12px 24px;
        border-bottom: 1px solid #e5e7eb;
        background: #fafbfc;
        color: #9ca3af;
        font-size: 11px;
        line-height: 1;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        text-align: left;
        vertical-align: middle;
        white-space: nowrap;
    }

    .students-table thead th:last-child {
        text-align: right;
    }

    .students-table tbody td {
        padding: 16px 24px;
        border-bottom: 1px solid #f3f4f6;
        color: #43474f;
        font-size: 14px;
        line-height: 1.5;
        vertical-align: middle;
    }

    .students-table tbody tr:last-child td {
        border-bottom: none;
    }

    .students-table tbody tr:hover {
        background: #fafbff;
    }

    .students-id,
    .students-date {
        white-space: nowrap;
    }

    .students-person {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .students-avatar {
        width: 32px;
        height: 32px;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        overflow: hidden;
    }

    .students-avatar--blue {
        background: #2d6deb;
        color: #fff;
    }

    .students-avatar--teal {
        background: #00423b;
        color: #13b8a6;
    }

    .students-avatar--navy {
        background: #163b65;
        color: #86a6d6;
    }

    .students-avatar--image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .students-avatar span {
        font-size: 12px;
        line-height: 16px;
        font-weight: 700;
    }

    .students-name {
        color: #0b1c30;
        font-size: 14px;
        line-height: 21px;
        font-weight: 500;
        white-space: nowrap;
    }

    .students-chip {
        display: inline-flex;
        align-items: center;
        padding: 2px 10px;
        border-radius: 9999px;
        background: #dce9ff;
        color: #0b1c30;
        font-size: 12px;
        line-height: 16px;
        font-weight: 500;
        white-space: nowrap;
    }

    .students-actions {
        display: flex;
        gap: 4px;
        justify-content: flex-end;
    }

    .students-action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: none;
        background: none;
        cursor: pointer;
        transition: background 0.15s;
    }

    .students-action-btn:hover {
        background: #f3f4f6;
    }

    .students-action-btn svg {
        width: 16px;
        height: 16px;
    }

    .students-action-btn--view svg { color: #2563eb; }
    .students-action-btn--edit svg { color: #6b7280; }
    .students-action-btn--delete svg { color: #ef4444; }

    .students-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 24px;
        border-top: 1px solid #e5e7eb;
    }

    .students-pagination__info {
        font-size: 13px;
        color: #73777f;
    }

    .students-pagination__controls {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .students-pagination__arrow {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        color: #9ca3af;
        cursor: pointer;
        transition: background 0.15s;
    }

    .students-pagination__arrow:hover {
        background: #f3f4f6;
    }

    .students-pagination__arrow:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .students-pagination__arrow svg {
        width: 14px;
        height: 14px;
    }

    .students-pagination__page {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.15s;
    }

    .students-pagination__page:hover {
        background: #f3f4f6;
    }

    .students-pagination__page--active {
        background: #0054cb;
        color: #fff;
    }

    .students-pagination__page--active:hover {
        background: #004aaf;
    }

    .students-pagination__ellipsis {
        color: #9ca3af;
        font-size: 14px;
        padding: 0 4px;
    }

    @media (max-width: 980px) {
        .students-header,
        .students-card__filters,
        .students-pagination {
            flex-direction: column;
            align-items: stretch;
        }

        .students-filter-group,
        .students-pagination__controls {
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .students-select {
            width: min(100%, 320px);
        }
    }
</style>

<div class="students-page">
    <section class="students-header" aria-labelledby="students-title">
        <div>
            <h2 id="students-title">Students</h2>
            <p>Manage and track student profiles and counseling progress.</p>
        </div>

        <button type="button" class="students-primary-button">
            <img src="<?php echo e($studentsAssetPath . '/register-student-icon.svg'); ?>" alt="">
            <span>Register Student</span>
        </button>
    </section>

    <section class="students-card" aria-label="Student list filters and table">
        <div class="students-card__filters">
            <div class="students-filter-group">
                <button type="button" class="students-select" aria-label="Filter by education level">
                    <span>All Education Levels</span>
                    <span class="students-select__icon" aria-hidden="true">
                        <img src="<?php echo e($studentsAssetPath . '/dropdown-icon.svg'); ?>" alt="">
                    </span>
                </button>

                <button type="button" class="students-select" aria-label="Filter by counselor">
                    <span>All Counselors</span>
                    <span class="students-select__icon" aria-hidden="true">
                        <img src="<?php echo e($studentsAssetPath . '/dropdown-icon.svg'); ?>" alt="">
                    </span>
                </button>
            </div>

            <button type="button" class="students-more-filters">
                <img src="<?php echo e($studentsAssetPath . '/more-filters-icon.svg'); ?>" alt="">
                <span>More Filters</span>
            </button>
        </div>

        <div class="students-table-wrap">
            <table class="students-table">
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Education Level</th>
                        <th>Assigned Counselor</th>
                        <th>Last Session Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td class="students-id"><?php echo e($student['id']); ?></td>
                            <td>
                                <div class="students-person">
                                    <?php if ($student['avatarType'] === 'image'): ?>
                                        <div class="students-avatar students-avatar--image">
                                            <img src="<?php echo e($studentsAssetPath . '/' . $student['avatarImage']); ?>" alt="<?php echo e($student['name']); ?>">
                                        </div>
                                    <?php else: ?>
                                        <div class="students-avatar <?php echo e($student['avatarClass']); ?>">
                                            <span><?php echo e($student['avatarText']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <span class="students-name"><?php echo e($student['name']); ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="students-chip"><?php echo e($student['level']); ?></span>
                            </td>
                            <td><?php echo e($student['counselor']); ?></td>
                            <td class="students-date"><?php echo e($student['date']); ?></td>
                            <td>
                                <div class="students-actions">
                                    <button type="button" class="students-action-btn students-action-btn--view" aria-label="View student">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                    </button>
                                    <button type="button" class="students-action-btn students-action-btn--edit" aria-label="Edit student">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                    </button>
                                    <button type="button" class="students-action-btn students-action-btn--delete" aria-label="Delete student">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="students-pagination">
            <div class="students-pagination__info">Showing 1 to 4 of 124 entries</div>

            <div class="students-pagination__controls" aria-label="Pagination controls">
                <button type="button" class="students-pagination__arrow" disabled>
                    <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3L5 7L9 11"/></svg>
                </button>
                <button type="button" class="students-pagination__page students-pagination__page--active">1</button>
                <button type="button" class="students-pagination__page">2</button>
                <button type="button" class="students-pagination__page">3</button>
                <span class="students-pagination__ellipsis">...</span>
                <button type="button" class="students-pagination__arrow">
                    <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 3L9 7L5 11"/></svg>
                </button>
            </div>
        </div>
    </section>
</div>
<?php
$content = ob_get_clean();

include __DIR__ . '/../layouts/admin-layout.php';