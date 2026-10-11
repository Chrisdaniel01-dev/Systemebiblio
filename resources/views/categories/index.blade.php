@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Catégories</h1>
        <a href="{{ route('categories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Ajouter une catégorie
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre de livres</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($categories as $categorie)
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $categorie->nom }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $categorie->description ?? 'N/A' }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $categorie->livres->count() }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('categories.show', $categorie) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Voir</a>
                        <a href="{{ route('categories.edit', $categorie) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Modifier</a>
                        <form method="POST" action="{{ route('categories.destroy', $categorie) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection
