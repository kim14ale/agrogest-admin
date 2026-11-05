<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicineRequest;
use App\Models\Medicine;

class MedicineController extends Controller
{
    public function index()
    {
        return view('admin.medicines.index', ['items' => Medicine::orderBy('name')->paginate(15)]);
    }

    public function create()
    {
        return view('admin.medicines.form', ['item' => new Medicine(), 'types' => Medicine::TYPES]);
    }

    public function store(MedicineRequest $request)
    {
        Medicine::create($request->validated());

        return redirect()->route('medicines.index')->with('ok', 'Registro creado correctamente.');
    }

    public function edit(Medicine $medicine)
    {
        return view('admin.medicines.form', ['item' => $medicine, 'types' => Medicine::TYPES]);
    }

    public function update(MedicineRequest $request, Medicine $medicine)
    {
        $medicine->update($request->validated());

        return redirect()->route('medicines.index')->with('ok', 'Registro actualizado correctamente.');
    }

    public function destroy(Medicine $medicine)
    {
        try {
            $medicine->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'No se puede eliminar: el registro está siendo usado por otros datos.');
        }

        return redirect()->route('medicines.index')->with('ok', 'Registro eliminado.');
    }
}
