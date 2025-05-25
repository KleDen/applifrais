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
        'etat_id' => 'CR', // Créée
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
    // 1) Валидация входа (только поля, которые мы редактируем)
    $data = $request->validate([
        'forfait_repas'   => 'nullable|integer|min:0',
        'forfait_nuitee'  => 'nullable|integer|min:0',
        'forfait_km'      => 'nullable|integer|min:0',
        'horsforfait_libelle.*'     => 'nullable|string',
        'horsforfait_montant.*'     => 'nullable|numeric',
        'horsforfait_justificatif.*' => [
        'nullable',
        'file',
        'max:10240',           // ограничение в МБ 
        'mimes:jpeg,jpg,png,gif,svg,webp,pdf'
],

    ]);

    // 2) Обновляем date_modif
    $fiche->date_modif = now();
    $fiche->save();

    // 3) Обновляем три forfait-строки
    $forfaits = [
        1 => $data['forfait_repas']  ?? 0,  // repas
        2 => $data['forfait_nuitee'] ?? 0,  // nuitée
        3 => $data['forfait_km']     ?? 0,  // km
    ];

    foreach ($forfaits as $forfaitId => $quantite) {
        // Если строка уже есть — обновляем, иначе — создаём
        $fiche->lignesForfait()
              ->updateOrCreate(
                  ['frais_forfait_id' => $forfaitId],
                  ['quantite' => $quantite]
              );
    }

    // 4) Перестраиваем hors-forfait
    //   a) Удаляем все старые записи
    $fiche->lignesHorsForfait()->delete();

    //   b) Считаем новые данные
    $libelles      = $data['horsforfait_libelle']      ?? [];
    $montants      = $data['horsforfait_montant']      ?? [];
    $justificatifs = $request->file('horsforfait_justificatif') ?? [];

    foreach ($libelles as $i => $libelle) {
        if (trim($libelle) === '' || empty($montants[$i])) {
            continue;
        }

        // сохраним файл, если есть
        $path = isset($justificatifs[$i])
            ? $justificatifs[$i]->store('justificatifs', 'public')
            : null;

        $fiche->lignesHorsForfait()->create([
            'libelle'      => $libelle,
            'montant'      => $montants[$i],
            'justificatif' => $path,
            'date'         => now(),
        ]);
    }

    // 5) Готово!
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
