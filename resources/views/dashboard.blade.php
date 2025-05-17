<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ 'Bienvenue, ' . auth()->user()->name . '!' }}
                    <p>TEST TEXT - SHOULD BE VISIBLE</p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('fiches.create') }}" class="inline-block px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                        {{ '➕ Créer une fiche' }} 
                    </a>
                </div>
                <div class="mt-4">
                    <a href="{{ route('fiches.index') }}" class="inline-block px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75">
                        {{ '📄 Voir mes fiches' }}
                    </a> 
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
