@extends('layouts.app')
@section('title', 'Etapas de engorde')
@section('content')
    <x-index-header :create="route('stages.create')" label="Nueva etapa" description="Según su peso, cada animal se ubica en una etapa. Los rangos no pueden cruzarse." />
    <div class="card"><div class="card-body">
        <table class="table align-middle">
            <tr><th>Etapa</th><th>Rango de peso</th><th>Consumo diario (MS)</th><th>GDP esperada</th><th>CA objetivo</th><th></th></tr>
            @foreach ($items as $s)
                <tr><td><strong>{{ $s->name }}</strong></td><td>{{ $s->min_weight }} – {{ $s->max_weight }} kg</td><td>{{ $s->intake_pct }} % del peso</td>
                    <td>{{ $s->expected_gdp }} kg/día</td><td>{{ $s->target_fca }}</td>
                    <x-actions :edit="route('stages.edit', $s)" :destroy="route('stages.destroy', $s)" /></tr>
            @endforeach
        </table>
        {{ $items->links() }}
    </div></div>
@endsection
