<?php
$pageTitle = 'Counselors Management';
$pageDescription = 'Manage educational counselors, their specializations, and availability.';
$currentPage = 'counselors';

$assetPath = url('/frontend/assets');
$imagesPath = $assetPath . '/images/counselors-dashboard';

$counselors = [
    [
        'initials' => 'SJ',
        'avatarClass' => 'cr-avatar--blue',
        'name' => 'Sarah Jenkins',
        'email' => 'sarah.j@ecms.edu',
        'specialization' => 'Undergraduate',
        'assigned' => 45,
        'max' => 50,
        'status' => 'Available',
        'statusTone' => 'available',
    ],
    [
        'initials' => 'MC',
        'avatarClass' => 'cr-avatar--photo',
        'name' => 'Michael Chang',
        'email' => 'm.chang@ecms.edu',
        'specialization' => 'Postgraduate',
        'assigned' => 32,
        'max' => 40,
        'status' => 'In Session',
        'statusTone' => 'in-session',
    ],
    [
        'initials' => 'EP',
        'avatarClass' => 'cr-avatar--green',
        'name' => 'Elena Patterson',
        'email' => 'elena.p@ecms.edu',
        'specialization' => 'Visa Counseling',
        'assigned' => 55,
        'max' => 60,
        'status' => 'Available',
        'statusTone' => 'available',
    ],
];

ob_start();
?>
<style>
    .cr-page {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .cr-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
    }

    .cr-header h2 {
        margin: 0;
        color: #0b1c30;
        font-size: 32px;
        font-weight: 700;
        line-height: 1.2;
        letter-spacing: -0.64px;
    }

    .cr-header p {
        margin: 4px 0 0;
        color: #73777f;
        font-size: 14px;
        line-height: 1.5;
    }

    .cr-btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 40px;
        padding: 0 24px;
        border-radius: 8px;
        background: #0054cb;
        color: #fff;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.15s;
        white-space: nowrap;
        box-shadow: 0 1px 2px rgba(0, 84, 203, 0.2);
    }

    .cr-btn-add:hover {
        background: #004aaf;
    }

    .cr-btn-add svg {
        width: 18px;
        height: 18px;
    }

    /* Stats */
    .cr-stats {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 16px;
    }

    .cr-stat-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }

    .cr-stat-card__header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .cr-stat-card__icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .cr-stat-card__icon--blue { background: #e0edff; color: #0054cb; }
    .cr-stat-card__icon--teal { background: #e0f7f4; color: #00897b; }

    .cr-stat-card__icon svg {
        width: 20px;
        height: 20px;
    }

    .cr-stat-card__title {
        font-size: 16px;
        font-weight: 700;
        color: #0b1c30;
    }

    .cr-stat-card__value {
        font-size: 36px;
        font-weight: 700;
        color: #0b1c30;
        line-height: 1.1;
        margin-bottom: 4px;
    }

    .cr-stat-card__note {
        font-size: 13px;
        color: #73777f;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .cr-stat-card__note svg {
        width: 14px;
        height: 14px;
    }

    /* Availability */
    .cr-availability {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .cr-avail-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .cr-avail-row__left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cr-avail-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .cr-avail-dot--available { background: #3b82f6; }
    .cr-avail-dot--offline { background: #9ca3af; }

    .cr-avail-label {
        font-size: 14px;
        color: #43474f;
    }

    .cr-avail-value {
        font-size: 14px;
        font-weight: 600;
        color: #0b1c30;
    }

    .cr-avail-bar {
        width: 100%;
        height: 6px;
        background: #e5e7eb;
        border-radius: 9999px;
        overflow: hidden;
    }

    .cr-avail-bar__fill {
        height: 100%;
        border-radius: 9999px;
        background: #3b82f6;
    }

    /* Table card */
    .cr-table-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        overflow: hidden;
    }

    .cr-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 24px;
        background: #fafbfc;
        border-bottom: 1px solid #e5e7eb;
    }

    .cr-search {
        position: relative;
        display: flex;
        align-items: center;
        width: 320px;
    }

    .cr-search img {
        position: absolute;
        left: 12px;
        width: 16px;
        height: 16px;
        pointer-events: none;
    }

    .cr-search input {
        width: 100%;
        height: 40px;
        padding: 10px 17px 10px 40px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #f9fafb;
        color: #0b1c30;
        font-size: 14px;
        outline: none;
        transition: border-color 0.15s;
    }

    .cr-search input::placeholder {
        color: #9ca3af;
    }

    .cr-search input:focus {
        border-color: #0054cb;
        background: #fff;
    }

    .cr-toolbar-actions {
        display: flex;
        gap: 12px;
    }

    .cr-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 40px;
        padding: 0 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
        color: #43474f;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.15s;
    }

    .cr-filter-btn:hover {
        background: #f9fafb;
    }

    .cr-filter-btn svg {
        width: 16px;
        height: 16px;
    }

    .cr-spec-select {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 40px;
        padding: 0 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
        color: #43474f;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.15s;
    }

    .cr-spec-select:hover {
        background: #f9fafb;
    }

    .cr-spec-select svg {
        width: 14px;
        height: 14px;
    }

    .cr-table-wrap {
        overflow-x: auto;
    }

    .cr-table {
        width: 100%;
        border-collapse: collapse;
    }

    .cr-table th {
        padding: 14px 24px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #9ca3af;
        background: #fafbfc;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .cr-table th:last-child {
        text-align: right;
    }

    .cr-table td {
        padding: 16px 24px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
        color: #43474f;
        vertical-align: middle;
    }

    .cr-table tbody tr:last-child td {
        border-bottom: none;
    }

    .cr-table tbody tr:hover {
        background: #fafbff;
    }

    .cr-person {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cr-avatar {
        width: 40px;
        height: 40px;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
        flex: 0 0 auto;
    }

    .cr-avatar--blue { background: #163b65; color: #86a6d6; }
    .cr-avatar--green { background: #00423b; color: #13b8a6; }
    .cr-avatar--photo {
        background: #e5e7eb;
        overflow: hidden;
    }
    .cr-avatar--photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cr-person__info {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .cr-person__name {
        font-size: 15px;
        font-weight: 600;
        color: #0b1c30;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cr-person__email {
        font-size: 13px;
        color: #73777f;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cr-spec-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 6px;
        background: #e0edff;
        color: #0054cb;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
    }

    .cr-assigned {
        white-space: nowrap;
    }

    .cr-assigned strong {
        font-weight: 600;
        color: #0b1c30;
    }

    .cr-assigned span {
        color: #9ca3af;
    }

    .cr-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 500;
        white-space: nowrap;
    }

    .cr-status__dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .cr-status--available { color: #2563eb; }
    .cr-status--available .cr-status__dot { background: #3b82f6; }
    .cr-status--in-session { color: #dc2626; }
    .cr-status--in-session .cr-status__dot { background: #ef4444; }

    .cr-actions {
        display: flex;
        gap: 4px;
        justify-content: flex-end;
    }

    .cr-action-btn {
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

    .cr-action-btn:hover {
        background: #f3f4f6;
    }

    .cr-action-btn svg {
        width: 16px;
        height: 16px;
    }

    .cr-action-btn--view svg { color: #2563eb; }
    .cr-action-btn--edit svg { color: #6b7280; }
    .cr-action-btn--delete svg { color: #ef4444; }

    .cr-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 24px;
        border-top: 1px solid #e5e7eb;
    }

    .cr-pagination-info {
        font-size: 13px;
        color: #73777f;
    }

    .cr-pagination-pages {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .cr-pagination-arrow {
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

    .cr-pagination-arrow:hover {
        background: #f3f4f6;
    }

    .cr-pagination-arrow:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .cr-pagination-arrow svg {
        width: 14px;
        height: 14px;
    }

    .cr-pagination-page {
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

    .cr-pagination-page:hover {
        background: #f3f4f6;
    }

    .cr-pagination-page--active {
        background: #0054cb;
        color: #fff;
    }

    .cr-pagination-page--active:hover {
        background: #004aaf;
    }

    .cr-pagination-ellipsis {
        color: #9ca3af;
        font-size: 14px;
        padding: 0 4px;
    }

    @media (max-width: 1024px) {
        .cr-stats {
            grid-template-columns: 1fr;
        }
        .cr-toolbar {
            flex-direction: column;
            align-items: stretch;
        }
        .cr-search {
            width: 100%;
        }
    }
</style>

<div class="cr-page">
    <section class="cr-header" aria-labelledby="cr-title">
        <div>
            <h2 id="cr-title">Counselors Management</h2>
            <p>Manage educational counselors, their specializations, and availability.</p>
        </div>
        <button type="button" class="cr-btn-add">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Add Counselor
        </button>
    </section>

    <section class="cr-stats" aria-label="Counselor statistics">
        <article class="cr-stat-card">
            <div class="cr-stat-card__header">
                <div class="cr-stat-card__icon cr-stat-card__icon--blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <span class="cr-stat-card__title">Total Counselors</span>
            </div>
            <div class="cr-stat-card__value">24</div>
            <div class="cr-stat-card__note">
                <svg viewBox="0 0 16 16" fill="none" stroke="#059669" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="14 10 8 4 2 10"/>
                </svg>
                +2 this month
            </div>
        </article>

        <article class="cr-stat-card">
            <div class="cr-stat-card__header">
                <div class="cr-stat-card__icon cr-stat-card__icon--teal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
                <span class="cr-stat-card__title">Active Sessions</span>
            </div>
            <div class="cr-stat-card__value">142</div>
            <div class="cr-stat-card__note">Ongoing consultations</div>
        </article>

        <article class="cr-stat-card">
            <div class="cr-stat-card__title" style="margin-bottom:16px">Availability Overview</div>
            <div class="cr-availability">
                <div>
                    <div class="cr-avail-row">
                        <div class="cr-avail-row__left">
                            <span class="cr-avail-dot cr-avail-dot--available"></span>
                            <span class="cr-avail-label">Available Now</span>
                        </div>
                        <span class="cr-avail-value">18</span>
                    </div>
                    <div class="cr-avail-bar">
                        <div class="cr-avail-bar__fill" style="width:75%"></div>
                    </div>
                </div>
                <div>
                    <div class="cr-avail-row">
                        <div class="cr-avail-row__left">
                            <span class="cr-avail-dot cr-avail-dot--offline"></span>
                            <span class="cr-avail-label">In Session / Offline</span>
                        </div>
                        <span class="cr-avail-value">6</span>
                    </div>
                </div>
            </div>
        </article>
    </section>

    <section class="cr-table-card" aria-label="Counselor list">
        <div class="cr-toolbar">
            <label class="cr-search" aria-label="Search counselors">
                <img src="<?php echo e($imagesPath . '/search-icon.svg'); ?>" alt="">
                <input type="search" placeholder="Search counselors by name or specialization..." aria-label="Search counselors">
            </label>
            <div class="cr-toolbar-actions">
                <button type="button" class="cr-filter-btn">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="2" y1="4" x2="14" y2="4"/>
                        <line x1="4" y1="8" x2="12" y2="8"/>
                        <line x1="6" y1="12" x2="10" y2="12"/>
                    </svg>
                    Filter
                </button>
                <button type="button" class="cr-spec-select">
                    <span>All Specializations</span>
                    <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 5L7 9L11 5"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="cr-table-wrap">
            <table class="cr-table">
                <thead>
                    <tr>
                        <th>Counselor Name</th>
                        <th>Specialization</th>
                        <th>Students Assigned</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($counselors as $c): ?>
                        <tr>
                            <td>
                                <div class="cr-person">
                                    <div class="cr-avatar <?php echo e($c['avatarClass']); ?>">
                                        <?php echo e($c['initials']); ?>
                                    </div>
                                    <div class="cr-person__info">
                                        <span class="cr-person__name"><?php echo e($c['name']); ?></span>
                                        <span class="cr-person__email"><?php echo e($c['email']); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="cr-spec-badge"><?php echo e($c['specialization']); ?></span>
                            </td>
                            <td class="cr-assigned">
                                <strong><?php echo e($c['assigned']); ?></strong> <span>/ <?php echo e($c['max']); ?> max</span>
                            </td>
                            <td>
                                <span class="cr-status cr-status--<?php echo e($c['statusTone']); ?>">
                                    <span class="cr-status__dot"></span>
                                    <?php echo e($c['status']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="cr-actions">
                                    <button type="button" class="cr-action-btn cr-action-btn--view" aria-label="View counselor">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                    </button>
                                    <button type="button" class="cr-action-btn cr-action-btn--edit" aria-label="Edit counselor">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                    </button>
                                    <button type="button" class="cr-action-btn cr-action-btn--delete" aria-label="Delete counselor">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="cr-pagination">
            <span class="cr-pagination-info">Showing 1 to 3 of 24 entries</span>
            <div class="cr-pagination-pages">
                <button type="button" class="cr-pagination-arrow" disabled>
                    <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3L5 7L9 11"/></svg>
                </button>
                <button type="button" class="cr-pagination-page cr-pagination-page--active">1</button>
                <button type="button" class="cr-pagination-page">2</button>
                <button type="button" class="cr-pagination-page">3</button>
                <span class="cr-pagination-ellipsis">...</span>
                <button type="button" class="cr-pagination-arrow">
                    <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 3L9 7L5 11"/></svg>
                </button>
            </div>
        </div>
    </section>
</div>
<?php
$content = ob_get_clean();

include __DIR__ . '/../layouts/admin-layout.php';
