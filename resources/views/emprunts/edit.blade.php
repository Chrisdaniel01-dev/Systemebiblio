@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Modifier l'emprunt</h1>

    <form method="POST" action="{{ route('emprunts.update', $emprunt) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="livre_id" class="block text-sm font-medium text-gray-700">Livre</label>
                <select name="livre_id" id="livre_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Sélectionner un livre</option>
                    @foreach($livres as $livre)
                        <option value="{{ $livre->id }}" {{ old('livre_id', $emprunt->livre_id) == $livre->id ? 'selected' : '' }}>
                            {{ $livre->titre }} - {{ $livre->auteur }}
                        </option>
                    @endforeach
                </select>
                @error('livre_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="abonne_id" class="block text-sm font-medium text-gray-700">Abonné</label>
                <select name="abonne_id" id="abonne_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Sélectionner un abonné</option>
                    @foreach($abonnes as $abonne)
                        <option value="{{ $abonne->id }}" {{ old('abonne_id', $emprunt->abonne_id) == $abonne->id ? 'selected' : '' }}>
                            {{ $abonne->nom }} {{ $abonne->prenom }} - {{ $abonne->email }}
                        </option>
                    @endforeach
                </select>
                @error('abonne_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="date_emprunt" class="block text-sm font-medium text-gray-700">Date d'emprunt</label>
                <input type="date" name="date_emprunt" id="date_emprunt" value="{{ old('date_emprunt', $emprunt->date_emprunt->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('date_emprunt') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="date_retour_prevue" class="block text-sm font-medium text-gray-700">Date de retour prévue</label>
                <input type="date" name="date_retour_prevue" id="date_retour_prevue" value="{{ old('date_retour_prevue', $emprunt->date_retour_prevue->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('date_retour_prevue') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="date_retour_effective" class="block text-sm font-medium text-gray-700">Date de retour effective</label>
                <input type="date" name="date_retour_effective" id="date_retour_effective" value="{{ old('date_retour_effective', $emprunt->date_retour_effective ? $emprunt->date_retour_effective->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('date_retour_effective') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-6">
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $emprunt->notes) }}</textarea>
            @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mt-6 flex items-center justify-end">
            <a href="{{ route('emprunts.show', $emprunt) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Annuler</a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Mettre à jour l'emprunt</button>
        </div>
    </form>
</div>
@endsection
