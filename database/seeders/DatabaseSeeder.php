<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Breed;
use App\Models\Feed;
use App\Models\FeedingStage;
use App\Models\Lot;
use App\Models\Medicine;
use App\Models\Parameter;
use App\Models\Role;
use App\Models\User;
use App\Models\Weighing;
use Illuminate\Database\Seeder;

/**
 * Datos iniciales. Coinciden con el ejemplo del Documento de Análisis (novillo NOV-015).
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = collect([
            ['admin', 'Administrador', 'Gestiona usuarios, catálogos, animales y parámetros del Core'],
            ['vet', 'Veterinario', 'Registra tratamientos'],
            ['operator', 'Operario', 'Registra pesajes y alimentación'],
        ])->mapWithKeys(fn ($r) => [$r[0] => Role::create(['code' => $r[0], 'name' => $r[1], 'description' => $r[2]])]);

        $admin = User::create(['first_name' => 'María', 'last_name' => 'Torres', 'email' => 'admin@agrogest.com', 'password' => 'Admin2026*', 'role_id' => $roles['admin']->id]);
        User::create(['first_name' => 'Luis', 'last_name' => 'Vega', 'email' => 'vet@agrogest.com', 'password' => 'Vet2026*', 'role_id' => $roles['vet']->id]);

        foreach (['Brahman' => 'Cebú, resistente al calor', 'Angus' => 'Carne de alta calidad', 'Charolais' => 'Gran tamaño y rápido crecimiento'] as $n => $d) {
            Breed::create(['name' => $n, 'description' => $d]);
        }

        foreach ([['Levante', 150, 250, 3.0, 0.6, 6.0], ['Engorde', 250, 400, 2.5, 0.9, 8.0], ['Finalización', 400, 800, 2.2, 1.0, 9.0]] as [$n, $a, $b, $c, $g, $f]) {
            FeedingStage::create(['name' => $n, 'min_weight' => $a, 'max_weight' => $b, 'intake_pct' => $c, 'expected_gdp' => $g, 'target_fca' => $f]);
        }

        $silage = Feed::create(['name' => 'Ensilaje de maíz', 'dry_matter_pct' => 30, 'cost_per_kg' => 0.035, 'stock_kg' => 25000]);
        $mix = Feed::create(['name' => 'Balanceado de engorde', 'dry_matter_pct' => 88, 'cost_per_kg' => 0.35, 'stock_kg' => 4000]);

        $l1 = Lot::create(['code' => 'L-01', 'name' => 'Corral norte', 'capacity' => 20, 'feed_id' => $mix->id]);
        $l2 = Lot::create(['code' => 'L-02', 'name' => 'Corral sur', 'capacity' => 20, 'feed_id' => $silage->id]);
        Lot::create(['code' => 'L-03', 'name' => 'Corral de cuarentena', 'capacity' => 2, 'feed_id' => $silage->id]);

        foreach ([
            ['Ivermectina 1 %', 'Antiparasitario', 0.1, 50, 30, 28, 0.08, 1250],
            ['Oxitetraciclina LA', 'Antibiótico', 0.2, 100, 7, 21, 0.12, 800],
            ['Meloxicam 2 %', 'Antiinflamatorio', 0.025, 15, 3, 15, 0.30, 300],
            ['Complejo B', 'Vitamina', 0.02, 20, 15, 0, 0.05, 500],
        ] as [$n, $t, $d, $m, $i, $w, $c, $s]) {
            Medicine::create(['name' => $n, 'type' => $t, 'dose_per_kg' => $d, 'max_dose' => $m, 'min_interval_days' => $i, 'withdrawal_days' => $w, 'unit_cost' => $c, 'stock' => $s]);
        }

        foreach ([
            ['price_per_kg', '2.30', 'Precio por kg en pie (USD)'],
            ['ics_threshold', '6', 'Umbral del índice de carga sanitaria'],
            ['ics_lambda', '0.05', 'Tasa de decaimiento sanitario (λ)'],
            ['target_margin', '25', 'Margen de ganancia objetivo (%)'],
            ['max_daily_change_kg', '3', 'Cambio máximo de peso por día (kg)'],
        ] as [$k, $v, $d]) {
            Parameter::create(['key' => $k, 'value' => $v, 'description' => $d]);
        }

        // Animal del ejemplo del informe: NOV-015
        $brahman = Breed::where('name', 'Brahman')->first();
        $nov015 = Animal::create(['tag_number' => 'NOV-015', 'sex' => 'Macho', 'entry_date' => '2026-09-05', 'entry_weight' => 320, 'purchase_cost' => 560, 'target_weight' => 450, 'breed_id' => $brahman->id, 'lot_id' => $l2->id]);
        foreach ([['2026-09-20', 332], ['2026-10-05', 345], ['2026-10-20', 356]] as [$d, $w]) {
            Weighing::create(['animal_id' => $nov015->id, 'weighed_at' => $d, 'weight_kg' => $w, 'user_id' => $admin->id]);
        }

        $others = [
            ['NOV-011', 'Brahman', $l2, 320, [[15, 334], [30, 348], [45, 362]]],
            ['NOV-012', 'Brahman', $l2, 310, [[15, 323], [30, 336], [45, 349]]],
            ['NOV-022', 'Angus', $l2, 309, [[15, 316], [30, 324], [45, 331]]],
            ['NOV-008', 'Charolais', $l1, 407, [[15, 422], [30, 437], [45, 452]]],
            ['NOV-019', 'Brahman', $l1, 375, [[15, 389], [30, 404], [45, 418]]],
        ];
        foreach ($others as [$tag, $breed, $lot, $w0, $ws]) {
            $a = Animal::create(['tag_number' => $tag, 'sex' => 'Macho', 'entry_date' => '2026-09-05', 'entry_weight' => $w0, 'purchase_cost' => round($w0 * 1.75), 'target_weight' => 450, 'breed_id' => Breed::where('name', $breed)->value('id'), 'lot_id' => $lot->id]);
            foreach ($ws as [$days, $w]) {
                Weighing::create(['animal_id' => $a->id, 'weighed_at' => now()->setDate(2026, 9, 5)->addDays($days)->toDateString(), 'weight_kg' => $w, 'user_id' => $admin->id]);
            }
        }
    }
}
