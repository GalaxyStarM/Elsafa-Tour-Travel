<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Dashboard') — PT. Elsafa Tour dan Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        /* ── Sidebar tweaks ── */
        .sb-brand {
            padding: 20px 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sb-logo {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }
        .sb-brand .b-sub {
            font-size: 9px;
            font-weight: 600;
            color: rgba(255,255,255,0.45);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sb-nav { padding: 8px 0; }
        .sb-nav .nav-item { margin: 1px 10px; }
        .sb-nav .nav-link {
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 14px;
            font-weight: 500;
            gap: 12px;
            color: rgba(255,255,255,0.55);
            transition: all 0.15s;
        }
        .sb-nav .nav-link i {
            font-size: 18px;
            width: 22px;
            flex-shrink: 0;
        }
        .sb-nav .nav-link.active {
            background: var(--gold);
            color: var(--navy);
            font-weight: 700;
        }
        .sb-nav .nav-link:hover:not(.active) {
            background: rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.9);
        }

        /* Logout button */
        .sb-footer { padding: 12px 10px 16px; }
        .sb-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.5);
            font-size: 14px;
            font-weight: 500;
            background: none;
            border: none;
            width: 100%;
            padding: 9px 14px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.15s;
            text-align: left;
        }
        .sb-logout i { font-size: 17px; }
        .sb-logout:hover { color: #fff; background: rgba(255,255,255,0.07); }

        /* ── Topbar ── */
        .topbar { height: 64px; padding: 0 32px; }
        .topbar .page-title { font-size: 22px; font-weight: 700; }
        .topbar .admin-badge { gap: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
        .topbar .admin-badge .bi-person-circle { font-size: 28px; color: #adb5bd; }
        .topbar .admin-badge .bi-chevron-down  { font-size: 11px; color: #adb5bd; }

        /* ── Content ── */
        .content { padding: 28px 32px; }
    </style>
    @stack('styles')
</head>
<body>

{{-- ═══════════════ SIDEBAR ═══════════════ --}}
<div id="sidebar">

    {{-- Brand --}}
    <div class="sb-brand">
        <img src="{{ asset('images/elsafa.png') }}" alt="Elsafa" class="sb-logo">
        <div class="b-sub">Tour dan Travel</div>
    </div>

    {{-- Nav --}}
    <nav class="sb-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    Beranda
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('jamaah.index') }}"
                   class="nav-link {{ request()->routeIs('jamaah.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    Data Jamaah
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('mitra.index') }}"
                   class="nav-link {{ request()->routeIs('mitra.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i>
                    Data Mitra
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('paket.index') }}"
                   class="nav-link {{ request()->routeIs('paket.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i>
                    Paket
                </a>
            </li>
        </ul>
    </nav>

    {{-- Logout --}}
    <div class="sb-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sb-logout">
                <i class="bi bi-box-arrow-left"></i>
                Log Out
            </button>
        </form>
    </div>
</div>

{{-- ═══════════════ MAIN ═══════════════ --}}
<div id="main">

    {{-- Topbar --}}
    <div class="topbar">
        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        <div class="admin-badge">
            <i class="bi bi-person-circle"></i>
            <span>{{ Auth::user()->name ?? 'Admin' }}</span>
            <i class="bi bi-chevron-down"></i>
        </div>
    </div>

    {{-- Flash messages --}}
    <div class="px-4 pt-3">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2" role="alert" style="font-size:13.5px">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert" style="font-size:13.5px">
            <i class="bi bi-exclamation-circle-fill me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>