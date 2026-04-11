<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Elsafa') — PT. Elsafa Tour dan Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<div id="sidebar">
    <div class="sb-brand">
        <div class="b-name">ELSAFA</div>
        <div class="b-sub">Tour dan Travel</div>
    </div>
    <nav class="sb-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('jamaah.index') }}"
                   class="nav-link {{ request()->routeIs('jamaah.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Data Jamaah
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('mitra.index') }}"
                   class="nav-link {{ request()->routeIs('mitra.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> Data Mitra
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('paket.index') }}"
                   class="nav-link {{ request()->routeIs('paket.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i> Paket
                </a>
            </li>
        </ul>
    </nav>
    <div class="sb-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent"
                    style="cursor:pointer;">
                <i class="bi bi-box-arrow-left"></i> Log Out
            </button>
        </form>
    </div>
</div>

<div id="main">
    <div class="topbar">
        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        <div class="admin-badge">
            <i class="bi bi-person-circle"></i>
            <span>Admin</span>
            <i class="bi bi-chevron-down" style="font-size:11px;"></i>
        </div>
    </div>
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2" role="alert" style="font-size:14px;">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert" style="font-size:14px;">
                <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>