@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
    <div class="top">
        <i class="uil uil-bars slidebar-toggle"></i>
        
        <div class="search-box">
            <i class="uil uil-search"></i>
            <input type="text" placeholder="Rechercher ici...">
        </div>
        <img src="{{ asset('images/images (1).jpg') }}" alt="">
    </div>

    <div class="dash-content">
        <div class="overview">
            <div class="title">
                <i class="uil uil-tachometer-fast-alt"></i>
                <span class="text">Tableau de Bord</span>
            </div>
            <div class="boxes">
                <div class="box box1">
                    <i class="uil uil-books"></i>
                    <span class="text">Livres total</span>
                    <span class="number">{{ $totalLivres ?? 0 }}</span>
                </div>
                <div class="box box2">
                    <i class="uil uil-user-circle"></i>
                    <span class="text">Abonnés total</span>
                    <span class="number">{{ $totalAbonnes ?? 0 }}</span>
                </div>
                <div class="box box3">
                    <i class="uil uil-exchange"></i>
                    <span class="text">Emprunts total</span>
                    <span class="number">{{ $totalEmprunts ?? 0 }}</span>
                </div>
            </div>
        </div>

        
        <div class="activity">

            <div class="title">
                <i class="uil uil-clock-three"></i>
                <span class="text">Activités récentes</span>
            </div>

            <div class="activity-data">

                {{-- Utilisateur --}}
                <div class="data names">

                    <span class="data-title">
                        Utilisateur
                    </span>

                    @forelse($activites as $activite)

                        <span class="data-list">
                            {{ $activite->user?->name ?? 'Système' }}
                        </span>

                    @empty

                        <span class="data-list">
                            Aucune activité récente
                        </span>

                    @endforelse

                </div>


                {{-- Action --}}
                <div class="data">

                    <span class="data-title">
                        Action
                    </span>

                    @forelse($activites as $activite)

                        @php
                            $actionClass = match($activite->action_type) {
                                'creation', 'create' => 'creation',
                                'modification', 'update' => 'modification',
                                'suppression', 'delete' => 'suppression',
                                'emprunt' => 'emprunt',
                                'retour' => 'retour',
                                'connexion', 'login' => 'connexion',
                                'deconnexion', 'logout' => 'deconnexion',
                                'consultation' => 'consultation',
                                'export' => 'export',
                                'import' => 'import',
                                default => 'autre',
                            };
                        @endphp

                        <span class="data-list">
                            <span class="dashboard-action-badge {{ $actionClass }}">
                                {{ $activite->action_type_label }}
                            </span>
                        </span>

                    @empty

                        <span class="data-list">
                            -
                        </span>

                    @endforelse

                </div>


                {{-- Description --}}
                <div class="data activity-description-column">

                    <span class="data-title">
                        Description
                    </span>

                    @forelse($activites as $activite)

                        <span
                            class="data-list activity-description"
                            title="{{ $activite->description }}"
                        >
                            {{ \Illuminate\Support\Str::limit($activite->description, 55) }}
                        </span>

                    @empty

                        <span class="data-list">
                            -
                        </span>

                    @endforelse

                </div>


                {{-- Date --}}
                <div class="data">

                    <span class="data-title">
                        Date
                    </span>

                    @forelse($activites as $activite)

                        <span class="data-list">

                            @if($activite->created_at)

                                {{ $activite->created_at->format('d/m/Y') }}

                            @else

                                -

                            @endif

                        </span>

                    @empty

                        <span class="data-list">
                            -
                        </span>

                    @endforelse

                </div>


                {{-- Heure --}}
                <div class="data">

                    <span class="data-title">
                        Heure
                    </span>

                    @forelse($activites as $activite)

                        <span class="data-list">

                            @if($activite->created_at)

                                {{ $activite->created_at->format('H:i') }}

                            @else

                                -

                            @endif

                        </span>

                    @empty

                        <span class="data-list">
                            -
                        </span>

                    @endforelse

                </div>

            </div>

        </div>

    </div>
@endsection