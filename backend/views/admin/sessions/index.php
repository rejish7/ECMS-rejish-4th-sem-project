<?php
$assetPath = url('/frontend/assets');
$sessAssetPath = $assetPath . '/images/counselors-dashboard';
ob_start();
?>
<style>
.sessions-page{display:flex;flex-direction:column;gap:24px}
.sessions-header{display:flex;align-items:flex-start;justify-content:space-between;gap:24px}
.sessions-header h2{margin:0;color:#0b1c30;font-size:32px;line-height:1.2;font-weight:700;letter-spacing:-0.64px}
.sessions-header p{margin:4px 0 0;color:#73777f;font-size:14px;line-height:1.5}
.sessions-primary-button{display:inline-flex;align-items:center;gap:8px;height:40px;padding:0 24px;border-radius:8px;background:#0054cb;color:#fff;box-shadow:0 1px 2px rgba(0,84,203,0.2);font-size:14px;line-height:1;font-weight:500;letter-spacing:0.14px;white-space:nowrap;transition:background 0.15s;text-decoration:none}
.sessions-primary-button:hover{background:#004aaf}

.sessions-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.sessions-stat{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;box-shadow:0 1px 2px rgba(0,0,0,0.04)}
.sessions-stat__icon{width:40px;height:40px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px}
.sessions-stat__icon--total{background:#e0edff;color:#0054cb}
.sessions-stat__icon--completed{background:#d1fae5;color:#059669}
.sessions-stat__icon--upcoming{background:#dbeafe;color:#2563eb}
.sessions-stat__icon--cancelled{background:#f3f4f6;color:#6b7280}
.sessions-stat__icon svg{width:20px;height:20px}
.sessions-stat__value{font-size:28px;font-weight:700;color:#0b1c30;line-height:1.2}
.sessions-stat__label{font-size:13px;color:#73777f;margin-top:4px}

.sessions-card{overflow:hidden;border:1px solid #e5e7eb;border-radius:12px;background:#fff;box-shadow:0 1px 2px rgba(0,0,0,0.04)}
.sessions-card__header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid #e5e7eb}
.sessions-card__header h3{font-size:16px;font-weight:700;color:#0b1c30;margin:0}
.sessions-filters{display:flex;gap:12px;align-items:center;flex-wrap:wrap}
.sessions-filters select,.sessions-filters input{height:36px;padding:0 12px;border:1px solid #d0d5dd;border-radius:8px;font-size:13px;color:#344054;background:#fff}
.sessions-filters select:focus,.sessions-filters input:focus{outline:none;border-color:#0054cb}
.sessions-filters button{height:36px;padding:0 16px;border:1px solid #d0d5dd;border-radius:8px;background:#fff;font-size:13px;color:#344054;cursor:pointer}
.sessions-filters button:hover{background:#f9fafb}

.sessions-table-wrap{overflow-x:auto}
.sessions-table{width:100%;border-collapse:collapse;table-layout:fixed}
.sessions-table col:nth-child(1){width:10%}
.sessions-table col:nth-child(2){width:15%}
.sessions-table col:nth-child(3){width:15%}
.sessions-table col:nth-child(4){width:10%}
.sessions-table col:nth-child(5){width:18%}
.sessions-table col:nth-child(6){width:12%}
.sessions-table col:nth-child(7){width:10%}
.sessions-table col:nth-child(8){width:10%}
.sessions-table thead th{padding:12px 24px;border-bottom:1px solid #e5e7eb;background:#fafbfc;color:#9ca3af;font-size:11px;line-height:1;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;text-align:left;vertical-align:middle;white-space:nowrap}
.sessions-table thead th:last-child{text-align:right}
.sessions-table tbody td{padding:16px 24px;border-bottom:1px solid #f3f4f6;color:#43474f;font-size:14px;line-height:1.5;vertical-align:middle}
.sessions-table tbody tr:last-child td{border-bottom:none}
.sessions-table tbody tr:hover{background:#fafbff}

.sessions-id{color:#0054cb;font-weight:600;white-space:nowrap}
.sessions-person{display:flex;align-items:center;gap:12px}
.sessions-avatar{width:32px;height:32px;border-radius:9999px;display:inline-flex;align-items:center;justify-content:center;flex:0 0 auto;overflow:hidden}
.sessions-avatar--blue{background:#2d6deb;color:#fff}
.sessions-avatar--teal{background:#00423b;color:#13b8a6}
.sessions-avatar span{font-size:12px;line-height:16px;font-weight:700}
.sessions-name{color:#0b1c30;font-size:14px;line-height:21px;font-weight:500;white-space:nowrap}
.sessions-chip{display:inline-flex;align-items:center;padding:2px 10px;border-radius:9999px;font-size:12px;line-height:16px;font-weight:500;white-space:nowrap}
.sessions-chip--in-person{background:#dce9ff;color:#0054cb}
.sessions-chip--video{background:#d1fae5;color:#065f46}
.sessions-datetime{white-space:nowrap;font-size:13px}
.sessions-status{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:500}
.sessions-status__dot{width:8px;height:8px;border-radius:9999px;flex-shrink:0}
.sessions-status--completed{color:#059669}.sessions-status--completed .sessions-status__dot{background:#10b981}
.sessions-status--scheduled{color:#2563eb}.sessions-status--scheduled .sessions-status__dot{background:#3b82f6}
.sessions-status--cancelled{color:#6b7280}.sessions-status--cancelled .sessions-status__dot{background:#d1d5db}
.sessions-status--in-progress{color:#d97706}.sessions-status--in-progress .sessions-status__dot{background:#f59e0b}
.sessions-actions{display:flex;gap:4px;justify-content:flex-end}
.sessions-action-btn{width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;border-radius:6px;border:none;background:none;cursor:pointer;transition:background 0.15s;text-decoration:none}
.sessions-action-btn:hover{background:#f3f4f6}
.sessions-action-btn svg{width:16px;height:16px}
.sessions-action-btn--view svg{color:#2563eb}
.sessions-action-btn--edit svg{color:#6b7280}
.sessions-action-btn--delete svg{color:#ef4444}
.sessions-empty{text-align:center;padding:40px 20px;color:#9ca3af;font-size:14px}
.sessions-pagination{display:flex;align-items:center;justify-content:space-between;padding:14px 24px;border-top:1px solid #e5e7eb}
.sessions-pagination__info{font-size:13px;color:#73777f}
.sessions-pagination__controls{display:flex;align-items:center;gap:4px}
.sessions-pagination__page{width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;border-radius:6px;font-size:13px;font-weight:500;color:#6b7280;cursor:pointer;transition:all 0.15s;text-decoration:none}
.sessions-pagination__page:hover{background:#f3f4f6}
.sessions-pagination__page--active{background:#0054cb;color:#fff}
@media(max-width:768px){.sessions-stats{grid-template-columns:repeat(2,1fr)}}
</style>

<div class="sessions-page">
    <section class="sessions-header">
        <div><h2>Counseling Sessions</h2><p>Manage and track all counseling sessions across the system.</p></div>
        <a href="<?php echo url('/admin/sessions/create'); ?>" class="sessions-primary-button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Schedule Session
        </a>
    </section>

    <section class="sessions-stats">
        <div class="sessions-stat">
            <div class="sessions-stat__icon sessions-stat__icon--total">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div class="sessions-stat__value"><?php echo e($stats['total'] ?? 0); ?></div>
            <div class="sessions-stat__label">Total Sessions</div>
        </div>
        <div class="sessions-stat">
            <div class="sessions-stat__icon sessions-stat__icon--upcoming">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div class="sessions-stat__value" style="color:#2563eb;"><?php echo e($stats['upcoming'] ?? 0); ?></div>
            <div class="sessions-stat__label">Upcoming</div>
        </div>
        <div class="sessions-stat">
            <div class="sessions-stat__icon sessions-stat__icon--completed">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="sessions-stat__value" style="color:#059669;"><?php echo e($stats['completed'] ?? 0); ?></div>
            <div class="sessions-stat__label">Completed</div>
        </div>
        <div class="sessions-stat">
            <div class="sessions-stat__icon sessions-stat__icon--cancelled">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
            <div class="sessions-stat__value" style="color:#6b7280;"><?php echo e($stats['cancelled'] ?? 0); ?></div>
            <div class="sessions-stat__label">Cancelled</div>
        </div>
    </section>

    <section class="sessions-card">
        <div class="sessions-card__header">
            <h3>All Sessions</h3>
            <form class="sessions-filters" method="GET" action="<?php echo url('/admin/sessions'); ?>">
                <input type="text" name="search" placeholder="Search sessions..." value="<?php echo e($filters['search'] ?? ''); ?>">
                <select name="status">
                    <option value="">All Status</option>
                    <option value="scheduled" <?php echo ($filters['status'] ?? '') === 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                    <option value="in-progress" <?php echo ($filters['status'] ?? '') === 'in-progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="completed" <?php echo ($filters['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="cancelled" <?php echo ($filters['status'] ?? '') === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
                <select name="counselor_id">
                    <option value="">All Counselors</option>
                    <?php foreach ($counselors as $c): ?>
                        <option value="<?php echo e($c['id']); ?>" <?php echo ($filters['counselor_id'] ?? '') == $c['id'] ? 'selected' : ''; ?>><?php echo e($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>
        <div class="sessions-table-wrap">
            <table class="sessions-table">
                <colgroup><col><col><col><col><col><col><col></colgroup>
                <thead>
                    <tr>
                        <th>Session ID</th>
                        <th>Student</th>
                        <th>Counselor</th>
                        <th>Mode</th>
                        <th>Subject</th>
                        <th>Date &amp; Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($sessions)): ?>
                        <?php foreach ($sessions as $s): ?>
                            <tr>
                                <td><span class="sessions-id"><?php echo e($s['session_id'] ?? $s['id']); ?></span></td>
                                <td>
                                    <div class="sessions-person">
                                        <div class="sessions-avatar sessions-avatar--blue">
                                            <span><?php echo e(substr($s['student_name'] ?? 'S', 0, 2)); ?></span>
                                        </div>
                                        <span class="sessions-name"><?php echo e($s['student_name'] ?? '-'); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="sessions-person">
                                        <div class="sessions-avatar sessions-avatar--teal">
                                            <span><?php echo e(substr($s['counselor_name'] ?? 'C', 0, 2)); ?></span>
                                        </div>
                                        <span class="sessions-name"><?php echo e($s['counselor_name'] ?? '-'); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="sessions-chip sessions-chip--<?php echo e(($s['mode'] ?? '') === 'Video Call' ? 'video' : 'in-person'); ?>">
                                        <?php echo e($s['mode'] ?? '-'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="font-size:13px;color:#43474f;"><?php echo e($s['subject'] ?? '-'); ?></span>
                                </td>
                                <td>
                                    <div class="sessions-datetime">
                                        <?php if (!empty($s['datetime'])): ?>
                                            <?php echo e(date('M d, Y', strtotime($s['datetime']))); ?>
                                            <br><span style="color:#73777f;font-size:12px;"><?php echo e(date('g:i A', strtotime($s['datetime']))); ?></span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="sessions-status sessions-status--<?php echo e($s['status'] ?? 'scheduled'); ?>">
                                        <span class="sessions-status__dot"></span>
                                        <?php echo e(ucfirst(str_replace('-', ' ', $s['status'] ?? '-'))); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="sessions-actions">
                                        <a href="<?php echo url('/admin/sessions/' . $s['id']); ?>" class="sessions-action-btn sessions-action-btn--view" aria-label="View session">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                        </a>
                                        <a href="<?php echo url('/admin/sessions/' . $s['id'] . '/edit'); ?>" class="sessions-action-btn sessions-action-btn--edit" aria-label="Edit session">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                        </a>
                                        <form method="POST" action="<?php echo url('/admin/sessions/' . $s['id'] . '/delete'); ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this session?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="sessions-action-btn sessions-action-btn--delete" aria-label="Delete session">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="sessions-empty">No sessions found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="sessions-pagination">
            <span class="sessions-pagination__info">Showing <?php echo count($sessions); ?> of <?php echo e($total); ?> sessions</span>
            <div class="sessions-pagination__controls">
                <?php
                $paginationPage = (int)($filters['page'] ?? 1);
                $totalPages = max(1, ceil($total / 10));
                for ($i = 1; $i <= min($totalPages, 5); $i++): ?>
                    <a href="?page=<?php echo $i; ?>&status=<?php echo e($filters['status'] ?? ''); ?>&counselor_id=<?php echo e($filters['counselor_id'] ?? ''); ?>&search=<?php echo e($filters['search'] ?? ''); ?>" class="sessions-pagination__page <?php echo $i === $paginationPage ? 'sessions-pagination__page--active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>
    </section>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin-layout.php';
