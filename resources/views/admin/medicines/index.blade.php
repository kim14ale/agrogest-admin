@extends('layouts.app')
@section('title', 'Medicamentos')
@section('content')
    <x-index-header :create="route('medicines.create')" label="Nuevo medicamento" description="La dosis por kg y los días de retiro son datos sensibles: definen la dosis exacta y el bloqueo de venta." />
    <div class="card"><div class="card-body">
        <table class="table align-middle">
            <tr><th>Nombre</th><th>Tipo</th><th>Dosis por kg</th><th>Dosis máxima</th><th>Intervalo mínimo</th><th>Retiro</th><th>Stock</th><th></th></tr>
            @foreach ($items as $m)
                <tr><td><strong>{{ $m->name }}</strong></td><td>{{ $m->type }}</td><td>{{ $m->dose_per_kg }} ml/kg</td><td>{{ $m->max_dose }} ml</td>
                    <td>{{ $m->min_interval_days }} días</td><td>{{ $m->withdrawal_days }} días</td><td>{{ number_format($m->stock, 0) }} ml</td>
                    <x-actions :edit="route('medicines.edit', $m)" :destroy="route('medicines.destroy', $m)" /></tr>
            @endforeach
        </table>
        {{ $items->links() }}
    </div></div>
@endsection
