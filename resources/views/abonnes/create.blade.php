@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/abonne.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="overview">
    <div class="title">
        <i class="uil uil-plus"></i>
        <span class="text">Nouveau Abonné</span>
    </div>
</div>

<div class="top">
    <i class="uil uil-bars slidebar-toggle"></i>
    <div class="search-box">
        <i class="uil uil-search"></i>
        <input type="text" placeholder="Rechercher..." id="searchInput">
    </div>
    <img src="{{ asset('images/images (1).jpg') }}" alt="">
</div>

<div class="filters">
    <button class="add-btn" onclick="window.history.back()">Retour à la liste</button>
</div>

<div class="abonnes-container">
    <form method="POST" action="{{ route('abonnes.store') }}">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 20px;">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required>
                @error('nom') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required>
                @error('prenom') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="date_naissance">Date de naissance</label>
                <input type="date" name="date_naissance" id="date_naissance" value="{{ old('date_naissance') }}">
                @error('date_naissance') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="classe">Classe</label>
                <input type="text" name="classe" id="classe" value="{{ old('classe') }}" required>
                @error('classe') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}">
                @error('telephone') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label for="adresse">Adresse</label>
                <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}" required>
                @error('adresse') <p class="error-message">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('abonnes.index') }}" class="cancel-btn">Annuler</a>
            <button type="submit" class="submit-btn">Créer l'abonné</button>
        </div>
    </form>
</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any JavaScript functionality if needed
});
</script>
@endpush
@endsection
