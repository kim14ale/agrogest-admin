@extends('layouts.app')
@section('title', 'Lotes')
@section('content')
    <x-index-header :create="route('lots.create')" label="Nuevo lote" description="Corrales donde se agrupan los animales. Cada lote recibe un alimento." />
    <div class="card"><div class="card-body">
        <table class="table align-middle">
            <tr><th>Código</th><th>Nombre</th><th>Alimento</th><th>Ocupación</th><th></th></tr>
            @foreach ($items as $l)
                <tr><td><strong>{{ $l->code }}</strong></td><td>{{ $l->name }}</td><td>{{ $l->feed->name }}</td>
                    <td>{{ $l->active_animals_count }} / {{ $l->capacity }}</td>
                    <x-actions :edit="route('lots.edit', $l)" :destroy="route('lots.destroy', $l)" /></tr>
            @endforeach
        </table>
        {{ $items->links() }}
    </div></div>
@endsection
