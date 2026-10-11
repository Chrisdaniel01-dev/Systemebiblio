@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ $livre->titre }}</h1>
        <div>
            <a href="{{ route('livres.edit', $livre) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">Modifier</a>
            <a href="{{ route('livres.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Retour à la liste</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations générales</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Titre</dt>
                    <dd class="text-sm text-gray-900">{{ $livre->titre }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Auteur</dt>
                    <dd class="text-sm text-gray-900">{{ $livre->auteur }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">ISBN</dt>
                    <dd class="text-sm text-gray-900">{{ $livre->isbn ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Année de publication</dt>
                    <dd class="text-sm text-gray-900">{{ $livre->annee_publication ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Catégorie</dt>
                    <dd class="text-sm text-gray-900">{{ $livre->categorie->nom ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Statut et disponibilité</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Quantité disponible</dt>
                    <dd class="text-sm text-gray-900">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $livre->quantite_disponible > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $livre->quantite_disponible }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Créé le</dt>
                    <dd class="text-sm text-gray-900">{{ $livre->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Dernière modification</dt>
                    <dd class="text-sm text-gray-900">{{ $livre->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    @if($livre->description)
    <div class="mt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Description</h2>
        <p class="text-gray-700">{{ $livre->description }}</p>
    </div>
    @endif

    @if($livre->emprunts->count() > 0)
    <div class="mt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Historique des emprunts</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Abonné</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'emprunt</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de retour prévue</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de retour effective</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($livre->emprunts as $emprunt)
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $emprunt->abonne->nom }} {{ $emprunt->abonne->prenom }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $emprunt->date_retour_prevue->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $emprunt->date_retour_effective ? $emprunt->date_retour_effective->format('d/m/Y') : 'Non retourné' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $emprunt->date_retour_effective ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $emprunt->date_retour_effective ? 'Retourné' : 'En cours' }}
                            </span>
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
