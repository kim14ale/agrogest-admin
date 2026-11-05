<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['code' => strtoupper(trim((string) $this->input('code')))]);
    }

    public function rules(): array
    {
        $lot = $this->route('lot');

        return [
            'code' => ['required', 'regex:/^L-\d{2}$/', Rule::unique('lots', 'code')->ignore($lot?->id)],
            'name' => ['required', 'string', 'max:80'],
            'feed_id' => ['required', 'integer', 'exists:feeds,id'],
            'capacity' => [
                'required', 'integer', 'between:1,500',
                function (string $attr, $value, Closure $fail) use ($lot) {
                    if ($lot && $value < $lot->activeAnimals()->count()) {
                        $fail('La capacidad no puede ser menor a los '.$lot->activeAnimals()->count().' animales que hay en el lote.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return ['code.regex' => 'El código debe tener el formato L-00. Ejemplo: L-03.'];
    }

    public function attributes(): array
    {
        return ['code' => 'código', 'name' => 'nombre', 'feed_id' => 'alimento', 'capacity' => 'capacidad'];
    }
}
