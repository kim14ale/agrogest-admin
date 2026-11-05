<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parameter;
use Illuminate\Http\Request;

class ParameterController extends Controller
{
    /** Reglas por parámetro: cada valor se valida en el servidor. */
    private const RULES = [
        'price_per_kg' => ['required', 'numeric', 'between:0.5,10'],
        'ics_threshold' => ['required', 'numeric', 'between:1,10'],
        'ics_lambda' => ['required', 'numeric', 'between:0.01,0.5'],
        'target_margin' => ['required', 'numeric', 'between:1,100'],
        'max_daily_change_kg' => ['required', 'numeric', 'between:0.5,10'],
    ];

    public function index()
    {
        return view('admin.parameters.index', ['items' => Parameter::orderBy('id')->get()]);
    }

    public function update(Request $request)
    {
        $rules = collect(self::RULES)->mapWithKeys(fn ($r, $k) => ["values.$k" => $r])->all();
        $names = Parameter::pluck('description', 'key')->mapWithKeys(fn ($d, $k) => ["values.$k" => $d])->all();
        $data = $request->validate($rules, [], $names);

        foreach ($data['values'] as $key => $value) {
            Parameter::where('key', $key)->update(['value' => $value]);
        }

        return back()->with('ok', 'Parámetros del Core actualizados.');
    }
}
