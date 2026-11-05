@props(['create', 'label', 'description' => null])
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="hint">{{ $description }}</div>
    <a href="{{ $create }}" class="btn btn-success">+ {{ $label }}</a>
</div>
