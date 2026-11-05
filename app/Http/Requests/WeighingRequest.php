<?php

namespace App\Http\Requests;

use App\Models\Animal;
use App\Models\Parameter;
use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * 🚨 DATO SENSIBLE DEL CORE: el PESO.
 *
 * Todo el Core depende del pesaje: la GDP (regresión lineal), la ración ajustada,
 * la dosis exacta de medicamentos, el IEE y la recomendación de venta.
 * Un peso mal ingresado (por ejemplo 3560 en lugar de 356) arruinaría todos los
 * cálculos, por eso se valida AQUÍ, en el servidor, antes de grabarlo en la base
 * de datos. La validación del navegador (JavaScript/HTML) se puede saltar; esta no.
 */
class WeighingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $weighingId = $this->route('weighing')?->id;

        return [
            'lot_id' => ['required', 'integer', 'exists:lots,id'],

            'animal_id' => [
                'required', 'integer', 'exists:animals,id',
                // El animal debe pertenecer al lote elegido y seguir en engorde.
                function (string $attr, $value, Closure $fail) {
                    $animal = Animal::find($value);
                    if (! $animal) {
                        return;
                    }
                    if ((int) $animal->lot_id !== (int) $this->input('lot_id')) {
                        $fail('El animal seleccionado no pertenece al lote elegido.');
                    }
                    if (! in_array($animal->status, [Animal::STATUS_FATTENING, Animal::STATUS_READY])) {
                        $fail("No se puede pesar un animal con estado \"{$animal->status}\".");
                    }
                },
            ],

            'weighed_at' => [
                'required', 'date', 'before_or_equal:today',
                // Un solo pesaje por animal y por día.
                Rule::unique('weighings', 'weighed_at')
                    ->where('animal_id', $this->input('animal_id'))
                    ->ignore($weighingId),
                // No puede ser anterior al ingreso del animal.
                function (string $attr, $value, Closure $fail) {
                    $animal = Animal::find($this->input('animal_id'));
                    if ($animal && Carbon::parse($value)->lt($animal->entry_date)) {
                        $fail('La fecha del pesaje no puede ser anterior al ingreso del animal ('.$animal->entry_date->format('d/m/Y').').');
                    }
                },
            ],

            'weight_kg' => [
                'required', 'numeric', 'between:30,1200',
                // Coherencia biológica: un bovino no sube ni baja más de X kg por día.
                function (string $attr, $value, Closure $fail) use ($weighingId) {
                    $animal = Animal::find($this->input('animal_id'));
                    $date = strtotime((string) $this->input('weighed_at')) ? Carbon::parse($this->input('weighed_at')) : null;
                    if (! $animal || ! $date) {
                        return;
                    }

                    $prev = $animal->weighings()
                        ->where('weighed_at', '<', $date->toDateString())
                        ->when($weighingId, fn ($q) => $q->where('id', '!=', $weighingId))
                        ->reorder('weighed_at', 'desc')
                        ->first();

                    $prevWeight = $prev ? (float) $prev->weight_kg : $animal->entry_weight;
                    $prevDate = $prev ? $prev->weighed_at : $animal->entry_date;
                    $days = max(1, $prevDate->diffInDays($date));
                    $maxPerDay = (float) Parameter::get('max_daily_change_kg', 3);
                    $change = abs($value - $prevWeight) / $days;

                    if ($change > $maxPerDay) {
                        $fail(sprintf(
                            'El peso no es coherente: pasaría de %.1f kg a %.1f kg en %d día(s) (%.2f kg/día). El máximo permitido es %.1f kg/día. Verifique el pesaje.',
                            $prevWeight, $value, $days, $change, $maxPerDay
                        ));
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return ['weighed_at.unique' => 'Este animal ya tiene un pesaje registrado en esa fecha.'];
    }

    public function attributes(): array
    {
        return ['lot_id' => 'lote', 'animal_id' => 'animal', 'weighed_at' => 'fecha del pesaje', 'weight_kg' => 'peso'];
    }
}
