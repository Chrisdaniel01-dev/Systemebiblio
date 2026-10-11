@extends('layouts.app')

@section('title', 'Ajouter un Livre')

@section('styles')
    <link href="{{ asset('css/livre.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="dash-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-book"></i>
            <span class="text">Ajouter un Nouveau Livre</span>
        </div>
    </div>

    <div class="form-container">
        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('livres.store') }}" class="book-form">
            @csrf
            
            <div class="form-group">
                <label for="titre">Titre du livre *</label>
                <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required>
                @error('titre')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="auteur">Auteur *</label>
                <input type="text" id="auteur" name="auteur" value="{{ old('auteur') }}" required>
                @error('auteur')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="categorie_id">Catégorie *</label>
                <select id="categorie_id" name="categorie_id" required>
                    <option value="">Sélectionner une catégorie</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('categorie_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->nom }}
                        </option>
                    @endforeach
                </select>
                @error('categorie_id')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="quantite_disponible">Quantité disponible *</label>
                <input type="number" id="quantite_disponible" name="quantite_disponible" value="{{ old('quantite_disponible', 1) }}" min="0" required>
                @error('quantite_disponible')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="uil uil-save"></i> Enregistrer le livre
                </button>
                <a href="{{ route('livres.index') }}" class="btn-secondary">
                    <i class="uil uil-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection