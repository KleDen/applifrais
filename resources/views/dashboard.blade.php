<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">
                    Dépenses du mois de {{ ucfirst($moisActuel) }} {{ $anneeActuelle }}
                </h1>

                @if(!$fiche)
                    <p class="mb-6 text-gray-700">Aucune fiche de frais pour ce mois.</p>
                    <a href="{{ route('fiches.create') }}" class="px-4 py-2 bg-gsbblue text-white rounded">Créer la fiche du mois</a>
                @else
                    <form method="POST" action="{{ route('fiches.update', $fiche) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <h3 class="text-lg font-semibold mb-2">Frais forfaitaires</h3>
                        <table class="min-w-full mb-6 divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Libellé</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Quantité</th>
                                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">Montant (€)</th>
                                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">Total (€)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($fraisForfaits as $forfait)
                                    @php
                                        $ligne = $fiche->lignesForfait->firstWhere('frais_forfait_id', $forfait->id);
                                        $quantite = $ligne->quantite ?? 0;
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-2">{{ $forfait->libelle }}</td>
                                        <td class="px-4 py-2">
                                            <input type="number" min="0" name="forfait_{{ $forfait->id }}" value="{{ $quantite }}" class="w-24 border rounded px-2 py-1">
                                        </td>
                                        <td class="px-4 py-2 text-right">{{ number_format($forfait->montant, 2) }}</td>
                                        <td class="px-4 py-2 text-right">{{ number_format($forfait->montant * $quantite, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h3 class="text-lg font-semibold mb-2">Frais hors forfait</h3>
                        <div id="horsforfait-list">
                            @foreach($fiche->lignesHorsForfait as $ligne)
                                <div class="horsforfait-item mb-4 border-b pb-4">
                                    <div class="mb-2">
                                        <label class="block text-gray-700 font-bold mb-1">Libellé</label>
                                        <input type="text" name="horsforfait_libelle[]" class="w-full border rounded px-3 py-2" value="{{ $ligne->libelle }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="block text-gray-700 font-bold mb-1">Montant (€)</label>
                                        <input type="number" min="0" step="0.01" name="horsforfait_montant[]" class="w-full border rounded px-3 py-2" value="{{ $ligne->montant }}">
                                    </div>
                                    <button type="button" class="remove-horsforfait bg-red-500 text-white px-3 py-1 rounded">Supprimer</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-horsforfait" class="mt-2 bg-green-600 text-white px-4 py-2 rounded">+ Ajouter un frais hors forfait</button>

                        <div class="mt-6 text-right">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold">Enregistrer</button>
                        </div>
                    </form>
                @endif

                <div class="mt-10">
                    <h3 class="text-lg font-semibold mb-2">Mois précédents</h3>
                    <ul class="space-y-1">
                        @forelse($prevFiches as $pf)
                            @php
                                $sumForfait = $pf->lignesForfait->sum(fn($l) => $l->quantite * $l->forfait->montant);
                                $sumHF = $pf->lignesHorsForfait->sum('montant');
                                $date = Carbon\Carbon::create($pf->annee, $pf->mois, 1);
                            @endphp
                            <li>{{ ucfirst($date->translatedFormat('F Y')) }} - {{ number_format($sumForfait + $sumHF, 2) }} €</li>
                        @empty
                            <li>Aucune donnée pour les mois précédents.</li>
                        @endforelse
                    </ul>

                    <div class="mt-4">
                        <a href="{{ route('fiches.index') }}" class="text-blue-600 hover:underline">Afficher plus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const list = document.getElementById('horsforfait-list');
            const addBtn = document.getElementById('add-horsforfait');
            addBtn.addEventListener('click', function() {
                const item = document.createElement('div');
                item.className = 'horsforfait-item mb-4 border-b pb-4';
                item.innerHTML = `
                    <div class="mb-2">
                        <label class="block text-gray-700 font-bold mb-1">Libellé</label>
                        <input type="text" name="horsforfait_libelle[]" class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 font-bold mb-1">Montant (€)</label>
                        <input type="number" min="0" step="0.01" name="horsforfait_montant[]" class="w-full border rounded px-3 py-2">
                    </div>
                    <button type="button" class="remove-horsforfait bg-red-500 text-white px-3 py-1 rounded">Supprimer</button>
                `;
                list.appendChild(item);
            });
            list.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-horsforfait')) {
                    e.target.closest('.horsforfait-item').remove();
                }
            });
        });
    </script>
</x-app-layout>
