<?php

namespace App\Http\Requests;

use App\Models\Animal;
use App\Models\FeedingStage;
use App\Models\Lot;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validación del animal. El ARETE es su identificación única (como la cédula
 * de una persona): se normaliza y se valida en el servidor.
 */
class AnimalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['tag_number' => strtoupper(trim((string) $this->input('tag_number')))]);
    }

    public function rules(): array
    {
        $animal = $this->route('animal');

        return [
            'tag_number' => [
                'required', 'regex:/^[A-Z]{3}-\d{3,4}$/',
                Rule::unique('animals', 'tag_number')->ignore($animal?->id),
            ],
            'sex' => ['required', Rule::in(Animal::SEXES)],
            'breed_id' => ['required', 'integer', 'exists:breeds,id'],
            'lot_id' => [
                'required', 'integer', 'exists:lots,id',
                // El corral no puede superar su capacidad.
                function (string $attr, $value, Closure $fail) use ($animal) {
                    $lot = Lot::find($value);
                    if (! $lot || ($animal && (int) $animal->lot_id === (int) $value)) {
                        return;
                    }
                    if ($lot->activeAnimals()->count() >= $lot->capacity) {
                        $fail("El lote {$lot->code} está lleno ({$lot->capacity} animales).");
                    }
                },
            ],
            'birth_date' => ['nullable', 'date', 'before:entry_date'],
            'entry_date' => ['required', 'date', 'before_or_equal:today'],
            'entry_weight' => [
                'required', 'numeric', 'between:80,800',
                // El Core necesita una etapa de engorde para calcular la ración.
                function (string $attr, $value, Closure $fail) {
                    if (is_numeric($value) && ! FeedingStage::forWeight((float) $value)) {
                        $fail('No existe una etapa de engorde configurada para ese peso. Revise las etapas.');
                    }
                },
            ],
            'target_weight' => ['required', 'numeric', 'gt:entry_weight', 'max:900'],
            'purchase_cost' => ['required', 'numeric', 'min:0', 'max:20000'],
            'status' => ['sometimes', Rule::in(Animal::STATUSES)],
        ];
    }

    public function messages(): array
    {
        return [
            'tag_number.regex' => 'El arete debe tener el formato AAA-000 (tres letras, guion y 3 o 4 números). Ejemplo: NOV-015.',
            'tag_number.unique' => 'Ya existe un animal registrado con ese arete.',
            'target_weight.gt' => 'El peso objetivo debe ser mayor que el peso de ingreso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'tag_number' => 'arete', 'sex' => 'sexo', 'breed_id' => 'raza', 'lot_id' => 'lote',
            'birth_date' => 'fecha de nacimiento', 'entry_date' => 'fecha de ingreso', 'entry_weight' => 'peso de ingreso',
            'target_weight' => 'peso objetivo', 'purchase_cost' => 'costo de compra', 'status' => 'estado',
        ];
    }
}
