<?php

return [
    'after_or_equal' => 'El campo :attribute debe ser una fecha igual o posterior a :date.',
    'before' => 'El campo :attribute debe ser una fecha anterior a :date.',
    'before_or_equal' => 'El campo :attribute no puede ser una fecha futura.',
    'between' => ['numeric' => 'El campo :attribute debe estar entre :min y :max.'],
    'date' => 'El campo :attribute no es una fecha válida.',
    'email' => 'El campo :attribute debe ser un correo válido.',
    'exists' => 'El valor elegido en :attribute no existe en la base de datos.',
    'gt' => ['numeric' => 'El campo :attribute debe ser mayor que :value.'],
    'gte' => ['numeric' => 'El campo :attribute debe ser mayor o igual que :value.'],
    'in' => 'El valor elegido en :attribute no es válido.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'max' => ['numeric' => 'El campo :attribute no puede ser mayor que :max.', 'string' => 'El campo :attribute no puede tener más de :max caracteres.'],
    'min' => ['numeric' => 'El campo :attribute debe ser al menos :min.', 'string' => 'El campo :attribute debe tener al menos :min caracteres.'],
    'numeric' => 'El campo :attribute debe ser un número.',
    'regex' => 'El formato del campo :attribute no es válido.',
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser texto.',
    'unique' => 'Ya existe un registro con el mismo valor en :attribute.',
    'attributes' => [],
    'values' => ['entry_weight' => ['' => 'el peso de ingreso'], 'dose_per_kg' => ['' => 'la dosis por kg'], 'min_weight' => ['' => 'el peso mínimo']],
];
