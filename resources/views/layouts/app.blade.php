<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administración') · AgroGest</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body { background: #f4f6f3; color: #1f2d25; }
        .side { width: 230px; min-height: 100vh; background: #14532d; }
        .side .brand { color: #fff; font-weight: 700; font-size: 1.3rem; }
        .side .brand small { display: block; font-size: .75rem; font-weight: 400; color: #9fc3ab; }
        .side .nav-link { color: #d1e7d8; border-radius: 8px; padding: .55rem .8rem; margin-bottom: 2px; }
        .side .nav-link:hover { background: #1a6038; color: #fff; }
        .side .nav-link.active { background: #1f6b3d; color: #fff; font-weight: 600; }
        .side .sec { color: #86ab93; font-size: .7rem; letter-spacing: 1px; text-transform: uppercase; margin: 1rem .8rem .4rem; }
        .topbar { background: #fff; border-bottom: 1px solid #e3e8e1; }
        .card { border: 1px solid #e3e8e1; border-radius: 12px; }
        .btn-success { background: #15803d; border-color: #15803d; }
        .table th { font-size: .8rem; color: #6b7a70; font-weight: 600; background: #f8faf7; }
        .kpi .v { font-size: 1.8rem; font-weight: 700; }
        .form-label { font-size: .9rem; color: #4b5b51; }
        .hint { font-size: .8rem; color: #6b7a70; }
    </style>
</head>
<body>
<div class="d-flex">
    <aside class="side p-3 flex-shrink-0">
        <div class="brand px-2 mb-3">AgroGest<small>Administración · Engorde bovino</small></div>
        @php($r = request()->route()?->getName() ?? '')
        <nav class="nav flex-column">
            <a class="nav-link {{ $r === 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">Inicio</a>
            <div class="sec">Catálogos del Core</div>
            <a class="nav-link {{ str_starts_with($r, 'breeds') ? 'active' : '' }}" href="{{ route('breeds.index') }}">Razas</a>
            <a class="nav-link {{ str_starts_with($r, 'stages') ? 'active' : '' }}" href="{{ route('stages.index') }}">Etapas de engorde</a>
            <a class="nav-link {{ str_starts_with($r, 'feeds') ? 'active' : '' }}" href="{{ route('feeds.index') }}">Alimentos</a>
            <a class="nav-link {{ str_starts_with($r, 'lots') ? 'active' : '' }}" href="{{ route('lots.index') }}">Lotes</a>
            <a class="nav-link {{ str_starts_with($r, 'medicines') ? 'active' : '' }}" href="{{ route('medicines.index') }}">Medicamentos</a>
            <div class="sec">Operación</div>
            <a class="nav-link {{ str_starts_with($r, 'animals') ? 'active' : '' }}" href="{{ route('animals.index') }}">Animales</a>
            <a class="nav-link {{ str_starts_with($r, 'weighings') ? 'active' : '' }}" href="{{ route('weighings.index') }}">Pesajes</a>
            <div class="sec">Configuración</div>
            <a class="nav-link {{ str_starts_with($r, 'parameters') ? 'active' : '' }}" href="{{ route('parameters.index') }}">Parámetros del Core</a>
        </nav>
    </aside>

    <main class="flex-grow-1">
        <div class="topbar d-flex align-items-center justify-content-between px-4 py-3">
            <h1 class="h5 mb-0 fw-semibold">@yield('title')</h1>
            <form method="POST" action="{{ route('logout') }}" class="d-flex align-items-center gap-3">
                @csrf
                <span class="text-secondary"><strong class="text-dark">{{ auth()->user()->full_name }}</strong> · {{ auth()->user()->role->name }}</span>
                <button class="btn btn-sm btn-outline-secondary">Salir</button>
            </form>
        </div>

        <div class="p-4">
            @if (session('ok'))
                <div class="alert alert-success">{{ session('ok') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>El servidor rechazó los datos.</strong> Revise los campos marcados en rojo.
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>
@stack('scripts')
</body>
</html>
