@extends('layouts.app')
@section('title', 'Parámetros del Core')
@section('content')
    <div class="card" style="max-width: 760px"><div class="card-body p-4">
        <p class="hint">Valores que usan las fórmulas del Core. Se pueden cambiar sin modificar el código; cada valor se valida en el servidor.</p>
        <form method="POST" action="{{ route('parameters.update') }}" novalidate>
            @csrf @method('PUT')
            <table class="table align-middle">
                <tr><th>Parámetro</th><th style="width: 200px">Valor</th></tr>
                @foreach ($items as $p)
                    <tr>
                        <td>{{ $p->description }}<div class="hint">{{ $p->key }}</div></td>
                        <td>
                            <input name="values[{{ $p->key }}]" value="{{ old('values.'.$p->key, $p->value) }}" class="form-control @error('values.'.$p->key) is-invalid @enderror">
                            @error('values.'.$p->key)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </td>
                    </tr>
                @endforeach
            </table>
            <button class="btn btn-success px-4">Guardar parámetros</button>
        </form>
    </div></div>
@endsection
