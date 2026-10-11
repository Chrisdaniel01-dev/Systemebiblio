@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/abonne-show-pure.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="abonne-show-container">
    <div class="abonne-show-wrapper">
        <!-- Header Section with Hero -->
        <div class="abonne-hero-section">
            <div class="abonne-hero-card">
                <div class="abonne-hero-header">
                    <div class="abonne-hero-content">

                        <div class="abonne-hero-actions">
                            <a href="{{ route('abonnes.edit', $abonne) }}" class="abonne-action-btn abonne-action-btn-edit">
                                <svg class="abonne-btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Modifier
                            </a>
                            <a href="{{ route('abonnes.index') }}" class="abonne-action-btn abonne-action-btn-back">
                                <svg class="abonne-btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="abonne-content-grid">
            <!-- Personal Information -->
            <div class="abonne-info-section">
                <div class="abonne-info-card">
                    <div class="abonne-info-header">
                        <h2 class="abonne-info-title">
                            <svg class="abonne-info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informations personnelles
                        </h2>
                    </div>

                    <div class="abonne-info-content">
                        <div class="abonne-info-grid">
                            <div class="abonne-info-column">
                                <div class="abonne-info-item">
                                    <label class="abonne-info-label">Nom complet</label>
                                    <p class="abonne-info-value">{{ $abonne->nom }} {{ $abonne->prenom }}</p>
                                </div>
                                <div class="abonne-info-item">
                                    <label class="abonne-info-label">Clase</label>
                                    <p class="abonne-info-value abonne-info-with-icon">
                                        <svg class="abonne-info-value-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $abonne->classe }}
                                    </p>
                                </div>
                                <div class="abonne-info-item">
                                    <label class="abonne-info-label">Téléphone</label>
                                    <p class="abonne-info-value abonne-info-with-icon">
                                        <svg class="abonne-info-value-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        {{ $abonne->telephone ?? 'Non renseigné' }}
                                    </p>
                                </div>
                            </div>

                            <div class="abonne-info-column">
                                <div class="abonne-info-item">
                                    <label class="abonne-info-label">Date de naissance</label>
                                    <p class="abonne-info-value abonne-info-with-icon">
                                        <svg class="abonne-info-value-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $abonne->date_naissance ? $abonne->date_naissance->format('d/m/Y') : 'Non renseignée' }}
                                    </p>
                                </div>
                                <div class="abonne-info-item">
                                    <label class="abonne-info-label">Membre depuis</label>
                                    <p class="abonne-info-value abonne-info-with-icon">
                                        <svg class="abonne-info-value-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $abonne->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="abonne-info-item">
                                    <label class="abonne-info-label">Dernière mise à jour</label>
                                    <p class="abonne-info-value abonne-info-with-icon">
                                        <svg class="abonne-info-value-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        {{ $abonne->updated_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($abonne->adresse)
                        <div class="abonne-address-section">
                            <label class="abonne-info-label">Adresse</label>
                            <div class="abonne-address-box">
                                <p class="abonne-address-text">{{ $abonne->adresse }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Loan History Section -->
        @if($abonne->emprunts->count() > 0)
        <div class="abonne-loans-section">
            <div class="abonne-loans-card">
                <div class="abonne-loans-header">
                    <h2 class="abonne-loans-title">
                        <svg class="abonne-loans-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Historique des emprunts
                    </h2>
                </div>

                <div class="abonne-loans-table-container">
                    <table class="abonne-loans-table">
                        <thead class="abonne-loans-table-header">
                            <tr>
                                <th class="abonne-loans-table-head">Livre</th>
                                <th class="abonne-loans-table-head">Date d'emprunt</th>
                                <th class="abonne-loans-table-head">Date de retour prévue</th>
                                <th class="abonne-loans-table-head">Date de retour effective</th>
                                <th class="abonne-loans-table-head">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="abonne-loans-table-body">
                            @foreach($abonne->emprunts as $emprunt)
                            <tr class="abonne-loans-table-row">
                                <td class="abonne-loans-table-cell">
                                    <div class="abonne-loan-book">
                                        <div class="abonne-loan-book-icon">
                                            <svg class="abonne-loan-book-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                        <div class="abonne-loan-book-info">
                                            <div class="abonne-loan-book-title">{{ $emprunt->livre->titre }}</div>
                                            <div class="abonne-loan-book-author">{{ $emprunt->livre->auteur }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="abonne-loans-table-cell">{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                                <td class="abonne-loans-table-cell">{{ $emprunt->date_retour_prevue->format('d/m/Y') }}</td>
                                <td class="abonne-loans-table-cell">{{ $emprunt->date_retour_effective ? $emprunt->date_retour_effective->format('d/m/Y') : 'Non retourné' }}</td>
                                <td class="abonne-loans-table-cell">
                                    <span class="abonne-loan-status {{ $emprunt->date_retour_effective ? 'abonne-loan-status-returned' : 'abonne-loan-status-active' }}">
                                        <svg class="abonne-loan-status-icon" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $emprunt->date_retour_effective ? 'Retourné' : 'En cours' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/abonne-show-pure.css') }}">
@endsection
