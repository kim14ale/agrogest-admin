@extends('layouts.app')
@section('title', $item->exists ? 'Editar pesaje' : 'Registrar pesaje')
@section('content')
    <x-form-card :action="$item->exists ? route('weighings.update', $item) : route('weighings.store')" :method="$item->exists ? 'PUT' : 'POST'" :back="route('weighings.index')" title="Datos del pesaje">
        <div class="row">
            {{-- Dropdown en cascada: al elegir el LOTE se cargan solo los ANIMALES de ese lote --}}
            <div class="col">
                <x-select name="lot_id" label="1. Lote" :options="$lots->mapWithKeys(fn ($l) => [$l->id => $l->code.' · '.$l->name])" :value="$lotId" placeholder="Seleccione un lote…" />
            </div>
            <div class="col">
                <x-select name="animal_id" label="2. Animal" :options="[]" :value="$item->animal_id" placeholder="Primero elija un lote" data-selected="{{ old('animal_id', $item->animal_id) }}" />
            </div>
        </div>
        <div class="row">
            <div class="col"><x-field name="weighed_at" label="Fecha del pesaje" type="date" :value="$item->weighed_at?->format('Y-m-d')" /></div>
            <div class="col"><x-field name="weight_kg" label="Peso (kg)" type="number" step="0.01" :value="$item->weight_kg" hint="El servidor verifica que el cambio de peso sea biológicamente posible." /></div>
        </div>
    </x-form-card>
@endsection

@push('scripts')
<script>
    // Cascada Lote → Animal: pide al servidor los animales del lote elegido.
    const lotSelect = document.getElementById('lot_id');
    const animalSelect = document.getElementById('animal_id');
    const urlTemplate = @json(route('lots.animals', ['lot' => '__LOT__']));

    async function loadAnimals(lotId, selected = '') {
        animalSelect.innerHTML = '';
        if (!lotId) {
            animalSelect.add(new Option('Primero elija un lote', ''));
            animalSelect.disabled = true;
            return;
        }
        animalSelect.disabled = true;
        animalSelect.add(new Option('Cargando…', ''));
        const res = await fetch(urlTemplate.replace('__LOT__', lotId), { headers: { 'Accept': 'application/json' } });
        const animals = await res.json();
        animalSelect.innerHTML = '';
        animalSelect.add(new Option(animals.length ? 'Seleccione un animal…' : 'Este lote no tiene animales activos', ''));
        animals.forEach(a => animalSelect.add(new Option(a.label, a.id, false, String(a.id) === String(selected))));
        animalSelect.disabled = animals.length === 0;
    }

    lotSelect.addEventListener('change', () => loadAnimals(lotSelect.value));
    loadAnimals(lotSelect.value, animalSelect.dataset.selected);
</script>
@endpush
