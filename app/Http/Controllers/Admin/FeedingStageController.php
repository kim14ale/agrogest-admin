<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeedingStageRequest;
use App\Models\FeedingStage;

class FeedingStageController extends Controller
{
    public function index()
    {
        return view('admin.stages.index', ['items' => FeedingStage::orderBy('min_weight')->paginate(15)]);
    }

    public function create()
    {
        return view('admin.stages.form', ['item' => new FeedingStage()]);
    }

    public function store(FeedingStageRequest $request)
    {
        FeedingStage::create($request->validated());

        return redirect()->route('stages.index')->with('ok', 'Registro creado correctamente.');
    }

    public function edit(FeedingStage $stage)
    {
        return view('admin.stages.form', ['item' => $stage]);
    }

    public function update(FeedingStageRequest $request, FeedingStage $stage)
    {
        $stage->update($request->validated());

        return redirect()->route('stages.index')->with('ok', 'Registro actualizado correctamente.');
    }

    public function destroy(FeedingStage $stage)
    {
        try {
            $stage->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'No se puede eliminar: el registro está siendo usado por otros datos.');
        }

        return redirect()->route('stages.index')->with('ok', 'Registro eliminado.');
    }
}
