<?php
$pageTitle = 'Counseling Sessions';
$pageDescription = 'Manage and track counseling sessions.';
$currentPage = 'sessions';

$assetPath = url('/frontend/assets');
$sessionsAssetPath = $assetPath . '/images/counselors-dashboard';

$stats = [
    ['label' => 'Total Sessions', 'value' => '486', 'tone' => 'blue'],
    ['label' => 'Completed', 'value' => '312', 'tone' => 'green'],
    ['label' => 'Upcoming', 'value' => '128', 'tone' => 'amber'],
    ['label' => 'Cancelled', 'value' => '46', 'tone' => 'red'],
];

$sessions = [
    [
        'id' => 'SES-2024-0117',
        'avatarClass' => 'sessions-avatar--blue',
        'avatarText' => 'AL',
        'student' => 'Alex Lawson',
        'counselor' => 'Sarah Jenkins',
        'mode' => 'In-Person',
        'modeTone' => 'in-person',
        'datetime' => 'Jan 15, 2024 · 10:00 AM',
        'status' => 'Completed',
        'statusTone' => 'completed',
    ],
    [
        'id' => 'SES-2024-0118',
        'avatarClass' => 'sessions-avatar--teal',
        'avatarText' => 'MR',
        'student' => 'Maria Rodriguez',
        'counselor' => 'Michael Chang',
        'mode' => 'Video Call',
        'modeTone' => 'video',
        'datetime' => 'Jan 15, 2024 · 11:30 AM',
        'status' => 'Scheduled',
        'statusTone' => 'scheduled',
    ],
    [
        'id' => 'SES-2024-0119',
        'avatarClass' => 'sessions-avatar--navy',
        'avatarText' => 'JD',
        'student' => 'James Duncan',
        'counselor' => 'Elena Rostova',
        'mode' => 'In-Person',
        'modeTone' => 'in-person',
        'datetime' => 'Jan 15, 2024 · 1:00 PM',
        'status' => 'In Progress',
        'statusTone' => 'in-progress',
    ],
    [
        'id' => 'SES-2024-0120',
        'avatarClass' => 'sessions-avatar--purple',
        'avatarText' => 'CK',
        'student' => 'Chloe Kim',
        'counselor' => 'Sarah Jenkins',
        'mode' => 'Video Call',
        'modeTone' => 'video',
        'datetime' => 'Jan 16, 2024 · 9:00 AM',
        'status' => 'Scheduled',
        'statusTone' => 'scheduled',
    ],
    [
        'id' => 'SES-2024-0121',
        'avatarClass' => 'sessions-avatar--orange',
        'avatarText' => 'RP',
        'student' => 'Ryan Patel',
        'counselor' => 'Marcus Chen',
        'mode' => 'In-Person',
        'modeTone' => 'in-person',
        'datetime' => 'Jan 14, 2024 · 3:30 PM',
        'status' => 'Completed',
        'statusTone' => 'completed',
    ],
    [
        'id' => 'SES-2024-0122',
        'avatarClass' => 'sessions-avatar--green',
        'avatarText' => 'EW',
        'student' => 'Emily Watson',
        'counselor' => 'Michael Chang',
        'mode' => 'Video Call',
        'modeTone' => 'video',
        'datetime' => 'Jan 14, 2024 · 4:00 PM',
        'status' => 'Cancelled',
        'statusTone' => 'cancelled',
    ],
];

ob_start();
?>
<style>
    .sessions-page {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .sessions-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
    }

    .sessions-header h2 {
        margin: 0;
        color: #0b1c30;
        font-size: 32px;
        line-height: 1.2;
        font-weight: 700;
        letter-spacing: -0.64px;
    }

    .sessions-header p {
        margin: 4px 0 0;
        color: #73777f;
        font-size: 14px;
        line-height: 1.5;
    }

    .sessions-primary-button {
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

    .sessions-primary-button:hover {
        background: #004aaf;
    }

    .sessions-primary-button img {
        width: 10.5px;
        height: 10.5px;
        flex: 0 0 auto;
    }

    .sessions-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .sessions-stat-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .sessions-stat-card__icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }

    .sessions-stat-card__icon--blue { background: #e0edff; color: #0054cb; }
    .sessions-stat-card__icon--green { background: #e7f6ef; color: #059669; }
    .sessions-stat-card__icon--amber { background: #fef3e2; color: #d97706; }
    .sessions-stat-card__icon--red { background: #fef2f2; color: #ef4444; }

    .sessions-stat-card__icon svg {
        width: 20px;
        height: 20px;
    }

    .sessions-stat-card__label {
        font-size: 13px;
        color: #73777f;
        margin-bottom: 4px;
    }

    .sessions-stat-card__value {
        font-size: 28px;
        font-weight: 700;
        color: #0b1c30;
        line-height: 1.2;
    }

    .sessions-card {
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .sessions-card__filters {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 24px;
        background: #fafbfc;
        border-bottom: 1px solid #e5e7eb;
    }

    .sessions-filter-group {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .sessions-search {
        position: relative;
        display: flex;
        align-items: center;
        width: 256px;
    }

    .sessions-search img {
        position: absolute;
        left: 12px;
        width: 15px;
        height: 15px;
        pointer-events: none;
    }

    .sessions-search input {
        width: 100%;
        height: 40px;
        padding: 10px 17px 10px 41px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #f9fafb;
        color: #0b1c30;
        font-size: 14px;
        line-height: 1;
        outline: none;
        transition: border-color 0.15s;
    }

    .sessions-search input:focus {
        border-color: #0054cb;
        background: #fff;
    }

    .sessions-search input::placeholder {
        color: #9ca3af;
        opacity: 1;
    }

    .sessions-select {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        width: 192px;
        height: 40px;
        padding: 0 13px;
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

    .sessions-select:hover {
        background: #f9fafb;
    }

    .sessions-select img {
        width: 10px;
        height: 6.167px;
    }

    .sessions-more-filters {
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

    .sessions-more-filters:hover {
        background: #f9fafb;
    }

    .sessions-more-filters img {
        width: 13.5px;
        height: 9px;
        flex: 0 0 auto;
    }

    .sessions-table-wrap {
        overflow-x: auto;
    }

    .sessions-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .sessions-table col:nth-child(1) { width: 14%; }
    .sessions-table col:nth-child(2) { width: 18%; }
    .sessions-table col:nth-child(3) { width: 16%; }
    .sessions-table col:nth-child(4) { width: 12%; }
    .sessions-table col:nth-child(5) { width: 16%; }
    .sessions-table col:nth-child(6) { width: 10%; }
    .sessions-table col:nth-child(7) { width: 14%; }

    .sessions-table thead th {
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

    .sessions-table thead th:last-child {
        text-align: right;
    }

    .sessions-table tbody td {
        padding: 16px 24px;
        border-bottom: 1px solid #f3f4f6;
        color: #43474f;
        font-size: 14px;
        line-height: 1.5;
        vertical-align: middle;
    }

    .sessions-table tbody tr:last-child td {
        border-bottom: none;
    }

    .sessions-table tbody tr:hover {
        background: #fafbff;
    }

    .sessions-id,
    .sessions-date {
        white-space: nowrap;
    }

    .sessions-person {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sessions-avatar {
        width: 32px;
        height: 32px;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        overflow: hidden;
    }

    .sessions-avatar--blue { background: #2d6deb; color: #fff; }
    .sessions-avatar--teal { background: #00423b; color: #13b8a6; }
    .sessions-avatar--navy { background: #163b65; color: #86a6d6; }
    .sessions-avatar--purple { background: #4c1d95; color: #c4b5fd; }
    .sessions-avatar--orange { background: #9a3412; color: #fdba74; }
    .sessions-avatar--green { background: #065f46; color: #6ee7b7; }

    .sessions-avatar span {
        font-size: 12px;
        line-height: 16px;
        font-weight: 700;
    }

    .sessions-name {
        color: #0b1c30;
        font-size: 14px;
        line-height: 21px;
        font-weight: 500;
        white-space: nowrap;
    }

    .sessions-chip {
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

    .sessions-chip--in-person {
        background: #dce9ff;
        color: #0054cb;
    }

    .sessions-chip--video {
        background: #d1fae5;
        color: #065f46;
    }

    .sessions-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        line-height: 20px;
        font-weight: 500;
        white-space: nowrap;
    }

    .sessions-status__dot {
        width: 8px;
        height: 8px;
        border-radius: 9999px;
        flex: 0 0 auto;
    }

    .sessions-status--completed { color: #059669; }
    .sessions-status--completed .sessions-status__dot { background: #10b981; }
    .sessions-status--scheduled { color: #2563eb; }
    .sessions-status--scheduled .sessions-status__dot { background: #3b82f6; }
    .sessions-status--in-progress { color: #d97706; }
    .sessions-status--in-progress .sessions-status__dot { background: #f59e0b; }
    .sessions-status--cancelled { color: #9ca3af; }
    .sessions-status--cancelled .sessions-status__dot { background: #d1d5db; }

    .sessions-actions {
        display: flex;
        gap: 4px;
        justify-content: flex-end;
    }

    .sessions-action-btn {
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

    .sessions-action-btn:hover {
        background: #f3f4f6;
    }

    .sessions-action-btn svg {
        width: 16px;
        height: 16px;
    }

    .sessions-action-btn--view svg { color: #2563eb; }
    .sessions-action-btn--edit svg { color: #6b7280; }
    .sessions-action-btn--delete svg { color: #ef4444; }

    .sessions-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 24px;
        border-top: 1px solid #e5e7eb;
    }

    .sessions-pagination__info {
        font-size: 13px;
        color: #73777f;
    }

    .sessions-pagination__controls {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .sessions-pagination__arrow {
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

    .sessions-pagination__arrow:hover {
        background: #f3f4f6;
    }

    .sessions-pagination__arrow:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .sessions-pagination__arrow svg {
        width: 14px;
        height: 14px;
    }

    .sessions-pagination__page {
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

    .sessions-pagination__page:hover {
        background: #f3f4f6;
    }

    .sessions-pagination__page--active {
        background: #0054cb;
        color: #fff;
    }

    .sessions-pagination__page--active:hover {
        background: #004aaf;
    }

    .sessions-pagination__ellipsis {
        color: #9ca3af;
        font-size: 14px;
        padding: 0 4px;
    }

    @media (max-width: 980px) {
        .sessions-header,
        .sessions-card__filters,
        .sessions-pagination {
            flex-direction: column;
            align-items: stretch;
        }

        .sessions-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .sessions-filter-group,
        .sessions-pagination__controls {
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .sessions-search,
        .sessions-select {
            width: min(100%, 320px);
        }
    }
</style>

<div class="sessions-page">
    <section class="sessions-header" aria-labelledby="sessions-title">
        <div>
            <h2 id="sessions-title">Counseling Sessions</h2>
            <p>Manage and track counseling sessions.</p>
        </div>

        <button type="button" class="sessions-primary-button">
            <img src="<?php echo e($assetPath . '/images/user-management/add-user-icon.svg'); ?>" alt="">
            <span>Schedule Session</span>
        </button>
    </section>

    <section class="sessions-stats" aria-label="Session statistics">
        <?php foreach ($stats as $stat): ?>
            <article class="sessions-stat-card">
                <div class="sessions-stat-card__icon sessions-stat-card__icon--<?php echo e($stat['tone']); ?>">
                    <?php if ($stat['tone'] === 'blue'): ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                    <?php elseif ($stat['tone'] === 'green'): ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    <?php elseif ($stat['tone'] === 'amber'): ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    <?php else: ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                    <?php endif; ?>
                </div>
                <div class="sessions-stat-card__label"><?php echo e($stat['label']); ?></div>
                <div class="sessions-stat-card__value"><?php echo e($stat['value']); ?></div>
            </article>
        <?php endforeach; ?>
    </section>

    <section class="sessions-card" aria-label="Session list filters and table">
        <div class="sessions-card__filters">
            <div class="sessions-filter-group">
                <label class="sessions-search" aria-label="Search sessions">
                    <img src="<?php echo e($sessionsAssetPath . '/search-icon.svg'); ?>" alt="">
                    <input type="search" placeholder="Search by student or counselor..." aria-label="Search sessions">
                </label>

                <button type="button" class="sessions-select" aria-label="Filter by counselor">
                    <span>All Counselors</span>
                    <img src="<?php echo e($sessionsAssetPath . '/specialization-dropdown.svg'); ?>" alt="">
                </button>

                <button type="button" class="sessions-select" aria-label="Filter by status">
                    <span>All Statuses</span>
                    <img src="<?php echo e($sessionsAssetPath . '/specialization-dropdown.svg'); ?>" alt="">
                </button>
            </div>

            <button type="button" class="sessions-more-filters">
                <img src="<?php echo e($sessionsAssetPath . '/filter-icon.svg'); ?>" alt="">
                <span>More Filters</span>
            </button>
        </div>

        <div class="sessions-table-wrap">
            <table class="sessions-table">
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th>Session ID</th>
                        <th>Student</th>
                        <th>Counselor</th>
                        <th>Mode</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sessions as $session): ?>
                        <tr>
                            <td class="sessions-id"><?php echo e($session['id']); ?></td>
                            <td>
                                <div class="sessions-person">
                                    <div class="sessions-avatar <?php echo e($session['avatarClass']); ?>">
                                        <span><?php echo e($session['avatarText']); ?></span>
                                    </div>
                                    <span class="sessions-name"><?php echo e($session['student']); ?></span>
                                </div>
                            </td>
                            <td><?php echo e($session['counselor']); ?></td>
                            <td>
                                <span class="sessions-chip sessions-chip--<?php echo e($session['modeTone']); ?>"><?php echo e($session['mode']); ?></span>
                            </td>
                            <td class="sessions-date"><?php echo e($session['datetime']); ?></td>
                            <td>
                                <span class="sessions-status sessions-status--<?php echo e($session['statusTone']); ?>">
                                    <span class="sessions-status__dot"></span>
                                    <?php echo e($session['status']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="sessions-actions">
                                    <button type="button" class="sessions-action-btn sessions-action-btn--view" aria-label="View session">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                    </button>
                                    <button type="button" class="sessions-action-btn sessions-action-btn--edit" aria-label="Edit session">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                    </button>
                                    <button type="button" class="sessions-action-btn sessions-action-btn--delete" aria-label="Delete session">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="sessions-pagination">
            <div class="sessions-pagination__info">Showing 1 to 6 of 486 entries</div>

            <div class="sessions-pagination__controls" aria-label="Pagination controls">
                <button type="button" class="sessions-pagination__arrow" disabled>
                    <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3L5 7L9 11"/></svg>
                </button>
                <button type="button" class="sessions-pagination__page sessions-pagination__page--active">1</button>
                <button type="button" class="sessions-pagination__page">2</button>
                <button type="button" class="sessions-pagination__page">3</button>
                <span class="sessions-pagination__ellipsis">...</span>
                <button type="button" class="sessions-pagination__arrow">
                    <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 3L9 7L5 11"/></svg>
                </button>
            </div>
        </div>
    </section>
</div>
<?php
$content = ob_get_clean();

include __DIR__ . '/../layouts/admin-layout.php';
