<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FicheFraisController;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    Carbon::setLocale('fr');
    $today = Carbon::now()->isoFormat('dddd D MMMM YYYY');
    return view('dashboard', ['today' => $today]);
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
      
    // Fiches frais
    Route::resource('fiches', FicheFraisController::class);

    // Frais forfaitaires (ajout ligne)
    Route::post('/fiches/{id}/forfaitaires', [LigneFraisForfaitController::class, 'store'])->name('forfaitaires.store');

    // Frais hors forfait (ajout ligne + justificatif)
    Route::post('/fiches/{id}/horsforfait', [LigneFraisHorsForfaitController::class, 'store'])->name('horsforfait.store'); 


});


require __DIR__.'/auth.php';
