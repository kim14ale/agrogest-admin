<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LotRequest;
use App\Models\Lot;

class LotController extends Controller
{
    public function index()
    {
        return view('admin.lots.index', ['items' => Lot::with('feed')->withCount('activeAnimals')->orderBy('code')->paginate(15)]);
    }

    public function create()
    {
        return view('admin.lots.form', ['item' => new Lot(), 'feeds' => \App\Models\Feed::orderBy('name')->get()]);
    }

    public function store(LotRequest $request)
    {
        Lot::create($request->validated());

        return redirect()->route('lots.index')->with('ok', 'Registro creado correctamente.');
    }

    public function edit(Lot $lot)
    {
        return view('admin.lots.form', ['item' => $lot, 'feeds' => \App\Models\Feed::orderBy('name')->get()]);
    }

    public function update(LotRequest $request, Lot $lot)
    {
        $lot->update($request->validated());

        return redirect()->route('lots.index')->with('ok', 'Registro actualizado correctamente.');
    }

    public function destroy(Lot $lot)
    {
        try {
            $lot->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'No se puede eliminar: el registro está siendo usado por otros datos.');
        }

        return redirect()->route('lots.index')->with('ok', 'Registro eliminado.');
    }
}
