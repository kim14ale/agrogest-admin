@extends('layouts.app')
@section('title', 'Animales')
@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <form method="GET" class="d-flex gap-2 align-items-end">
            <div>
                <label class="form-label mb-1">Filtrar por lote</label>
                <select name="lot_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Todos los lotes</option>
                    @foreach ($lots as $l)
                        <option value="{{ $l->id }}" @selected(request('lot_id') == $l->id)>{{ $l->code }} · {{ $l->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <a href="{{ route('animals.create') }}" class="btn btn-success">+ Nuevo animal</a>
    </div>
    <div class="card"><div class="card-body">
        <table class="table align-middle">
            <tr><th>Arete</th><th>Raza</th><th>Lote</th><th>Ingreso</th><th>Peso actual</th><th>Peso objetivo</th><th>GDP (Core)</th><th>Estado</th><th></th></tr>
            @foreach ($items as $a)
                <tr>
                    <td><strong>{{ $a->tag_number }}</strong></td><td>{{ $a->breed->name }}</td><td>{{ $a->lot->code }}</td>
                    <td>{{ $a->entry_date->format('d/m/Y') }} · {{ number_format($a->entry_weight, 0) }} kg</td>
                    <td><strong>{{ number_format($a->currentWeight(), 1) }} kg</strong></td><td>{{ number_format($a->target_weight, 0) }} kg</td>
                    <td>{{ $gdp[$a->id] !== null ? number_format($gdp[$a->id], 2).' kg/día' : 'No disponible' }}</td>
                    <td><span class="badge text-bg-{{ $a->status === 'En engorde' ? 'primary' : ($a->status === 'Listo para venta' ? 'success' : 'secondary') }}">{{ $a->status }}</span></td>
                    <x-actions :edit="route('animals.edit', $a)" :destroy="route('animals.destroy', $a)" />
                </tr>
            @endforeach
        </table>
        <div class="hint">La GDP se calcula por regresión lineal con los pesajes registrados.</div>
        {{ $items->links() }}
    </div></div>
@endsection
