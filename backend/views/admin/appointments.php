<?php
$pageTitle = 'Appointments';
$pageDescription = 'Manage and schedule counseling sessions.';
$currentPage = 'appointments';

$assetPath = url('/frontend/assets');
$imagesPath = $assetPath . '/images/counselors-dashboard';

$calendarMonth = 'October 2023';
$selectedDay = 11;
$appointmentsWithDots = [12, 14];

$appointments = [
    [
        'time' => '09:30',
        'duration' => '45 min',
        'student' => 'Alex Mercer',
        'studentInitials' => 'AM',
        'studentAvatarClass' => 'apt-avatar--blue',
        'counselor' => 'Dr. Sarah Jenkins',
        'counselorInitials' => 'SJ',
        'counselorAvatarClass' => 'apt-counselor-avatar',
        'status' => 'Upcoming',
        'statusTone' => 'upcoming',
    ],
    [
        'time' => '11:00',
        'duration' => '60 min',
        'student' => 'Maya Lin',
        'studentInitials' => 'ML',
        'studentAvatarClass' => 'apt-avatar--orange',
        'counselor' => 'David Cho',
        'counselorInitials' => 'DC',
        'counselorAvatarClass' => 'apt-counselor-avatar',
        'status' => 'In Progress',
        'statusTone' => 'in-progress',
    ],
];

$calendarDays = [
    ['day' => 24, 'grey' => true], ['day' => 25, 'grey' => true], ['day' => 26, 'grey' => true],
    ['day' => 27, 'grey' => true], ['day' => 28, 'grey' => true], ['day' => 29, 'grey' => true],
    ['day' => 30, 'grey' => true],
    ['day' => 1],  ['day' => 2],  ['day' => 3],  ['day' => 4],
    ['day' => 5],  ['day' => 6],  ['day' => 7],
    ['day' => 8],  ['day' => 9],  ['day' => 10], ['day' => 11, 'dot' => false],
    ['day' => 12, 'dot' => true],  ['day' => 13], ['day' => 14, 'dot' => true],
    ['day' => 15],
];

ob_start();
?>
<style>
    .apt-page {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 24px;
        align-items: start;
    }

    /* Calendar */
    .apt-calendar-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .apt-cal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .apt-cal-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0b1c30;
    }

    .apt-cal-nav {
        display: flex;
        gap: 4px;
    }

    .apt-cal-nav-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        color: #6b7280;
        cursor: pointer;
        transition: background 0.15s;
    }

    .apt-cal-nav-btn:hover {
        background: #f3f4f6;
    }

    .apt-cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 2px;
    }

    .apt-cal-dow {
        text-align: center;
        font-size: 12px;
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        padding: 8px 0 12px;
    }

    .apt-cal-day {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 40px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #0b1c30;
        cursor: pointer;
        transition: background 0.15s;
        position: relative;
    }

    .apt-cal-day:hover {
        background: #f3f4f6;
    }

    .apt-cal-day--grey {
        color: #d1d5db;
        cursor: default;
    }

    .apt-cal-day--grey:hover {
        background: transparent;
    }

    .apt-cal-day--selected {
        background: #0054cb;
        color: #fff;
    }

    .apt-cal-day--selected:hover {
        background: #004aaf;
    }

    .apt-cal-day-dot {
        position: absolute;
        bottom: 4px;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #3b82f6;
    }

    /* Today's Load */
    .apt-today-load {
        margin-top: 16px;
        padding: 24px;
        border-radius: 12px;
        background: #e8f0fe;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .apt-today-load__label {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: #0054cb;
        margin-bottom: 4px;
    }

    .apt-today-load__value {
        font-size: 24px;
        font-weight: 700;
        color: #0b1c30;
    }

    .apt-today-load__icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #0054cb;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .apt-today-load__icon svg {
        width: 24px;
        height: 24px;
        color: #fff;
    }

    /* Right column */
    .apt-right {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* Filter bar */
    .apt-filter-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #fff;
        padding: 6px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    .apt-view-tabs {
        display: flex;
        gap: 4px;
    }

    .apt-view-tab {
        height: 36px;
        padding: 0 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.15s;
        display: inline-flex;
        align-items: center;
    }

    .apt-view-tab:hover {
        color: #374151;
        background: #f9fafb;
    }

    .apt-view-tab--active {
        background: #fff;
        color: #0054cb;
        border: 1px solid #0054cb;
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(0,84,203,0.1);
    }

    .apt-view-tab--active:hover {
        background: #f0f5ff;
    }

    .apt-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 36px;
        padding: 0 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #4b5563;
        cursor: pointer;
        transition: background 0.15s;
    }

    .apt-filter-btn:hover {
        background: #f3f4f6;
    }

    .apt-filter-btn svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    /* Day heading */
    .apt-day-heading {
        font-size: 20px;
        font-weight: 700;
        color: #0b1c30;
        margin: 8px 0 0;
    }

    /* Appointment cards */
    .apt-cards {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .apt-card {
        display: grid;
        grid-template-columns: 100px 1fr;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }

    .apt-card__time {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 2px;
        border-right: 3px solid #0054cb;
        background: #fafbff;
        padding: 20px 16px;
    }

    .apt-card__time-value {
        font-size: 24px;
        font-weight: 700;
        color: #0b1c30;
        line-height: 1;
    }

    .apt-card__time-duration {
        font-size: 12px;
        font-weight: 500;
        color: #73777f;
        margin-top: 4px;
    }

    .apt-card__details {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 16px 24px;
    }

    .apt-person {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .apt-avatar {
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

    .apt-avatar--blue { background: #2d6deb; color: #fff; }
    .apt-avatar--orange { background: #ea580c; color: #fff; }

    .apt-person__text {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .apt-person__label {
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: #9ca3af;
        line-height: 1.4;
    }

    .apt-person__name {
        font-size: 16px;
        font-weight: 700;
        color: #0b1c30;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .apt-counselor-icon {
        width: 32px;
        height: 32px;
        border-radius: 9999px;
        background: #e8f0fe;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
    }

    .apt-counselor-icon svg {
        width: 16px;
        height: 16px;
        color: #0054cb;
    }

    .apt-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 13px;
        font-weight: 500;
        white-space: nowrap;
    }

    .apt-status-badge__dot {
        width: 7px;
        height: 7px;
        border-radius: 9999px;
        flex-shrink: 0;
    }

    .apt-status-badge--upcoming {
        background: #dbeafe;
        color: #1d4ed8;
    }
    .apt-status-badge--upcoming .apt-status-badge__dot { background: #3b82f6; }

    .apt-status-badge--in-progress {
        background: #d1fae5;
        color: #047857;
    }
    .apt-status-badge--in-progress .apt-status-badge__dot { background: #10b981; }

    .apt-status-badge--completed {
        background: #d1fae5;
        color: #059669;
    }
    .apt-status-badge--completed .apt-status-badge__dot { background: #10b981; }

    .apt-status-badge--cancelled {
        background: #f3f4f6;
        color: #6b7280;
    }
    .apt-status-badge--cancelled .apt-status-badge__dot { background: #d1d5db; }

    @media (max-width: 1024px) {
        .apt-page {
            grid-template-columns: 1fr;
        }
        .apt-card__details {
            flex-wrap: wrap;
            gap: 12px;
        }
    }
</style>

<div class="apt-page">
    <aside>
        <div class="apt-calendar-card">
            <div class="apt-cal-header">
                <h3><?php echo e($calendarMonth); ?></h3>
                <div class="apt-cal-nav">
                    <button type="button" class="apt-cal-nav-btn" aria-label="Previous month">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <button type="button" class="apt-cal-nav-btn" aria-label="Next month">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 12L10 8L6 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
            </div>

            <div class="apt-cal-grid">
                <?php foreach (['Su','Mo','Tu','We','Th','Fr','Sa'] as $dow): ?>
                    <div class="apt-cal-dow"><?php echo e($dow); ?></div>
                <?php endforeach; ?>

                <?php foreach ($calendarDays as $day): ?>
                    <?php
                    $classes = ['apt-cal-day'];
                    if ($day['grey']) $classes[] = 'apt-cal-day--grey';
                    if ($day['day'] === $selectedDay) $classes[] = 'apt-cal-day--selected';
                    ?>
                    <div class="<?php echo e(implode(' ', $classes)); ?>">
                        <?php echo e($day['day']); ?>
                        <?php if (!empty($day['dot'])): ?>
                            <span class="apt-cal-day-dot"></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="apt-today-load">
            <div>
                <div class="apt-today-load__label">Today's Load</div>
                <div class="apt-today-load__value">4 Appointments</div>
            </div>
            <div class="apt-today-load__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/>
                    <polyline points="16 7 22 7 22 13"/>
                </svg>
            </div>
        </div>
    </aside>

    <div class="apt-right">
        <div class="apt-filter-bar">
            <div class="apt-view-tabs">
                <button type="button" class="apt-view-tab apt-view-tab--active">Day</button>
                <button type="button" class="apt-view-tab">Week</button>
                <button type="button" class="apt-view-tab">Month</button>
            </div>
            <button type="button" class="apt-filter-btn">
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="2" y1="4" x2="14" y2="4"/>
                    <line x1="4" y1="8" x2="12" y2="8"/>
                    <line x1="6" y1="12" x2="10" y2="12"/>
                </svg>
                Filter
            </button>
        </div>

        <h3 class="apt-day-heading">Wednesday, Oct 11</h3>

        <div class="apt-cards">
            <?php foreach ($appointments as $apt): ?>
                <div class="apt-card">
                    <div class="apt-card__time">
                        <div class="apt-card__time-value"><?php echo e($apt['time']); ?></div>
                        <div class="apt-card__time-duration"><?php echo e($apt['duration']); ?></div>
                    </div>
                    <div class="apt-card__details">
                        <div class="apt-person">
                            <div class="apt-avatar <?php echo e($apt['studentAvatarClass']); ?>">
                                <?php echo e($apt['studentInitials']); ?>
                            </div>
                            <div class="apt-person__text">
                                <span class="apt-person__label">Student</span>
                                <span class="apt-person__name"><?php echo e($apt['student']); ?></span>
                            </div>
                        </div>

                        <div class="apt-counselor-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <div class="apt-person__text">
                            <span class="apt-person__label">Counselor</span>
                            <span class="apt-person__name"><?php echo e($apt['counselor']); ?></span>
                        </div>

                        <span class="apt-status-badge apt-status-badge--<?php echo e($apt['statusTone']); ?>">
                            <span class="apt-status-badge__dot"></span>
                            <?php echo e($apt['status']); ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();

include __DIR__ . '/../layouts/admin-layout.php';
