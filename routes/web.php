<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FicheFraisController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
