@extends('layouts.app')
@section('title', 'Pesajes')
@section('content')
    <x-index-header :create="route('weighings.create')" label="Registrar pesaje" description="El peso es el dato sensible del Core: de él dependen la GDP, la ración, la dosis y la venta." />
    <div class="card"><div class="card-body">
        <table class="table align-middle">
            <tr><th>Fecha</th><th>Animal</th><th>Lote</th><th>Peso</th><th>Registrado por</th><th></th></tr>
            @foreach ($items as $w)
                <tr><td>{{ $w->weighed_at->format('d/m/Y') }}</td><td><strong>{{ $w->animal->tag_number }}</strong></td><td>{{ $w->animal->lot->code }}</td>
                    <td><strong>{{ number_format($w->weight_kg, 1) }} kg</strong></td><td>{{ $w->user->full_name }}</td>
                    <x-actions :edit="route('weighings.edit', $w)" :destroy="route('weighings.destroy', $w)" /></tr>
            @endforeach
        </table>
        {{ $items->links() }}
    </div></div>
@endsection
