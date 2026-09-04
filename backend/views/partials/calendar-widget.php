<?php
$calMonth = $calMonth ?? (int)date('m');
$calYear = $calYear ?? (int)date('Y');
$calSelectedDate = $calSelectedDate ?? '';
$calInputName = $calInputName ?? 'datetime';
$calId = $calId ?? 'calendarWidget';
$monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
$dayNames = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
$firstDay = mktime(0, 0, 0, $calMonth, 1, $calYear);
$daysInMonth = (int)date('t', $firstDay);
$startDow = (int)date('w', $firstDay);
$startDow = $startDow === 0 ? 6 : $startDow - 1;
$today = date('Y-m-d');
?>
<div class="cal-wrap" id="<?php echo e($calId); ?>">
    <div class="cal-header">
        <div class="cal-nav">
            <button type="button" onclick="calNav('<?php echo e($calId); ?>', -1)">&lsaquo;</button>
        </div>
        <h4><?php echo e($monthNames[$calMonth - 1] . ' ' . $calYear); ?></h4>
        <div class="cal-nav">
            <button type="button" onclick="calNav('<?php echo e($calId); ?>', 1)">&rsaquo;</button>
        </div>
    </div>
    <div class="cal-grid">
        <?php foreach ($dayNames as $dn): ?>
            <div class="cal-grid__day-name"><?php echo $dn; ?></div>
        <?php endforeach; ?>
        <?php for ($i = 0; $i < $startDow; $i++): ?>
            <div class="cal-grid__day cal-grid__day--empty"></div>
        <?php endfor; ?>
        <?php for ($d = 1; $d <= $daysInMonth; $d++):
            $dateStr = sprintf('%04d-%02d-%02d', $calYear, $calMonth, $d);
            $isToday = $dateStr === $today;
            $isSelected = $dateStr === $calSelectedDate;
            $isPast = $dateStr < $today;
        ?>
            <div class="cal-grid__day<?php echo $isToday ? ' cal-grid__day--today' : ''; ?><?php echo $isSelected ? ' cal-grid__day--selected' : ''; ?><?php echo $isPast ? ' cal-grid__day--disabled' : ''; ?>"
                 data-date="<?php echo e($dateStr); ?>"
                 <?php if (!$isPast): ?>onclick="calSelect('<?php echo e($calId); ?>', '<?php echo e($dateStr); ?>')"<?php endif; ?>>
                <?php echo $d; ?>
            </div>
        <?php endfor; ?>
    </div>
    <?php if ($calSelectedDate): ?>
        <div class="cal-selected-date">Selected: <strong><?php echo e(date('l, M d, Y', strtotime($calSelectedDate))); ?></strong></div>
    <?php endif; ?>
    <input type="hidden" name="<?php echo e($calInputName); ?>" id="<?php echo e($calId); ?>_value" value="<?php echo e($calSelectedDate); ?>">
</div>
<script>
(function() {
    var w = document.getElementById('<?php echo e($calId); ?>');
    if (!w) return;
    var month = <?php echo $calMonth; ?>;
    var year = <?php echo $calYear; ?>;
    window['calState_<?php echo e($calId); ?>'] = { month: month, year: year };

    fetchSessionsForCalendar('<?php echo e($calId); ?>', month, year);
})();

function fetchSessionsForCalendar(calId, month, year) {
    fetch('<?php echo url("/api/sessions"); ?>?month=' + month + '&year=' + year)
        .then(function(r) { return r.json(); })
        .then(function(sessions) {
            var w = document.getElementById(calId);
            w.querySelectorAll('.cal-grid__day--has-session').forEach(function(el) { el.classList.remove('cal-grid__day--has-session'); });
            sessions.forEach(function(s) {
                if (!s.datetime) return;
                var day = s.datetime.substring(8, 10);
                var dayEl = w.querySelector('.cal-grid__day[data-date="' + s.datetime.substring(0, 10) + '"]');
                if (dayEl) dayEl.classList.add('cal-grid__day--has-session');
            });
        });
}

function calNav(calId, dir) {
    var state = window['calState_' + calId];
    state.month += dir;
    if (state.month > 12) { state.month = 1; state.year++; }
    if (state.month < 1) { state.month = 12; state.year--; }
    var monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    var w = document.getElementById(calId);
    w.querySelector('h4').textContent = monthNames[state.month - 1] + ' ' + state.year;
    var today = new Date();
    var todayStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
    var firstDay = new Date(state.year, state.month - 1, 1);
    var daysInMonth = new Date(state.year, state.month, 0).getDate();
    var startDow = firstDay.getDay();
    startDow = startDow === 0 ? 6 : startDow - 1;
    var grid = w.querySelector('.cal-grid');
    var html = '';
    var dayNames = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    dayNames.forEach(function(dn) { html += '<div class="cal-grid__day-name">' + dn + '</div>'; });
    for (var i = 0; i < startDow; i++) { html += '<div class="cal-grid__day cal-grid__day--empty"></div>'; }
    for (var d = 1; d <= daysInMonth; d++) {
        var ds = state.year + '-' + String(state.month).padStart(2, '0') + '-' + String(d).padStart(2, '0');
        var cls = 'cal-grid__day';
        if (ds === todayStr) cls += ' cal-grid__day--today';
        var selected = document.getElementById(calId + '_value').value;
        if (ds === selected) cls += ' cal-grid__day--selected';
        if (ds < todayStr) cls += ' cal-grid__day--disabled';
        var onclick = ds >= todayStr ? "calSelect('" + calId + "','" + ds + "')" : '';
        html += '<div class="' + cls + '" data-date="' + ds + '" onclick="' + onclick + '">' + d + '</div>';
    }
    var existingDays = grid.querySelectorAll('.cal-grid__day, .cal-grid__day--empty, .cal-grid__day-name');
    existingDays.forEach(function(el) { el.remove(); });
    grid.insertAdjacentHTML('beforeend', html);
    fetchSessionsForCalendar(calId, state.month, state.year);
}

function calSelect(calId, dateStr) {
    var w = document.getElementById(calId);
    document.getElementById(calId + '_value').value = dateStr;
    w.querySelectorAll('.cal-grid__day--selected').forEach(function(el) { el.classList.remove('cal-grid__day--selected'); });
    var dayEl = w.querySelector('.cal-grid__day[data-date="' + dateStr + '"]');
    if (dayEl) dayEl.classList.add('cal-grid__day--selected');
    var existing = w.querySelector('.cal-selected-date');
    if (existing) existing.remove();
    var d = new Date(dateStr + 'T12:00:00');
    var options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
    var div = document.createElement('div');
    div.className = 'cal-selected-date';
    div.innerHTML = 'Selected: <strong>' + d.toLocaleDateString('en-US', options) + '</strong>';
    w.appendChild(div);
}
</script>
