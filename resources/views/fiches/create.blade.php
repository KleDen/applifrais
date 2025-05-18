<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer une fiche de frais') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-8">
                <form method="POST" action="{{ route('fiches.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="mois" class="block text-gray-700 font-bold mb-2">Mois</label>
                        <input type="text" id="mois" name="mois" class="w-full border rounded px-3 py-2" placeholder="MM" required>
                    </div>
                    <div class="mb-4">
                        <label for="annee" class="block text-gray-700 font-bold mb-2">Année</label>
                        <input type="text" id="annee" name="annee" class="w-full border rounded px-3 py-2" placeholder="YYYY" required>
                    </div>
                    <div class="mb-4">
                        <label for="commentaire" class="block text-gray-700 font-bold mb-2">Commentaire</label>
                        <textarea id="commentaire" name="commentaire" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">Créer la fiche</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
