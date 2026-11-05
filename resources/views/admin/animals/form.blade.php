@extends('layouts.app')
@section('title', $item->exists ? 'Editar animal '.$item->tag_number : 'Nuevo animal')
@section('content')
    <x-form-card :action="$item->exists ? route('animals.update', $item) : route('animals.store')" :method="$item->exists ? 'PUT' : 'POST'" :back="route('animals.index')" title="Datos del animal">
        <div class="row">
            <div class="col"><x-field name="tag_number" label="Arete" :value="$item->tag_number" hint="Identificación única. Formato AAA-000, ej. NOV-015." /></div>
            <div class="col"><x-select name="sex" label="Sexo" :options="array_combine(\App\Models\Animal::SEXES, \App\Models\Animal::SEXES)" :value="$item->sex" /></div>
        </div>
        <div class="row">
            {{-- Claves foráneas: se eligen en dropdowns, nunca se escriben a mano --}}
            <div class="col"><x-select name="breed_id" label="Raza" :options="$breeds->pluck('name', 'id')" :value="$item->breed_id" /></div>
            <div class="col"><x-select name="lot_id" label="Lote" :value="$item->lot_id"
                :options="$lots->mapWithKeys(fn ($l) => [$l->id => $l->code.' · '.$l->name.' ('.$l->active_animals_count.'/'.$l->capacity.')'])" hint="Entre paréntesis: ocupación / capacidad." /></div>
        </div>
        <div class="row">
            <div class="col"><x-field name="birth_date" label="Fecha de nacimiento (opcional)" type="date" :value="$item->birth_date?->format('Y-m-d')" /></div>
            <div class="col"><x-field name="entry_date" label="Fecha de ingreso" type="date" :value="$item->entry_date?->format('Y-m-d')" /></div>
        </div>
        <div class="row">
            <div class="col"><x-field name="entry_weight" label="Peso de ingreso (kg)" type="number" step="0.01" :value="$item->entry_weight" /></div>
            <div class="col"><x-field name="target_weight" label="Peso objetivo (kg)" type="number" step="0.01" :value="$item->target_weight" /></div>
            <div class="col"><x-field name="purchase_cost" label="Costo de compra (USD)" type="number" step="0.01" :value="$item->purchase_cost" /></div>
        </div>
        @if ($item->exists)
            <x-select name="status" label="Estado" :options="array_combine(\App\Models\Animal::STATUSES, \App\Models\Animal::STATUSES)" :value="$item->status" placeholder="—" />
        @endif
    </x-form-card>
@endsection
