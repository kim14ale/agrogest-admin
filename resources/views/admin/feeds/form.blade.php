@extends('layouts.app')
@section('title', $item->exists ? 'Editar alimento' : 'Nuevo alimento')
@section('content')
    <x-form-card :action="$item->exists ? route('feeds.update', $item) : route('feeds.store')" :method="$item->exists ? 'PUT' : 'POST'" :back="route('feeds.index')" title="Datos del alimento">
        <x-field name="name" label="Nombre" :value="$item->name" />
        <div class="row">
            <div class="col"><x-field name="dry_matter_pct" label="Materia seca (%)" type="number" step="0.01" :value="$item->dry_matter_pct" hint="Entre 5 y 100. Ensilaje ≈ 30 %." /></div>
            <div class="col"><x-field name="cost_per_kg" label="Costo por kg (USD)" type="number" step="0.001" :value="$item->cost_per_kg" /></div>
            <div class="col"><x-field name="stock_kg" label="Stock (kg)" type="number" step="0.01" :value="$item->stock_kg ?? 0" /></div>
        </div>
    </x-form-card>
@endsection
