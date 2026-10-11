@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ $categorie->nom }}</h1>
        <div>
            <a href="{{ route('categories.edit', $categorie) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">Modifier</a>
            <a href="{{ route('categories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Retour à la liste</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations générales</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nom</dt>
                    <dd class="text-sm text-gray-900">{{ $categorie->nom }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Créé le</dt>
                    <dd class="text-sm text-gray-900">{{ $categorie->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Dernière modification</dt>
                    <dd class="text-sm text-gray-900">{{ $categorie->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Statistiques</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nombre de livres</dt>
                    <dd class="text-sm text-gray-900">{{ $categorie->livres->count() }}</dd>
                </div>
            </dl>
        </div>
    </div>

    @if($categorie->description)
    <div class="mt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Description</h2>
        <p class="text-gray-700">{{ $categorie->description }}</p>
    </div>
    @endif

    @if($categorie->livres->count() > 0)
    <div class="mt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Livres dans cette catégorie</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disponible</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($categorie->livres as $livre)
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $livre->titre }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $livre->auteur }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $livre->quantite_disponible > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $livre->quantite_disponible }}
                            </span>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('livres.show', $livre) }}" class="text-indigo-600 hover:text-indigo-900">Voir le livre</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
