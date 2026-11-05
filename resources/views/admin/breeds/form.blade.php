@extends('layouts.app')
@section('title', $item->exists ? 'Editar raza' : 'Nueva raza')
@section('content')
    <x-form-card :action="$item->exists ? route('breeds.update', $item) : route('breeds.store')" :method="$item->exists ? 'PUT' : 'POST'" :back="route('breeds.index')" title="Datos de la raza">
        <x-field name="name" label="Nombre" :value="$item->name" />
        <x-field name="description" label="Descripción" type="textarea" :value="$item->description" />
    </x-form-card>
@endsection
