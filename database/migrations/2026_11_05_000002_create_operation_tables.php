<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tablas operativas: animales, pesajes, alimentación, tratamientos y ventas.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('tag_number', 20)->unique(); // arete
            $table->string('sex', 10);                  // Macho / Hembra
            $table->date('birth_date')->nullable();
            $table->date('entry_date');
            $table->decimal('entry_weight', 6, 2);
            $table->decimal('purchase_cost', 10, 2);
            $table->decimal('target_weight', 6, 2);
            $table->string('status', 30)->default('En engorde');
            $table->foreignId('breed_id')->constrained('breeds');
            $table->foreignId('lot_id')->constrained('lots');
            $table->timestamps();
        });

        Schema::create('weighings', function (Blueprint $table) {
            $table->id();
            $table->decimal('weight_kg', 6, 2);
            $table->date('weighed_at');
            $table->foreignId('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
            $table->unique(['animal_id', 'weighed_at']);
        });

        Schema::create('feedings', function (Blueprint $table) {
            $table->id();
            $table->decimal('quantity_kg', 8, 2);
            $table->date('fed_at');
            $table->foreignId('lot_id')->constrained('lots');
            $table->foreignId('feed_id')->constrained('feeds');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->string('diagnosis', 200);
            $table->decimal('dose_applied', 8, 2);
            $table->decimal('cost', 10, 2);
            $table->date('applied_at');
            $table->date('withdrawal_until');
            $table->foreignId('animal_id')->constrained('animals');
            $table->foreignId('medicine_id')->constrained('medicines');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('buyer_name', 100);
            $table->string('buyer_id_number', 13);
            $table->decimal('weight_kg', 6, 2);
            $table->decimal('price_per_kg', 8, 2);
            $table->decimal('total', 10, 2);
            $table->date('sold_at');
            $table->foreignId('animal_id')->unique()->constrained('animals');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['sales', 'treatments', 'feedings', 'weighings', 'animals'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
