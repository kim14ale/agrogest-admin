<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80', Rule::unique('feeds', 'name')->ignore($this->route('feed')?->id)],
            'dry_matter_pct' => ['required', 'numeric', 'between:5,100'],
            'cost_per_kg' => ['required', 'numeric', 'gt:0', 'max:50'],
            'stock_kg' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return ['name' => 'nombre', 'dry_matter_pct' => 'materia seca', 'cost_per_kg' => 'costo por kg', 'stock_kg' => 'stock'];
    }
}
