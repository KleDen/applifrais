<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes rapports de frais') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            {{-- флеш-сообщение об успехе --}}
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
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Mois / Année</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Statut</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">Forfait (€)</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">Hors forfait (€)</th>
                                <th class="px-4 py-2 text-center text-sm font-medium text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($fiches as $fiche)
                                <tr>
                                    {{-- Carbon для форматирования --}}
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::create($fiche->annee, $fiche->mois, 1)->translatedFormat('F Y') }}
                                    </td>
                                    {{-- libellé de l'état --}}
                                    <td class="px-4 py-2">{{ $fiche->etat->libelle }}</td>
                                    {{-- сумма forfait --}}
                                    <td class="px-4 py-2 text-right">
                                        {{ number_format($fiche->lignesForfait->sum(fn($l) => $l->quantite * $l->forfait->montant), 2) }}
                                    </td>
                                    {{-- сумма hors forfait --}}
                                    <td class="px-4 py-2 text-right">
                                        {{ number_format($fiche->lignesHorsForfait->sum('montant'), 2) }}
                                    </td>
                                     {{-- EDIT --}}
                                    <td class="px-4 py-2 text-center space-x-2">
                                        <a href="{{ route('fiches.edit', $fiche) }}" 
                                           class="text-yellow-600 hover:underline">Éditer</a>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Кнопка «Créer une fiche» --}}
                <div class="mt-6 text-right">
                    <a href="{{ route('fiches.create') }}"
                       class="inline-block px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">
                        ➕ Créer une fiche
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
{{-- 
    Этот шаблон отображает список отчетов пользователя. 
    Он включает в себя таблицу с отчетами, их статусами и действиями (просмотр, редактирование, удаление).
    Также есть кнопка для создания нового отчета.