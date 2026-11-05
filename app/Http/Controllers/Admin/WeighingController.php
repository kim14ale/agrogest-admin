<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WeighingRequest;
use App\Models\Animal;
use App\Models\Lot;
use App\Models\Weighing;

class WeighingController extends Controller
{
    public function index()
    {
        return view('admin.weighings.index', [
            'items' => Weighing::with(['animal.lot', 'user'])->orderByDesc('weighed_at')->orderByDesc('id')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.weighings.form', ['item' => new Weighing(['weighed_at' => now()]), 'lots' => Lot::orderBy('code')->get(), 'lotId' => null]);
    }

    public function store(WeighingRequest $request)
    {
        // lot_id solo sirve para la cascada; no se guarda en weighings.
        Weighing::create($request->safe()->except('lot_id') + ['user_id' => $request->user()->id]);

        return redirect()->route('weighings.index')->with('ok', 'Pesaje registrado. El Core recalculó la GDP del animal.');
    }

    public function edit(Weighing $weighing)
    {
        return view('admin.weighings.form', ['item' => $weighing, 'lots' => Lot::orderBy('code')->get(), 'lotId' => $weighing->animal->lot_id]);
    }

    public function update(WeighingRequest $request, Weighing $weighing)
    {
        $weighing->update($request->safe()->except('lot_id'));

        return redirect()->route('weighings.index')->with('ok', 'Pesaje actualizado.');
    }

    public function destroy(Weighing $weighing)
    {
        $weighing->delete();

        return redirect()->route('weighings.index')->with('ok', 'Pesaje eliminado.');
    }

    /**
     * Dropdown dependiente: devuelve en JSON los animales activos del lote elegido.
     * GET /admin/lots/{lot}/animals
     */
    public function animalsByLot(Lot $lot)
    {
        return $lot->activeAnimals()->orderBy('tag_number')->get()->map(fn (Animal $a) => [
            'id' => $a->id,
            'label' => "{$a->tag_number} · {$a->breed->name} · último peso ".number_format($a->currentWeight(), 1).' kg',
        ]);
    }
}
