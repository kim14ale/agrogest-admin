@extends('layouts.app')
@section('title', 'Administración del Core')
@section('content')
    <div class="row g-3 mb-4">
        @foreach ($counts as $label => $value)
            <div class="col-md-3">
                <div class="card kpi"><div class="card-body">
                    <div class="text-secondary small">{{ $label }}</div>
                    <div class="v">{{ $value }}</div>
                </div></div>
            </div>
        @endforeach
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card h-100"><div class="card-body">
                <h2 class="h6 fw-semibold">¿Qué alimenta cada catálogo en el Core?</h2>
                <table class="table align-middle mb-0">
                    <tr><th>Catálogo</th><th>Registros</th><th>Lo usa el Core para</th></tr>
                    @php($uses = [
                        'Razas' => 'Clasificar y comparar rendimiento',
                        'Etapas de engorde' => 'Consumo diario, GDP esperada y CA objetivo',
                        'Alimentos' => 'Materia seca y costo de la ración',
                        'Lotes' => 'Repartir el alimento por corral',
                        'Medicamentos' => 'Dosis exacta, período de retiro e ICS',
                        'Animales' => 'Peso de ingreso, costo y peso objetivo',
                    ])
                    @foreach ($catalogs as [$name, $count, $route])
                        <tr>
                            <td><a href="{{ route($route) }}" class="text-success fw-semibold text-decoration-none">{{ $name }}</a></td>
                            <td>{{ $count }}</td>
                            <td class="text-secondary">{{ $uses[$name] }}</td>
                        </tr>
                    @endforeach
                </table>
            </div></div>
        </div>
        <div class="col-lg-5">
            <div class="card h-100"><div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="h6 fw-semibold mb-0">Últimos pesajes</h2>
                    <a href="{{ route('weighings.create') }}" class="btn btn-sm btn-success">+ Pesaje</a>
                </div>
                <table class="table mb-0">
                    <tr><th>Animal</th><th>Lote</th><th>Fecha</th><th>Peso</th></tr>
                    @forelse ($lastWeighings as $w)
                        <tr><td><strong>{{ $w->animal->tag_number }}</strong></td><td>{{ $w->animal->lot->code }}</td><td>{{ $w->weighed_at->format('d/m/Y') }}</td><td>{{ number_format($w->weight_kg, 1) }} kg</td></tr>
                    @empty
                        <tr><td colspan="4" class="text-secondary">Sin pesajes.</td></tr>
                    @endforelse
                </table>
            </div></div>
        </div>
    </div>
@endsection
