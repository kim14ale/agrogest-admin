<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Breed;
use App\Models\Feed;
use App\Models\FeedingStage;
use App\Models\Lot;
use App\Models\Medicine;
use App\Models\Weighing;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'counts' => [
                'Animales en engorde' => Animal::where('status', Animal::STATUS_FATTENING)->count(),
                'Lotes' => Lot::count(),
                'Pesajes registrados' => Weighing::count(),
                'Medicamentos' => Medicine::count(),
            ],
            'catalogs' => [
                ['Razas', Breed::count(), 'breeds.index'],
                ['Etapas de engorde', FeedingStage::count(), 'stages.index'],
                ['Alimentos', Feed::count(), 'feeds.index'],
                ['Lotes', Lot::count(), 'lots.index'],
                ['Medicamentos', Medicine::count(), 'medicines.index'],
                ['Animales', Animal::count(), 'animals.index'],
            ],
            'lastWeighings' => Weighing::with('animal.lot')->latest()->take(5)->get(),
        ]);
    }
}
