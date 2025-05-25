<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FicheFraisController;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::redirect('/', '/login');

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
    Route::resource('fiches', FicheFraisController::class)
     ->parameters(['fiches' => 'fiche']);



});


require __DIR__.'/auth.php';
