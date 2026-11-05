<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catálogos que configuran el Core: razas, etapas de engorde, alimentos,
 * lotes, medicamentos y parámetros.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('breeds', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('description', 200)->nullable();
            $table->timestamps();
        });

        Schema::create('feeding_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->decimal('min_weight', 6, 2);
            $table->decimal('max_weight', 6, 2);
            $table->decimal('intake_pct', 5, 2);    // % del peso vivo en materia seca
            $table->decimal('expected_gdp', 5, 3);  // kg/día
            $table->decimal('target_fca', 5, 2);    // conversión alimenticia objetivo
            $table->timestamps();
        });

        Schema::create('feeds', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->unique();
            $table->decimal('dry_matter_pct', 5, 2);
            $table->decimal('cost_per_kg', 8, 3);
            $table->decimal('stock_kg', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 80);
            $table->unsignedInteger('capacity');
            $table->foreignId('feed_id')->constrained('feeds');
            $table->timestamps();
        });

        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->unique();
            $table->string('type', 30); // Antibiótico, Antiinflamatorio, Antiparasitario, Vitamina, Vacuna
            $table->decimal('dose_per_kg', 6, 3);
            $table->decimal('max_dose', 8, 2);
            $table->unsignedInteger('min_interval_days');
            $table->unsignedInteger('withdrawal_days');
            $table->decimal('unit_cost', 8, 3);
            $table->decimal('stock', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique();
            $table->string('value', 50);
            $table->string('description', 200)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['parameters', 'medicines', 'lots', 'feeds', 'feeding_stages', 'breeds'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
