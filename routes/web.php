<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FicheFraisController;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::redirect('/', '/login');

use App\Models\FichesFrais;
use App\Models\FraisForfait;

Route::get('/dashboard', function () {
    Carbon::setLocale('fr');

    $user   = auth()->user();
    $now    = Carbon::now();
    $mois   = str_pad($now->month, 2, '0', STR_PAD_LEFT);
    $annee  = $now->year;

    $fiche = FichesFrais::with(['lignesForfait.forfait', 'lignesHorsForfait'])
        ->where('user_id', $user->id)
        ->where('mois', $mois)
        ->where('annee', $annee)
        ->first();

    $fraisForfaits = FraisForfait::all();

    $prevFiches = FichesFrais::with('etat')
        ->where('user_id', $user->id)
        ->where(function ($q) use ($annee, $mois) {
            $q->where('annee', '<', $annee)
              ->orWhere(function ($q2) use ($annee, $mois) {
                  $q2->where('annee', $annee)->where('mois', '<', $mois);
              });
        })
        ->orderByDesc('annee')
        ->orderByDesc('mois')
        ->limit(12)
        ->get();

    return view('dashboard', [
        'fiche'         => $fiche,
        'fraisForfaits' => $fraisForfaits,
        'prevFiches'    => $prevFiches,
        'moisActuel'    => $now->translatedFormat('F'),
        'anneeActuelle' => $annee,
    ]);
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
      
    // Fiches frais
    Route::resource('fiches', FicheFraisController::class)
     ->parameters(['fiches' => 'fiche']);



});


require __DIR__.'/auth.php';
