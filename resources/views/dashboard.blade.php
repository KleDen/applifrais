<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-8">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Bonjour, {{ auth()->user()->prenom }} !
                    </h1>
                    <p class="text-gray-600 mt-1">
                        Bienvenue sur votre espace Applifrais.
                        
                    </p>
                </div>
                <div class="mb-8">
                    <p class="text-lg text-gray-800 font-medium">Que souhaitez-vous faire aujourd’hui&nbsp;?</p>
                </div>
                <div class="flex flex-col gap-4">
                    <a href="{{ route('fiches.create') }}"
                       class="w-full inline-block px-6 py-4 bg-gsbblue hover:bg-gsbblue-dark text-white text-lg font-semibold rounded-lg shadow-md transition">
                        ➕ Créer une fiche de frais
                    </a>
                    <a href="{{ route('fiches.index') }}"
                       class="w-full inline-block px-6 py-4 bg-gsbnavy hover:bg-gsbnavy-dark text-white text-lg font-semibold rounded-lg shadow-md transition">
                        📄 Consulter mes fiches de frais
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>1
