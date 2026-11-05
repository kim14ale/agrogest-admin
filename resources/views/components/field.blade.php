@props(['name', 'label', 'type' => 'text', 'value' => null, 'hint' => null, 'step' => null])
<div class="mb-3">
    <label class="form-label" for="{{ $name }}">{{ $label }}</label>
    @if ($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" rows="2" class="form-control @error($name) is-invalid @enderror">{{ old($name, $value) }}</textarea>
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" @if($step) step="{{ $step }}" @endif
               value="{{ old($name, $value) }}" class="form-control @error($name) is-invalid @enderror">
    @endif
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
    @if ($hint)<div class="hint mt-1">{{ $hint }}</div>@endif
</div>
