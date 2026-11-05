@props(['edit', 'destroy'])
<td class="text-end text-nowrap">
    <a href="{{ $edit }}" class="btn btn-sm btn-outline-success">Editar</a>
    <form method="POST" action="{{ $destroy }}" class="d-inline" onsubmit="return confirm('¿Eliminar este registro?')">
        @csrf @method('DELETE')
        <button class="btn btn-sm btn-outline-danger">Eliminar</button>
    </form>
</td>
