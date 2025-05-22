<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nouvelle fiche de frais') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-8">
                {{-- 
                    Форма создания новой «fiche de frais» (отчёта о расходах)
                    - Месяц: можно выбрать только текущий или предыдущий (если сегодня до 10-го числа)
                    - Год: отображается автоматически, меняется при смене месяца
                    - Блок «Frais forfaitaires»: расходы по тарифу (Repas, Nuitée, Kilomètres), все поля необязательны
                    - Блок «Frais hors forfait»: расходы вне тарифа, можно добавить несколько, каждый с полями (Libellé, Montant, Justificatif)
                    - Кнопка «+ Ajouter une frais» добавляет новый блок hors forfait
                    - Кнопка «Supprimer» удаляет соответствующий блок hors forfait
                    - Все данные отправляются одной формой, сервер сам определяет, что заполнено
                --}}
                <form method="POST" action="{{ route('fiches.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="mois" class="block text-gray-700 font-bold mb-2">Mois</label>
                        <select id="mois" name="mois" class="w-full border rounded px-3 py-2" required>
                            @php
                                $now = \Carbon\Carbon::now();
                                $currentMonth = $now->month;
                                $currentYear = $now->year;
                                $showPrevious = $now->day < 10;
                                $months = [];
                                if ($showPrevious) {
                                    $prev = $now->copy()->subMonth();
                                    $months[] = [
                                        'value' => str_pad($prev->month, 2, '0', STR_PAD_LEFT),
                                        'label' => $prev->translatedFormat('F'),
                                        'year' => $prev->year
                                    ];
                                }
                                $months[] = [
                                    'value' => str_pad($currentMonth, 2, '0', STR_PAD_LEFT),
                                    'label' => $now->translatedFormat('F'),
                                    'year' => $currentYear
                                ];
                            @endphp
                            @foreach($months as $m)
                                <option value="{{ $m['value'] }}" data-year="{{ $m['year'] }}">
                                    {{ ucfirst($m['label']) }}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-gray-600 ml-2">Année : <span id="display-annee">{{ $months[count($months)-1]['year'] }}</span></span>
                        <input type="hidden" id="annee" name="annee" value="{{ $months[count($months)-1]['year'] }}">
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const moisSelect = document.getElementById('mois');
                            const anneeInput = document.getElementById('annee');
                            const displayAnnee = document.getElementById('display-annee');
                            moisSelect.addEventListener('change', function() {
                                const selected = moisSelect.options[moisSelect.selectedIndex];
                                anneeInput.value = selected.getAttribute('data-year');
                                displayAnnee.textContent = selected.getAttribute('data-year');
                            });
                        });
                    </script>
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-2">Frais forfaitaires</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="forfait_repas" class="block text-gray-700 font-bold mb-2">Repas</label>
                                <input type="number" min="0" id="forfait_repas" name="forfait_repas" class="w-full border rounded px-3 py-2" placeholder="Nombre de repas">
                            </div>
                            <div>
                                <label for="forfait_nuitee" class="block text-gray-700 font-bold mb-2">Nuitée</label>
                                <input type="number" min="0" id="forfait_nuitee" name="forfait_nuitee" class="w-full border rounded px-3 py-2" placeholder="Nombre de nuitées">
                            </div>
                            <div>
                                <label for="forfait_km" class="block text-gray-700 font-bold mb-2">Kilomètres</label>
                                <input type="number" min="0" id="forfait_km" name="forfait_km" class="w-full border rounded px-3 py-2" placeholder="Nombre de kilomètres">
                            </div>
                        </div>
                    </div>
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-2">Frais hors forfait</h3>
                        <div id="horsforfait-list">
                            <div class="horsforfait-item mb-4 border-b pb-4">
                                <div class="mb-2">
                                    <label for="horsforfait_libelle[]" class="block text-gray-700 font-bold mb-2">Libellé</label>
                                    <input type="text" name="horsforfait_libelle[]" class="w-full border rounded px-3 py-2" placeholder="Libellé de la dépense">
                                </div>
                                <div class="mb-2">
                                    <label for="horsforfait_montant[]" class="block text-gray-700 font-bold mb-2">Montant (€)</label>
                                    <input type="number" min="0" step="0.01" name="horsforfait_montant[]" class="w-full border rounded px-3 py-2" placeholder="Montant de la dépense">
                                </div>
                                <div class="mb-2">
                                    <label for="horsforfait_justificatif[]" class="block text-gray-700 font-bold mb-2">Justificatif (photo ou PDF)</label>
                                    <input type="file" name="horsforfait_justificatif[]" accept="image/*,application/pdf" class="w-full border rounded px-3 py-2">
                                </div>
                                <button type="button" class="remove-horsforfait bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Supprimer</button>
                            </div>
                        </div>
                        <button type="button" id="add-horsforfait" class="mt-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">+ Ajouter une frais</button>
                    </div>
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
                                        <label class="block text-gray-700 font-bold mb-2">Justificatif (photo ou PDF)</label>
                                        <input type="file" name="horsforfait_justificatif[]" accept="image/*,application/pdf" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <button type="button" class="remove-horsforfait bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Удалить</button>
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
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">Créer la fiche</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
