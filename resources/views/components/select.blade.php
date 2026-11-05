@props(['name', 'label', 'options' => [], 'value' => null, 'placeholder' => 'Seleccione…', 'hint' => null])
{{-- Las claves foráneas NUNCA se escriben a mano: siempre se eligen de un dropdown. --}}
<div class="mb-3">
    <label class="form-label" for="{{ $name }}">{{ $label }}</label>
    <select id="{{ $name }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'form-select' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $key => $text)
            <option value="{{ $key }}" @selected((string) old($name, $value) === (string) $key)>{{ $text }}</option>
        @endforeach
    </select>
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
    @if ($hint)<div class="hint mt-1">{{ $hint }}</div>@endif
</div>
