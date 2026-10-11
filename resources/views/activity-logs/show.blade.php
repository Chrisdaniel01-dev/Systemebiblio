@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/activity-log-show.css') }}">
@endsection

@section('title', 'Détails du Log d\'Activité')

@section('content')

<div class="activity-show-page">

    {{-- =====================================================
         EN-TÊTE
    ====================================================== --}}
    <div class="activity-show-header">

        <div class="activity-show-title">

            <div class="activity-show-title-icon">
                <i class="uil uil-clipboard-notes"></i>
            </div>

            <div>
                <h1>Détails du Log d'Activité</h1>
                <p>Informations concernant cette activité</p>
            </div>

        </div>

        <a href="{{ route('activity-logs.index', request()->query()) }}"
           class="activity-show-back">

            <i class="uil uil-arrow-left"></i>
            Retour à la liste

        </a>

    </div>


    {{-- =====================================================
         INFORMATIONS PRINCIPALES
    ====================================================== --}}
    <div class="activity-show-card">

        <div class="activity-show-card-header">

            <i class="uil uil-info-circle"></i>

            <h2>
                Informations de l'activité
            </h2>

        </div>


        <div class="activity-info-list">

            {{-- Utilisateur --}}
            <div class="activity-info-row">

                <span class="activity-info-label">
                    Utilisateur
                </span>

                <span class="activity-info-value">
                    {{ $activityLog->user?->name ?? 'Système' }}
                </span>

            </div>


            {{-- Type d'action --}}
            <div class="activity-info-row">

                <span class="activity-info-label">
                    Type d'action
                </span>

                @php
                    $badgeClass = match($activityLog->action_type) {

                        'creation',
                        'create' => 'create',

                        'modification',
                        'update' => 'update',

                        'suppression',
                        'delete' => 'delete',

                        'emprunt' => 'emprunt',

                        'retour' => 'retour',

                        'connexion',
                        'login' => 'login',

                        'deconnexion',
                        'logout' => 'logout',

                        'consultation' => 'consultation',

                        'export' => 'export',

                        'import' => 'import',

                        default => 'other',
                    };
                @endphp


                <span class="activity-show-badge {{ $badgeClass }}">
                    {{ $activityLog->action_type_label }}
                </span>

            </div>


            {{-- Modèle --}}
            <div class="activity-info-row">

                <span class="activity-info-label">
                    Modèle
                </span>

                <span class="activity-info-value">
                    {{ $activityLog->model_name ?? class_basename($activityLog->model_type ?? '') ?: 'N/A' }}
                </span>

            </div>


            {{-- Date --}}
            <div class="activity-info-row">

                <span class="activity-info-label">
                    Date
                </span>

                <span class="activity-info-value">
                    {{ $activityLog->created_at?->format('d/m/Y à H:i') }}
                </span>

            </div>


            {{-- Statut --}}
            <div class="activity-info-row">

                <span class="activity-info-label">
                    Statut
                </span>


                <span class="activity-show-status {{ $activityLog->status }}">

                    @if($activityLog->status === 'success')

                        <i class="uil uil-check-circle"></i>
                        Succès

                    @elseif($activityLog->status === 'error')

                        <i class="uil uil-times-circle"></i>
                        Erreur

                    @else

                        <i class="uil uil-info-circle"></i>
                        {{ ucfirst($activityLog->status ?? 'N/A') }}

                    @endif

                </span>

            </div>

        </div>

    </div>


    {{-- =====================================================
         DESCRIPTION
    ====================================================== --}}
    @if($activityLog->description)

        <div class="activity-show-card">

            <div class="activity-show-card-header">

                <i class="uil uil-align-left"></i>

                <h2>
                    Description
                </h2>

            </div>


            <div class="activity-description-box">
                {{ $activityLog->description }}
            </div>

        </div>

    @endif


    {{-- =====================================================
         CHANGEMENTS
    ====================================================== --}}

    @if($activityLog->old_values || $activityLog->new_values)

        @php

            /*
             * Libellés lisibles pour l'utilisateur.
             */
            $fieldLabels = [

                'livre_id' => 'Livre',

                'abonne_id' => 'Abonné',

                'date_debut' => 'Date de début',

                'date_fin' => 'Date de fin prévue',

                'date_retour_effective' => 'Date de retour',

                'statut' => 'Statut',

                'quantite' => 'Quantité',

                'disponible' => 'Disponible',

                'titre' => 'Titre',

                'auteur' => 'Auteur',

            ];


            /*
             * Champs techniques qui ne doivent jamais
             * apparaître dans l'interface.
             */
            $hiddenFields = [

                'id',

                'created_at',

                'updated_at',

                'deleted_at',

                'categorie_id',

                'abonne',

                'livre',

            ];


            /*
             * Transforme les données techniques
             * en informations compréhensibles.
             */
            $formatValue = function ($key, $value) use ($hiddenFields) {

                /*
                 * Masquer les champs techniques.
                 */
                if (in_array($key, $hiddenFields)) {

                    return null;

                }


                /*
                 * Livre à partir de son ID.
                 */
                if ($key === 'livre_id') {

                    $livre = \App\Models\Livre::find($value);

                    return $livre?->titre ?? 'Livre inconnu';

                }


                /*
                 * Abonné à partir de son ID.
                 */
                if ($key === 'abonne_id') {

                    $abonne = \App\Models\Abonne::find($value);

                    return $abonne

                        ? $abonne->prenom . ' ' . $abonne->nom

                        : 'Abonné inconnu';

                }


                /*
                 * Dates.
                 */
                if (in_array($key, [

                    'date_debut',

                    'date_fin',

                    'date_retour_effective',

                ])) {

                    try {

                        return \Carbon\Carbon::parse($value)
                            ->format('d/m/Y');

                    } catch (\Exception $e) {

                        return $value;

                    }

                }


                /*
                 * Statut.
                 */
                if ($key === 'statut') {

                    return match ($value) {

                        'en cours' => 'En cours',

                        'retourne','retourné' => 'Retourné',

                        'annule','annulé' => 'Annulé',

                        default => ucfirst($value),

                    };

                }


                /*
                 * Valeurs booléennes.
                 */
                if (is_bool($value)) {

                    return $value ? 'Oui' : 'Non';

                }


                /*
                 * Valeur vide.
                 */
                if ($value === null || $value === '') {

                    return 'Non renseigné';

                }


                /*  Tableau. */
                if (is_array($value)) {

                    return implode(', ', $value);

                }


                return $value;

            };

        @endphp


        <div class="activity-show-card">

            <div class="activity-show-card-header">

                <i class="uil uil-exchange"></i>

                <h2>
                    Changements détectés
                </h2>

            </div>


            <div class="activity-changes-grid">


                {{-- =================================================
                     AVANT L'ACTION
                ================================================== --}}

                @if($activityLog->old_values)

                    <div class="activity-change-box old">

                        <div class="activity-change-header">

                            <i class="uil uil-minus-circle"></i>

                            <h3>
                                Avant l'action
                            </h3>

                        </div>


                        <div class="activity-readable-values">

                            @foreach($activityLog->old_values as $key => $value)

                                @php

                                    $formattedValue =
                                        $formatValue($key, $value);

                                @endphp


                                @if($formattedValue !== null)

                                    <div class="activity-readable-row">

                                        <span>
                                            {{ $fieldLabels[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}
                                        </span>

                                        <strong>
                                            {{ $formattedValue }}
                                        </strong>

                                    </div>

                                @endif

                            @endforeach

                        </div>

                    </div>

                @endif


                {{-- =================================================
                     APRÈS L'ACTION
                ================================================== --}}

                @if($activityLog->new_values)

                    <div class="activity-change-box new">

                        <div class="activity-change-header">

                            <i class="uil uil-plus-circle"></i>

                            <h3>
                                Après l'action
                            </h3>

                        </div>


                        <div class="activity-readable-values">

                            @foreach($activityLog->new_values as $key => $value)

                                @php

                                    $formattedValue =
                                        $formatValue($key, $value);

                                @endphp


                                @if($formattedValue !== null)

                                    <div class="activity-readable-row">

                                        <span>
                                            {{ $fieldLabels[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}
                                        </span>

                                        <strong>
                                            {{ $formattedValue }}
                                        </strong>

                                    </div>

                                @endif

                            @endforeach

                        </div>

                    </div>

                @endif

            </div>

        </div>

    @endif


    {{-- =====================================================
         ACTIONS
    ====================================================== --}}

    <div class="activity-show-actions">


        {{-- Logs de l'utilisateur --}}
        @if($activityLog->user)

            <a href="{{ route('activity-logs.by-user', $activityLog->user->id) }}"
               class="activity-show-btn secondary">

                <i class="uil uil-users-alt"></i>

                Logs de cet utilisateur

            </a>

        @endif


        {{-- Suppression --}}
        <form method="POST"
              action="{{ route('activity-logs.bulk-delete') }}"
              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce log ?')">

            @csrf

            @method('DELETE')


            <input type="hidden"
                   name="log_ids[]"
                   value="{{ $activityLog->id }}">


            <button type="submit"
                    class="activity-show-btn danger">

                <i class="uil uil-trash-alt"></i>

                Supprimer

            </button>

        </form>

    </div>

</div>

@endsection

