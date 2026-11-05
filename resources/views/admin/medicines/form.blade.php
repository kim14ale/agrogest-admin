@extends('layouts.app')
@section('title', $item->exists ? 'Editar medicamento' : 'Nuevo medicamento')
@section('content')
    <x-form-card :action="$item->exists ? route('medicines.update', $item) : route('medicines.store')" :method="$item->exists ? 'PUT' : 'POST'" :back="route('medicines.index')" title="Datos del medicamento">
        <div class="row">
            <div class="col-7"><x-field name="name" label="Nombre" :value="$item->name" /></div>
            <div class="col-5"><x-select name="type" label="Tipo" :options="array_combine($types, $types)" :value="$item->type" /></div>
        </div>
        <div class="row">
            <div class="col"><x-field name="dose_per_kg" label="Dosis por kg (ml/kg)" type="number" step="0.001" :value="$item->dose_per_kg" /></div>
            <div class="col"><x-field name="max_dose" label="Dosis máxima (ml)" type="number" step="0.01" :value="$item->max_dose" /></div>
        </div>
        <div class="row">
            <div class="col"><x-field name="min_interval_days" label="Intervalo mínimo entre dosis (días)" type="number" :value="$item->min_interval_days" /></div>
            <div class="col"><x-field name="withdrawal_days" label="Días de retiro" type="number" :value="$item->withdrawal_days" hint="Antibióticos, antiinflamatorios y antiparasitarios: mínimo 1 día." /></div>
        </div>
        <div class="row">
            <div class="col"><x-field name="unit_cost" label="Costo por ml (USD)" type="number" step="0.001" :value="$item->unit_cost" /></div>
            <div class="col"><x-field name="stock" label="Stock (ml)" type="number" step="0.01" :value="$item->stock ?? 0" /></div>
        </div>
    </x-form-card>
@endsection
