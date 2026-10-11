@extends('layouts.app')

@section('title', 'Gestion des Abonnés')

@section('styles')
    <!-- Page-specific CSS -->
    <link href="{{ asset('css/abonne.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="overview">
    <div class="title">
        <i class="uil uil-users-alt"></i>
        <span class="text">Gestion des Abonnés</span>
    </div>
</div>

<div class="top">
    <i class="uil uil-bars slidebar-toggle"></i>
    <div class="search-box">
        <i class="uil uil-search"></i>
        <input type="text" placeholder="Rechercher un abonné..." id="searchInput">
    </div>
</div>

<div class="filters">
    <div class="filter-group">
        <label for="statusFilter">Statut:</label>
        <select id="statusFilter">
            <option value="">Tous</option>
            <option value="Actif">Actif</option>
            <option value="Inactif">Inactif</option>
        </select>
    </div>
            <a href="{{ route('abonnes.create') }}" class="add-btn">+ Nouveau abonné</a>

</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="abonnes-container">
    <table id="abonnesTable">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Classe</th>
                <th>Date de naissance</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th>Emprunts actifs</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="abonnesTableBody">
            @foreach($abonnes as $abonne)
            <tr>
                <td>{{ $abonne->nom }}</td>
                <td>{{ $abonne->prenom }}</td>
                <td>{{ $abonne->classe }}</td>
                <td>{{ $abonne->date_naissance ? $abonne->date_naissance->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $abonne->telephone ?? 'N/A' }}</td>
                <td>{{ $abonne->adresse ?? 'N/A' }}</td>
                <td>
                    <span class="status-badge {{ $abonne->emprunts()->whereNull('date_retour_effective')->count() > 0 ? 'status-active' : 'status-inactive' }}">
                        {{ $abonne->emprunts()->whereNull('date_retour_effective')->count() > 0 ? 'Actif' : 'Inactif' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('abonnes.show', $abonne) }}" class="action-link">Voir</a>
                    <a href="{{ route('abonnes.edit', $abonne) }}" class="action-link">Modifier</a>
                    <form method="POST" action="{{ route('abonnes.destroy', $abonne) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-link delete-link" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet abonné ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination-container">
        {{ $abonnes->links() }}
    </div>
</div>


@push('scripts')
<script> </script>
@endpush
@endsection
