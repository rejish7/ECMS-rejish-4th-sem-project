<?php
require_once MODEL_PATH . '/Notification.php';
$notifModel = new Notification();
$notifUserId = $_SESSION['user_id'] ?? 0;
$notifUnread = $notifUserId ? $notifModel->getUnreadCount($notifUserId) : 0;
$notifList = $notifUserId ? $notifModel->getByUserId($notifUserId, 15) : [];
?>
<div class="notif-bell" id="notifBell">
    <button type="button" class="icon-button icon-button--topbar" aria-label="Notifications" onclick="toggleNotifPanel()">
        <img src="<?php echo e($topbarImagePath . '/' . $topbarNotificationIcon); ?>" alt="">
        <?php if ($notifUnread > 0): ?>
            <span class="notif-badge" id="notifBadge"><?php echo $notifUnread > 99 ? '99+' : $notifUnread; ?></span>
        <?php endif; ?>
    </button>
    <div class="notif-panel" id="notifPanel" style="display:none;">
        <div class="notif-panel__header">
            <h4>Notifications</h4>
            <?php if ($notifUnread > 0): ?>
                <button type="button" class="notif-mark-all" onclick="markAllRead()">Mark all read</button>
            <?php endif; ?>
        </div>
        <div class="notif-panel__list" id="notifList">
            <?php if (empty($notifList)): ?>
                <div class="notif-empty">No notifications yet.</div>
            <?php else: ?>
                <?php foreach ($notifList as $n): ?>
                    <div class="notif-item <?php echo $n['is_read'] ? '' : 'notif-item--unread'; ?>" data-id="<?php echo e($n['id']); ?>" <?php echo $n['link'] ? 'onclick="markAndGo(' . e($n['id']) . ', \'' . e(url($n['link'])) . '\')"' : 'onclick="markNotifRead(' . e($n['id']) . ')"'; ?>>
                        <div class="notif-item__title"><?php echo e($n['title']); ?></div>
                        <div class="notif-item__msg"><?php echo e($n['message']); ?></div>
                        <div class="notif-item__time"><?php echo e(date('M d, g:i A', strtotime($n['created_at']))); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
function toggleNotifPanel() {
    var p = document.getElementById('notifPanel');
    p.style.display = p.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('click', function(e) {
    var bell = document.getElementById('notifBell');
    if (bell && !bell.contains(e.target)) {
        document.getElementById('notifPanel').style.display = 'none';
    }
});
function markNotifRead(id) {
    fetch('<?php echo url("/notifications/read"); ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'},
        body: 'id=' + id + '&csrf_token=<?php echo e(csrf_token()); ?>'
    }).then(function() {
        var item = document.querySelector('.notif-item[data-id="' + id + '"]');
        if (item) item.classList.remove('notif-item--unread');
        updateBadge();
    });
}
function markAndGo(id, link) {
    markNotifRead(id);
    window.location.href = link;
}
function markAllRead() {
    fetch('<?php echo url("/notifications/read-all"); ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'},
        body: 'csrf_token=<?php echo e(csrf_token()); ?>'
    }).then(function() {
        document.querySelectorAll('.notif-item--unread').forEach(function(el) { el.classList.remove('notif-item--unread'); });
        updateBadge();
    });
}
function updateBadge() {
    fetch('<?php echo url("/notifications"); ?>')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var badge = document.getElementById('notifBadge');
            if (data.unread_count > 0) {
                if (badge) { badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count; }
                else {
                    var b = document.createElement('span');
                    b.className = 'notif-badge';
                    b.id = 'notifBadge';
                    b.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                    document.querySelector('#notifBell .icon-button').appendChild(b);
                }
            } else if (badge) {
                badge.remove();
            }
        });
}
</script>
