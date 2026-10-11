@extends('layouts.app')

@section('title', 'Gestion des Emprunts')

@section('styles')
    <!-- Page-specific CSS -->
    <link href="{{ asset('css/emprunt.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="overview">
    <div class="title">
        <i class="uil uil-exchange"></i>
        <span class="text">Gestion des Emprunts</span>
    </div>
</div>

<div class="top">
    <i class="uil uil-bars slidebar-toggle"></i>
    <div class="search-box">
        <i class="uil uil-search"></i>
        <input type="text" placeholder="Rechercher un emprunt..." id="searchInput">
    </div>
    <img src="{{ asset('images/images (1).jpg') }}" alt="">
</div>

<div class="filters">
    <div class="filter-group">
        <label for="statusFilter">Statut:</label>
        <select id="statusFilter">
            <option value="">Tous</option>
            <option value="En cours">En cours</option>
            <option value="En retard">En retard</option>
            <option value="Retourné">Retourné</option>
        </select>
    </div>
        <a href="{{ route('emprunts.create') }}" class="add-btn">+ Nouveau emprunt</a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="emprunts-container">
    <table id="empruntsTable">
        <thead>
            <tr>
                <th>Livre</th>
                <th>Abonné</th>
                <th>Date d'emprunt</th>
                <th>Date de retour prévue</th>
                <th>Date de retour effective</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="empruntsTableBody">
            @foreach($emprunts as $emprunt)
            <tr>
                <td>{{ $emprunt->livre->titre }}</td>
                <td>
                    <div>{{ $emprunt->abonne->nom }} {{ $emprunt->abonne->prenom }}</div>
                    <small class="text-muted">{{ $emprunt->abonne->classe }}</small>
                </td>
                <td>{{ $emprunt->date_debut->format('d/m/Y') }}</td>
                <td>{{ $emprunt->date_fin->format('d/m/Y') }}</td>
                <td>{{ $emprunt->date_retour_effective ? $emprunt->date_retour_effective->format('d/m/Y') : 'Non retourné' }}</td>
                <td>
                    <span class="status-badge {{ $emprunt->date_retour_effective ? 'status-retourne' : ($emprunt->date_fin < now() ? 'status-retard' : 'status-actif') }}">
                        @if($emprunt->date_retour_effective)
                            Retourné
                        @elseif($emprunt->date_fin < now())
                            En retard
                        @else
                            En cours
                        @endif
                    </span>
                </td>
                <td>
                    <a href="{{ route('emprunts.show', $emprunt) }}" class="action-link">Voir</a>
                    <a href="{{ route('emprunts.edit', $emprunt) }}" class="action-link">Modifier</a>
                    @if(!$emprunt->date_retour_effective)
                        <form method="POST" action="{{ route('emprunts.return', $emprunt) }}" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="action-link retour-link">Marquer retourné</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('emprunts.destroy', $emprunt) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-link delete-link" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet emprunt ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination-container">
        {{ $emprunts->links() }}
    </div>
</div>
@endsection
