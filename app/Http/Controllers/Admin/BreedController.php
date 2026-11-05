<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BreedRequest;
use App\Models\Breed;

class BreedController extends Controller
{
    public function index()
    {
        return view('admin.breeds.index', ['items' => Breed::withCount('animals')->orderBy('name')->paginate(15)]);
    }

    public function create()
    {
        return view('admin.breeds.form', ['item' => new Breed()]);
    }

    public function store(BreedRequest $request)
    {
        Breed::create($request->validated());

        return redirect()->route('breeds.index')->with('ok', 'Registro creado correctamente.');
    }

    public function edit(Breed $breed)
    {
        return view('admin.breeds.form', ['item' => $breed]);
    }

    public function update(BreedRequest $request, Breed $breed)
    {
        $breed->update($request->validated());

        return redirect()->route('breeds.index')->with('ok', 'Registro actualizado correctamente.');
    }

    public function destroy(Breed $breed)
    {
        try {
            $breed->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'No se puede eliminar: el registro está siendo usado por otros datos.');
        }

        return redirect()->route('breeds.index')->with('ok', 'Registro eliminado.');
    }
}
