<?php

namespace App\Http\Requests;

use App\Models\Medicine;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * 🚨 Datos sensibles: dosis y período de retiro. Con ellos el Core calcula la
 * dosis exacta y bloquea la venta de animales con residuos de medicamento.
 */
class MedicineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $needsWithdrawal = in_array($this->input('type'), ['Antibiótico', 'Antiinflamatorio', 'Antiparasitario']);

        return [
            'name' => ['required', 'string', 'max:80', Rule::unique('medicines', 'name')->ignore($this->route('medicine')?->id)],
            'type' => ['required', Rule::in(Medicine::TYPES)],
            'dose_per_kg' => ['required', 'numeric', 'gt:0', 'max:10'],
            'max_dose' => ['required', 'numeric', 'gt:0', 'max:1000', 'gte:dose_per_kg'],
            'min_interval_days' => ['required', 'integer', 'between:1,365'],
            // Antibióticos, antiinflamatorios y antiparasitarios SIEMPRE tienen retiro.
            'withdrawal_days' => ['required', 'integer', 'max:365', $needsWithdrawal ? 'min:1' : 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        $type = mb_strtolower((string) $this->input('type'));

        return [
            'withdrawal_days.min' => "Un medicamento de tipo {$type} debe tener al menos 1 día de retiro: sin este dato el sistema permitiría vender carne con residuos.",
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre', 'type' => 'tipo', 'dose_per_kg' => 'dosis por kg', 'max_dose' => 'dosis máxima',
            'min_interval_days' => 'intervalo mínimo', 'withdrawal_days' => 'días de retiro',
            'unit_cost' => 'costo unitario', 'stock' => 'stock',
        ];
    }
}
