@props(['action', 'method' => 'POST', 'back', 'title'])
<div class="card" style="max-width: 760px">
    <div class="card-body p-4">
        <h2 class="h6 fw-semibold mb-1">{{ $title }}</h2>
        <p class="hint mb-4">Todos los datos se validan en el servidor antes de guardarse en la base de datos.</p>
        {{-- novalidate: el navegador no bloquea el envío, así se demuestra la validación del back-end --}}
        <form method="POST" action="{{ $action }}" novalidate>
            @csrf
            @if ($method !== 'POST') @method($method) @endif
            {{ $slot }}
            <div class="d-flex gap-2 mt-2">
                <button class="btn btn-success px-4">Guardar</button>
                <a href="{{ $back }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
