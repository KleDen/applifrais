<?php

namespace App\Http\Controllers;

use App\Models\FichesFrais;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FicheFraisController extends Controller
{
    public function __construct()
    {
        // Все маршруты требуют авторизации
        $this->middleware('auth');
    }

    /**
     * Показывает список своих fiches de frais.
     */
    public function index(): View
    {
        $user = auth()->user();
        $fiches = FichesFrais::with(['lignesForfait.forfait', 'lignesHorsForfait', 'etat'])
            ->where('id_visiteur', $user->id)
            ->orderByDesc('annee')
            ->orderByDesc('mois')
            ->get();
        return view('fiches.index', compact('fiches'));
    }

    /**
     * Форма создания новой fiche.
     */
    public function create(): View
    {
        return view('fiches.create');
    }

    /**
     * Сохраняет новую fiche в БД.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'mois'  => 'required|integer|min:1|max:12',
            'annee' => 'required|integer|min:2000|max:2100',
            // здесь можно добавить валидацию для остальных полей
        ]);
        $fiche = new FichesFrais($data);
        $fiche->id_visiteur = auth()->id();
        $fiche->id_etat = 1; // ID начального состояния (например "CL" – "Créée")
        $fiche->montant_valide = 0;    // начальное значение
        $fiche->save();
        return redirect()
            ->route('fiches.index')
            ->with('success', 'Fiche de frais créée avec succès.');
    }

    /**
     * Просмотр одной fiche.
     */
    public function show(FichesFrais $fiche): View
    {
        $fiche->load(['lignesForfait.forfait', 'lignesHorsForfait', 'etat']);
        return view('fiches.show', compact('fiche'));
    }

    /**
     * Форма редактирования fiche.
     */
    public function edit(FichesFrais $fiche): View
    {
        return view('fiches.edit', compact('fiche'));
    }

    /**
     * Сохраняет изменения fiche.
     */
    public function update(Request $request, FichesFrais $fiche): RedirectResponse
    {
        $data = $request->validate([
            'mois'          => 'required|integer|min:1|max:12',
            'annee'         => 'required|integer|min:2000|max:2100',
            'montant_valide'=> 'nullable|numeric|min:0',
            // ...
        ]);
        $fiche->fill($data);
        $fiche->date_modif = now();
        $fiche->save();
        return redirect()
            ->route('fiches.index')
            ->with('success', 'Fiche mise à jour avec succès.');
    }

    /**
     * Удаляет fiche.
     */
    public function destroy(FichesFrais $fiche): RedirectResponse
    {
        $fiche->delete();
        return redirect()
            ->route('fiches.index')
            ->with('success', 'Fiche supprimée.');
    }
}
