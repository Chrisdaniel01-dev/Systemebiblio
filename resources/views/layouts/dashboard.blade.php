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
                <div class="data names">
                    <span class="data-title">Nom d'utilisateur</span>
                    @forelse($activites as $activite)
                        <span class="data-list">{{ $activite->user_name }}</span>
                    @empty
                        <span class="data-list">Aucune activité récente</span>
                    @endforelse
                </div>
                <div class="data email">
                    <span class="data-title">Action</span>
                    @forelse($activites as $activite)
                        <span class="data-list">{{ $activite->action }}</span>
                    @empty
                        <span class="data-list">-</span>
                    @endforelse
                </div>
                <div class="data joined">
                    <span class="data-title">Heure</span>
                    @forelse($activites as $activite)
                        <span class="data-list">{{ $activite->time }}</span>
                    @empty
                        <span class="data-list">-</span>
                    @endforelse
                </div>
                <div class="data type">
                    <span class="data-title">Date</span>
                    @forelse($activites as $activite)
                        <span class="data-list">{{ $activite->date }}</span>
                    @empty
                        <span class="data-list">-</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection