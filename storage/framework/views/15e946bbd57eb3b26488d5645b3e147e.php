
<div class="topbar">
  <div class="tb-left">
    <div class="tb-title" id="topbar-title"><?php echo e($title ?? 'تكامل'); ?></div>
    <div class="tb-crumb">تكامل / <span id="topbar-crumb"><?php echo e($crumb ?? $title ?? 'تكامل'); ?></span></div>
  </div>
  <div class="tb-right">
    <?php if($showNotif ?? true): ?>
    <div style="position:relative">
      <div class="notif-btn" id="notif-btn" onclick="toggleNotifs()">
        <span class="notif-dot" id="notif-dot" style="display:none"></span>
        <span class="notif-count-badge" id="notif-count-badge" style="display:none"></span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="17" height="17">
          <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0" />
        </svg>
      </div>
    </div>
    <?php endif; ?>
    <div class="tb-user">
      <div>
        <div class="tu-name" id="tu-name"><?php echo e($userName ?? 'مبادرون'); ?></div>
        <div class="tu-role" id="tu-role"><?php echo $userRole ?? 'مسؤول المنصة'; ?></div>
      </div>
      <div class="user-av" id="tu-av"><?php echo e($userAv ?? 'م'); ?></div>
    </div>
  </div>
</div>
<?php /**PATH /home/a-22/Downloads/tkamel-fixed/tkamel/resources/views/layouts/topbar.blade.php ENDPATH**/ ?>