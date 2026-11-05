@extends('layouts.app')
@section('title', 'Razas')
@section('content')
    <x-index-header :create="route('breeds.create')" label="Nueva raza" description="Razas de ganado de carne que se engordan en la hacienda." />
    <div class="card"><div class="card-body">
        <table class="table align-middle">
            <tr><th>Nombre</th><th>Descripción</th><th>Animales</th><th></th></tr>
            @foreach ($items as $b)
                <tr><td><strong>{{ $b->name }}</strong></td><td>{{ $b->description }}</td><td>{{ $b->animals_count }}</td>
                    <x-actions :edit="route('breeds.edit', $b)" :destroy="route('breeds.destroy', $b)" /></tr>
            @endforeach
        </table>
        {{ $items->links() }}
    </div></div>
@endsection
