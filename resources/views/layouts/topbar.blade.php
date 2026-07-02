{{--
  layouts/topbar.blade.php
  Variables:
    $title      — page title shown in breadcrumb
    $crumb      — breadcrumb text (defaults to $title)
    $showNotif  — whether to show notification bell (default true)
    $userName   — user display name (null = static placeholder)
    $userRole   — role label HTML or text (null = مسؤول المنصة)
    $userAv     — avatar letter (null = م)
    $topbarId   — optional custom id (for JS targeting)
--}}
@php
  $authUser = \Illuminate\Support\Facades\Auth::user();
  $resolvedName = $authUser?->full_name ?? ($userName ?? session('association.name') ?? 'مبادرون');
  $resolvedRole = $userRole ?? (session()->has('association') && !$authUser ? 'جمعية معتمدة' : 'مسؤول المنصة');
  $resolvedAvatarHtml = null;
  if ($authUser && !empty($authUser->avatar_path)) {
    // مستخدم عادي لديه صورة
    $resolvedAvatarHtml = '<img src="' . e(asset('storage/' . $authUser->avatar_path)) . '" alt="avatar">';
  } elseif (!$authUser && session()->has('association')) {
    // جمعية — نجلب الصورة من قاعدة البيانات
    $assocSession = session('association');
    $assocModel = \App\Models\Association::find($assocSession['id'] ?? null);
    if ($assocModel && !empty($assocModel->avatar)) {
      $resolvedAvatarHtml = '<img src="' . e(asset('storage/' . $assocModel->avatar)) . '" alt="avatar">';
    } else {
      $resolvedAvatarHtml = $userAv ?? mb_substr($resolvedName ?? 'م', 0, 1);
    }
  } else {
    $resolvedAvatarHtml = $userAv ?? mb_substr($resolvedName ?? 'م', 0, 1);
  }
@endphp

<style>
  /* Allow avatar circle to show uploaded image */
  .user-av { overflow: hidden !important; position: relative !important; }
  .user-av img { width:100%; height:100%; border-radius:8px; object-fit:cover; display:block; position:absolute; top:0; left:0; }
</style>

<div class="topbar">
  <div class="tb-left">
    <div class="tb-title" id="topbar-title">{{ $title ?? 'تكامل' }}</div>
    <div class="tb-crumb">تكامل / <span id="topbar-crumb">{{ $crumb ?? $title ?? 'تكامل' }}</span></div>
  </div>
  <div class="tb-right">
    @if($showNotif ?? true)
    <div style="position:relative">
      <div class="notif-btn" id="notif-btn" onclick="toggleNotifs()">
        <span class="notif-dot" id="notif-dot" style="display:none"></span>
        <span class="notif-count-badge" id="notif-count-badge" style="display:none"></span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="17" height="17">
          <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0" />
        </svg>
      </div>
    </div>
    @endif
    <div class="tb-user">
      <div>
        <div class="tu-name" id="tu-name">{{ $resolvedName }}</div>
        <div class="tu-role" id="tu-role">{!! $resolvedRole !!}</div>
      </div>
      <div class="user-av" id="tu-av">{!! $resolvedAvatarHtml !!}</div>
    </div>
  </div>
</div>
