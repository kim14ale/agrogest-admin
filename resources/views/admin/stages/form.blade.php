@extends('layouts.app')
@section('title', $item->exists ? 'Editar etapa' : 'Nueva etapa')
@section('content')
    <x-form-card :action="$item->exists ? route('stages.update', $item) : route('stages.store')" :method="$item->exists ? 'PUT' : 'POST'" :back="route('stages.index')" title="Datos de la etapa de engorde">
        <x-field name="name" label="Nombre" :value="$item->name" />
        <div class="row">
            <div class="col"><x-field name="min_weight" label="Peso mínimo (kg)" type="number" step="0.01" :value="$item->min_weight" /></div>
            <div class="col"><x-field name="max_weight" label="Peso máximo (kg)" type="number" step="0.01" :value="$item->max_weight" hint="No puede cruzarse con otra etapa." /></div>
        </div>
        <div class="row">
            <div class="col"><x-field name="intake_pct" label="Consumo diario (% del peso)" type="number" step="0.01" :value="$item->intake_pct" hint="Entre 1 y 5 %." /></div>
            <div class="col"><x-field name="expected_gdp" label="GDP esperada (kg/día)" type="number" step="0.001" :value="$item->expected_gdp" /></div>
            <div class="col"><x-field name="target_fca" label="CA objetivo" type="number" step="0.01" :value="$item->target_fca" /></div>
        </div>
    </x-form-card>
@endsection
