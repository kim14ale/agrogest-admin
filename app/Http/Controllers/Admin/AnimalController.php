<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnimalRequest;
use App\Models\Animal;
use App\Models\Breed;
use App\Models\Lot;
use App\Services\PerformanceService;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function __construct(private PerformanceService $core) {}

    public function index(Request $request)
    {
        $items = Animal::with(['breed', 'lot', 'weighings'])
            ->when($request->lot_id, fn ($q, $lot) => $q->where('lot_id', $lot))
            ->orderBy('tag_number')->paginate(15)->withQueryString();

        // Datos que el admin alimenta y el Core ya calcula (GDP por regresión lineal).
        $gdp = $items->mapWithKeys(fn ($a) => [$a->id => $this->core->calculateGdp($a)]);

        return view('admin.animals.index', ['items' => $items, 'gdp' => $gdp, 'lots' => Lot::orderBy('code')->get()]);
    }

    public function create()
    {
        return view('admin.animals.form', $this->formData(new Animal(['status' => Animal::STATUS_FATTENING])));
    }

    public function store(AnimalRequest $request)
    {
        Animal::create($request->validated() + ['status' => Animal::STATUS_FATTENING]);

        return redirect()->route('animals.index')->with('ok', 'Animal registrado correctamente.');
    }

    public function edit(Animal $animal)
    {
        return view('admin.animals.form', $this->formData($animal));
    }

    public function update(AnimalRequest $request, Animal $animal)
    {
        $animal->update($request->validated());

        return redirect()->route('animals.index')->with('ok', 'Animal actualizado correctamente.');
    }

    public function destroy(Animal $animal)
    {
        try {
            $animal->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'No se puede eliminar: el animal tiene tratamientos o ventas registradas.');
        }

        return redirect()->route('animals.index')->with('ok', 'Animal eliminado.');
    }

    private function formData(Animal $animal): array
    {
        return [
            'item' => $animal,
            'breeds' => Breed::orderBy('name')->get(),
            'lots' => Lot::withCount('activeAnimals')->orderBy('code')->get(),
        ];
    }
}
