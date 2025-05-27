<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes rapports de frais') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-6">
                @if($fiches->isEmpty())
                    <p class="text-center text-gray-600">Vous n'avez encore créé aucun rapport.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Créé le</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Modifié le</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">Forfait (€)</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">Hors forfait (€)</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">Total (€)</th>
                                <th class="px-4 py-2 text-center text-sm font-medium text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($fiches as $fiche)
                                @php
                                    $sumForfait     = $fiche->lignesForfait->sum(fn($l) => $l->quantite * $l->forfait->montant);
                                    $sumHorsForfait = $fiche->lignesHorsForfait->sum('montant');
                                @endphp
                                <tr>
                                    <td class="px-4 py-2">
                                        {{-- jour chiffre, mois mot, à heure:minute en une ligne --}}
                                        {{ $fiche->created_at->translatedFormat('j F \à H:i') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ $fiche->updated_at->translatedFormat('j F \à H:i') }}
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        {{ number_format($sumForfait, 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        {{ number_format($sumHorsForfait, 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-right font-semibold">
                                        {{ number_format($sumForfait + $sumHorsForfait, 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-center space-x-2">
                                        <a href="{{ route('fiches.edit', ['fiche' => $fiche->id]) }}" class="text-yellow-600 hover:underline">Éditer</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <div class="mt-6 text-right">
                    <a href="{{ route('fiches.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">
                        ➕ Créer une fiche
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
