<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión · AgroGest</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body style="background:#14532d" class="d-flex align-items-center justify-content-center min-vh-100">
<div class="card shadow-sm" style="width: 380px; border-radius: 14px">
    <div class="card-body p-4">
        <h1 class="h4 fw-bold mb-0" style="color:#14532d">AgroGest</h1>
        <p class="text-secondary mb-4">Administración del engorde bovino</p>
        <form method="POST" action="{{ url('/login') }}" novalidate>
            @csrf
            <div class="mb-3">
                <label class="form-label">Correo</label>
                <input name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button class="btn w-100 text-white" style="background:#15803d">Ingresar</button>
        </form>
    </div>
</div>
</body>
</html>
