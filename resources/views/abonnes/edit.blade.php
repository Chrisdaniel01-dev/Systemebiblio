@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/abonne-edit-pure.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="abonne-edit-container">
    <div class="abonne-edit-wrapper">
        <!-- Header Section -->
        <div class="abonne-edit-header">
            <div class="abonne-edit-header-content">
                <div class="abonne-edit-header-left">
                    <div class="abonne-edit-header-icon">
                        <svg class="abonne-edit-header-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="abonne-edit-header-text">
                        <h1 class="abonne-edit-title">Modifier l'abonné</h1>
                        <p class="abonne-edit-subtitle">Mettez à jour les informations de l'abonné</p>
                    </div>
                </div>
                <a href="{{ route('abonnes.show', $abonne) }}" class="abonne-edit-view-btn">
                    <svg class="abonne-edit-view-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Voir le profil
                </a>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="abonne-edit-card">
            <div class="abonne-edit-card-header">
                <h2 class="abonne-edit-card-title">Informations personnelles</h2>
            </div>

            <form method="POST" action="{{ route('abonnes.update', $abonne) }}" class="abonne-edit-form">
                @csrf
                @method('PUT')

                <!-- Personal Information Section -->
                <div class="abonne-edit-section">
                    <div class="abonne-edit-section-header">
                        <h3 class="abonne-edit-section-title">
                            <svg class="abonne-edit-section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informations de base
                        </h3>
                    </div>

                    <div class="abonne-edit-grid">
                        <div class="abonne-edit-field">
                            <label for="nom" class="abonne-edit-label">Nom *</label>
                            <div class="abonne-edit-input-wrapper">
                                <input type="text"
                                       name="nom"
                                       id="nom"
                                       value="{{ old('nom', $abonne->nom) }}"
                                       class="abonne-edit-input"
                                       required>
                                <div class="abonne-edit-input-icon">
                                    <svg class="abonne-edit-input-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('nom')
                                <p class="abonne-edit-error">
                                    <svg class="abonne-edit-error-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="abonne-edit-field">
                            <label for="prenom" class="abonne-edit-label">Prénom *</label>
                            <div class="abonne-edit-input-wrapper">
                                <input type="text"
                                       name="prenom"
                                       id="prenom"
                                       value="{{ old('prenom', $abonne->prenom) }}"
                                       class="abonne-edit-input"
                                       required>
                                <div class="abonne-edit-input-icon">
                                    <svg class="abonne-edit-input-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('prenom')
                                <p class="abonne-edit-error">
                                    <svg class="abonne-edit-error-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="abonne-edit-field">
                            <label for="email" class="abonne-edit-label">Email *</label>
                            <div class="abonne-edit-input-wrapper">
                                <input type="email"
                                       name="email"
                                       id="email"
                                       value="{{ old('email', $abonne->email) }}"
                                       class="abonne-edit-input"
                                       required>
                                <div class="abonne-edit-input-icon">
                                    <svg class="abonne-edit-input-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('email')
                                <p class="abonne-edit-error">
                                    <svg class="abonne-edit-error-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="abonne-edit-field">
                            <label for="telephone" class="abonne-edit-label">Téléphone</label>
                            <div class="abonne-edit-input-wrapper">
                                <input type="tel"
                                       name="telephone"
                                       id="telephone"
                                       value="{{ old('telephone', $abonne->telephone) }}"
                                       class="abonne-edit-input">
                                <div class="abonne-edit-input-icon">
                                    <svg class="abonne-edit-input-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('telephone')
                                <p class="abonne-edit-error">
                                    <svg class="abonne-edit-error-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="abonne-edit-field">
                            <label for="date_naissance" class="abonne-edit-label">Date de naissance</label>
                            <div class="abonne-edit-input-wrapper">
                                <input type="date"
                                       name="date_naissance"
                                       id="date_naissance"
                                       value="{{ old('date_naissance', $abonne->date_naissance ? $abonne->date_naissance->format('Y-m-d') : '') }}"
                                       class="abonne-edit-input">
                                <div class="abonne-edit-input-icon">
                                    <svg class="abonne-edit-input-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('date_naissance')
                                <p class="abonne-edit-error">
                                    <svg class="abonne-edit-error-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="abonne-edit-field abonne-edit-field-full">
                            <label for="adresse" class="abonne-edit-label">Adresse</label>
                            <div class="abonne-edit-textarea-wrapper">
                                <textarea name="adresse"
                                          id="adresse"
                                          rows="4"
                                          class="abonne-edit-textarea"
                                          placeholder="Entrez l'adresse complète de l'abonné...">{{ old('adresse', $abonne->adresse) }}</textarea>
                                <div class="abonne-edit-textarea-icon">
                                    <svg class="abonne-edit-textarea-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('adresse')
                                <p class="abonne-edit-error">
                                    <svg class="abonne-edit-error-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="abonne-edit-actions">
                    <a href="{{ route('abonnes.show', $abonne) }}" class="abonne-edit-cancel-btn">
                        <svg class="abonne-edit-cancel-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Annuler
                    </a>
                    <button type="submit" class="abonne-edit-submit-btn">
                        <svg class="abonne-edit-submit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Mettre à jour l'abonné
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/abonne-edit-pure.css') }}">
@endsection
