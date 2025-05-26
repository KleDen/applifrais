<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le rapport de frais') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Сообщение об успехе или ошибке --}}
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Форма редактирования расходов --}}
                <form method="POST" action="{{ route('fiches.update', $fiche) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Frais forfaitaires --}}
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Frais forfaitaires</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @php
                                $repas = $fiche->lignesForfait->firstWhere('frais_forfait_id', 1)->quantite ?? 0;
                                $nuitee = $fiche->lignesForfait->firstWhere('frais_forfait_id', 2)->quantite ?? 0;
                                $etape = $fiche->lignesForfait->firstWhere('frais_forfait_id', 3)->quantite ?? 0;
                                $km = $fiche->lignesForfait->firstWhere('frais_forfait_id', 4)->quantite ?? 0;
                            @endphp

                            <div>
                                <label for="forfait_repas" class="block text-gray-700 font-bold mb-2">Repas</label>
                                <input type="number" min="0" id="forfait_repas" name="forfait_repas"
                                       class="w-full border rounded px-3 py-2"
                                       value="{{ $repas }}">
                            </div>

                            <div>
                                <label for="forfait_nuitee" class="block text-gray-700 font-bold mb-2">Nuitée</label>
                                <input type="number" min="0" id="forfait_nuitee" name="forfait_nuitee"
                                       class="w-full border rounded px-3 py-2"
                                       value="{{ $nuitee }}">
                            </div>

                            <div>
                                <label for="forfait_etape" class="block text-gray-700 font-bold mb-2">Relais étape</label>
                                <input type="number" min="0" id="forfait_etape" name="forfait_etape"
                                       class="w-full border rounded px-3 py-2"
                                       value="{{ $etape }}">
                            </div>                            
                            
                            <div>
                                <label for="forfait_km" class="block text-gray-700 font-bold mb-2">Kilomètres</label>
                                <input type="number" min="0" id="forfait_km" name="forfait_km"
                                       class="w-full border rounded px-3 py-2"
                                       value="{{ $km }}">
                            </div>
                        </div>
                    </div>

                    {{-- Frais hors forfait --}}
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Frais hors forfait</h3>
                        <div id="horsforfait-list">
                            @foreach($fiche->lignesHorsForfait as $index => $ligne)
                                <div class="horsforfait-item mb-4 border-b pb-4">
                                    <div class="mb-2">
                                        <label class="block text-gray-700 font-bold mb-2">Libellé</label>
                                        <input type="text" name="horsforfait_libelle[]"
                                               class="w-full border rounded px-3 py-2"
                                               value="{{ $ligne->libelle }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="block text-gray-700 font-bold mb-2">Montant (€)</label>
                                        <input type="number" min="0" step="0.01" name="horsforfait_montant[]"
                                               class="w-full border rounded px-3 py-2"
                                               value="{{ $ligne->montant }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="block text-gray-700 font-bold mb-2">Justificatif</label>
                                        @if($ligne->justificatif)
                                            <p class="text-sm mb-1">
                                                <a href="{{ Storage::url($ligne->justificatif) }}" target="_blank" class="text-blue-600 hover:underline">
                                                    Voir justificatif
                                                </a>
                                            </p>
                                        @endif
                                        <input type="file" name="horsforfait_justificatif[]" accept="image/*,application/pdf"
                                               class="w-full border rounded px-3 py-2">
                                    </div>
                                    <button type="button" class="remove-horsforfait bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                        Supprimer
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-horsforfait"
                                class="mt-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            + Ajouter un frais hors forfait
                        </button>
                    </div>

                    {{-- Bouton enregistrer --}}
                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold">
                            Enregistrer
                        </button>
                    </div>
                </form>

                {{-- Formulaire suppression --}}
                <form action="{{ route('fiches.destroy', $fiche) }}" method="POST" onsubmit="return confirm('Supprimer ce rapport ?');" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700 font-semibold">
                        Supprimer
                    </button>
                </form>

                <div class="mt-6">
                    <a href="{{ route('fiches.index') }}" class="text-gray-600 hover:underline">← Retour à la liste</a>
                </div>

            </div>
        </div>
    </div>

    {{-- JS для добавления/удаления hors forfait --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const horsforfaitList = document.getElementById('horsforfait-list');
            const addBtn = document.getElementById('add-horsforfait');
            addBtn.addEventListener('click', function() {
                const item = document.createElement('div');
                item.className = 'horsforfait-item mb-4 border-b pb-4';
                item.innerHTML = `
                    <div class="mb-2">
                        <label class="block text-gray-700 font-bold mb-2">Libellé</label>
                        <input type="text" name="horsforfait_libelle[]" class="w-full border rounded px-3 py-2" placeholder="Libellé de la dépense">
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 font-bold mb-2">Montant (€)</label>
                        <input type="number" min="0" step="0.01" name="horsforfait_montant[]" class="w-full border rounded px-3 py-2" placeholder="Montant de la dépense">
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 font-bold mb-2">Justificatif</label>
                        <input type="file" name="horsforfait_justificatif[]" accept="image/*,application/pdf" class="w-full border rounded px-3 py-2">
                    </div>
                    <button type="button" class="remove-horsforfait bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Supprimer</button>
                `;
                horsforfaitList.appendChild(item);
            });
            horsforfaitList.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-horsforfait')) {
                    e.target.closest('.horsforfait-item').remove();
                }
            });
        });
    </script>
</x-app-layout>
