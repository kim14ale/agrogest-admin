@extends('layouts.app')
@section('title', 'Alimentos')
@section('content')
    <x-index-header :create="route('feeds.create')" label="Nuevo alimento" description="La materia seca y el costo se usan para calcular la ración y el costo diario." />
    <div class="card"><div class="card-body">
        <table class="table align-middle">
            <tr><th>Nombre</th><th>Materia seca</th><th>Costo por kg</th><th>Costo por kg de MS</th><th>Stock</th><th></th></tr>
            @foreach ($items as $f)
                <tr><td><strong>{{ $f->name }}</strong></td><td>{{ $f->dry_matter_pct }} %</td><td>${{ number_format($f->cost_per_kg, 3) }}</td>
                    <td>${{ number_format($f->costPerKgDryMatter(), 3) }}</td><td>{{ number_format($f->stock_kg, 0) }} kg</td>
                    <x-actions :edit="route('feeds.edit', $f)" :destroy="route('feeds.destroy', $f)" /></tr>
            @endforeach
        </table>
        {{ $items->links() }}
    </div></div>
@endsection
