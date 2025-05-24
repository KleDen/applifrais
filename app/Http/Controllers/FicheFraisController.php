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
            ->where('user_id', $user->id)
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
        'mois' => 'required|string|max:2',
        'annee' => 'required|integer',
        'forfait_repas' => 'nullable|integer|min:0',
        'forfait_nuitee' => 'nullable|integer|min:0',
        'forfait_km' => 'nullable|integer|min:0',
        'horsforfait_libelle.*' => 'nullable|string',
        'horsforfait_montant.*' => 'nullable|numeric',
        'horsforfait_justificatif.*' => 'nullable|file|mimes:jpg,png,pdf',
    ]);

    // Создание основной fiche
    $fiche = FichesFrais::create([
        'mois' => $data['mois'],
        'annee' => $data['annee'],
        'user_id' => auth()->id(),
        'etat_id' => 'CL', // Créée
        'montant_valide' => 0,
        'nb_justificatifs' => count(array_filter($request->file('horsforfait_justificatif') ?? [])),
        'date_modif' => now(),
    ]);

    // Сохранение lignes forfait
    $forfaits = [
    ['id' => 1, 'quantite' => $data['forfait_repas']],
    ['id' => 2, 'quantite' => $data['forfait_nuitee']],
    ['id' => 3, 'quantite' => $data['forfait_km']],
];

foreach ($forfaits as $forfait) {
    if ($forfait['quantite'] > 0) {
        $fiche->lignesForfait()->create([
            'frais_forfait_id' => $forfait['id'],
            'quantite' => $forfait['quantite'],
        ]);
    }
}
    // Сохранение lignes hors forfait
    $libelles = $data['horsforfait_libelle'] ?? [];
    $montants = $data['horsforfait_montant'] ?? [];
    $justificatifs = $request->file('horsforfait_justificatif') ?? [];

    foreach ($libelles as $index => $libelle) {
        if ($libelle && $montants[$index]) {
            $path = null;
            if (isset($justificatifs[$index])) {
                $path = $justificatifs[$index]->store('justificatifs', 'public');
            }
            $fiche->lignesHorsForfait()->create([
                'libelle' => $libelle,
                'montant' => $montants[$index],
                'justificatif' => $path,
                'date' => now(),
            ]);
        }
    }

    return redirect()
        ->route('fiches.index')
        ->with('success', 'Fiche de frais créée avec succès.');
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
