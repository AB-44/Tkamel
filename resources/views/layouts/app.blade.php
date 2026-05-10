<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? 'تكامل' }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @stack('styles')
</head>

<body class="{{ $bodyClass ?? '' }}">
  <div class="{{ $wrapClass ?? 'layout' }}">

    {{-- ══ SIDEBAR ══ --}}
    @yield('sidebar')

    {{-- ══ MAIN ══ --}}
    <div class="main">

      {{-- ══ TOPBAR ══ --}}
      @include('layouts.topbar', [
        'title'     => $topbarTitle ?? ($title ?? 'تكامل'),
        'crumb'     => $topbarCrumb ?? ($title ?? 'تكامل'),
        'showNotif' => $showNotif ?? true,
        'userName'  => $userName  ?? null,
        'userRole'  => $userRole  ?? null,
        'userAv'    => $userAv    ?? null,
      ])

      {{-- ══ PAGE CONTENT ══ --}}
      @yield('content')

    </div>{{-- /main --}}
  </div>{{-- /layout --}}

  {{-- ══ NOTIFICATION PANEL (fixed, positioned via JS) ══ --}}
  @if($showNotif ?? true)
    @include('layouts.notif-panel')
  @endif

  @stack('scripts')
</body>
</html>
