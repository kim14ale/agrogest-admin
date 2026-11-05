<?php

namespace App\Http\Requests;

use App\Models\FeedingStage;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeedingStageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('stage')?->id;

        return [
            'name' => ['required', 'string', 'max:50', Rule::unique('feeding_stages', 'name')->ignore($id)],
            'min_weight' => ['required', 'numeric', 'min:0'],
            'max_weight' => [
                'required', 'numeric', 'gt:min_weight', 'max:1500',
                // Los rangos de peso de las etapas no pueden cruzarse.
                function (string $attr, $value, Closure $fail) use ($id) {
                    $min = (float) $this->input('min_weight');
                    $overlap = FeedingStage::when($id, fn ($q) => $q->where('id', '!=', $id))
                        ->where('min_weight', '<', $value)
                        ->where('max_weight', '>', $min)
                        ->first();
                    if ($overlap) {
                        $fail("El rango se cruza con la etapa \"{$overlap->name}\" ({$overlap->min_weight}–{$overlap->max_weight} kg).");
                    }
                },
            ],
            'intake_pct' => ['required', 'numeric', 'between:1,5'],
            'expected_gdp' => ['required', 'numeric', 'between:0.1,2.5'],
            'target_fca' => ['required', 'numeric', 'between:3,15'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre', 'min_weight' => 'peso mínimo', 'max_weight' => 'peso máximo',
            'intake_pct' => 'consumo diario', 'expected_gdp' => 'GDP esperada', 'target_fca' => 'CA objetivo',
        ];
    }
}
