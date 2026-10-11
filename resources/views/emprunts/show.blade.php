@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Emprunt #{{ $emprunt->id }}</h1>
        <div>
            <a href="{{ route('emprunts.edit', $emprunt) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">Modifier</a>
            <a href="{{ route('emprunts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Retour à la liste</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations de l'emprunt</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Livre</dt>
                    <dd class="text-sm text-gray-900">
                        <a href="{{ route('livres.show', $emprunt->livre) }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ $emprunt->livre->titre }}
                        </a>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Auteur</dt>
                    <dd class="text-sm text-gray-900">{{ $emprunt->livre->auteur }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Abonné</dt>
                    <dd class="text-sm text-gray-900">
                        <a href="{{ route('abonnes.show', $emprunt->abonne) }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ $emprunt->abonne->nom }} {{ $emprunt->abonne->prenom }}
                        </a>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email de l'abonné</dt>
                    <dd class="text-sm text-gray-900">{{ $emprunt->abonne->email }}</dd>
                </div>
            </dl>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Dates et statut</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date d'emprunt</dt>
                    <dd class="text-sm text-gray-900">{{ $emprunt->date_emprunt->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date de retour prévue</dt>
                    <dd class="text-sm text-gray-900">{{ $emprunt->date_retour_prevue->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date de retour effective</dt>
                    <dd class="text-sm text-gray-900">{{ $emprunt->date_retour_effective ? $emprunt->date_retour_effective->format('d/m/Y') : 'Non retourné' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                    <dd class="text-sm text-gray-900">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $emprunt->date_retour_effective ? 'bg-green-100 text-green-800' : ($emprunt->date_retour_prevue < now() ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            @if($emprunt->date_retour_effective)
                                Retourné
                            @elseif($emprunt->date_retour_prevue < now())
                                En retard
                            @else
                                En cours
                            @endif
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Créé le</dt>
                    <dd class="text-sm text-gray-900">{{ $emprunt->created_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    @if($emprunt->notes)
    <div class="mt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Notes</h2>
        <p class="text-gray-700">{{ $emprunt->notes }}</p>
    </div>
    @endif

    @if(!$emprunt->date_retour_effective)
    <div class="mt-6">
        <form method="POST" action="{{ route('emprunts.return', $emprunt) }}" class="inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Marquer comme retourné
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
