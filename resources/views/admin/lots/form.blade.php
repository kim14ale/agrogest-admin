@extends('layouts.app')
@section('title', $item->exists ? 'Editar lote' : 'Nuevo lote')
@section('content')
    <x-form-card :action="$item->exists ? route('lots.update', $item) : route('lots.store')" :method="$item->exists ? 'PUT' : 'POST'" :back="route('lots.index')" title="Datos del lote">
        <div class="row">
            <div class="col-4"><x-field name="code" label="Código" :value="$item->code" hint="Formato L-00" /></div>
            <div class="col-8"><x-field name="name" label="Nombre" :value="$item->name" /></div>
        </div>
        <div class="row">
            <div class="col-8"><x-select name="feed_id" label="Alimento" :options="$feeds->pluck('name', 'id')" :value="$item->feed_id" /></div>
            <div class="col-4"><x-field name="capacity" label="Capacidad" type="number" :value="$item->capacity" /></div>
        </div>
    </x-form-card>
@endsection
