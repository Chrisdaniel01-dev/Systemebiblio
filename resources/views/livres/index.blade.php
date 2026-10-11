@extends('layouts.app')

@section('title', 'Gestion des Livres')

@section('styles')
    <link href="{{ asset('css/livre.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="overview">
        <div class="title">
            <i class="uil uil-book"></i>
            <span class="text">Gestion des Livres</span>
        </div>
    </div>

<div class="top">
    <i class="uil uil-bars slidebar-toggle"></i>
    <div class="search-box">
        <i class="uil uil-search"></i>
        <input type="text" placeholder="Rechercher un livre..." id="searchInput">
    </div>
</div>

    <div class="page-actions">
        <!-- LIEN VERS LA PAGE D'AJOUT -->
        
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="uil uil-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="filters">
        <div class="filter-group">
            <label for="categoryFilter">Catégorie :</label>
            <select id="categoryFilter">
                <option value="">Toutes</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->nom }}</option>
                @endforeach
            </select>
        </div>
                <a href="{{ route('livres.create') }}" class="add-btn"> + Nouveau livre </a>
    </div>

    <div class="books-container">
        @if($livres->count() > 0)
            <table id="booksTable" class="books-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Catégorie</th>
                        <th>Quantité</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id = "booksTableBody">
                    @foreach($livres as $livre)
                    <tr>
                        <td>{{ $livre->titre }}</td>
                        <td>{{ $livre->auteur }}</td>
                        <td>{{ $livre->categorie->nom ?? 'N/A' }}</td>
                        <td>{{ $livre->quantite }}</td>
                        <td>
                            <span class="status-badge {{ $livre->quantite > 0 ? 'status-available' : 'status-borrowed' }}">
                                {{ $livre->quantite > 0 ? 'Disponible' : 'Épuisé' }}
                            </span>
                        </td>
                        <td class="actions">
                            <!-- LIEN VERS LA PAGE DE MODIFICATION -->
                            <a href="{{ route('livres.edit', $livre->id) }}" class="action-btn edit-btn">
                                <i class="uil uil-edit"></i> Modifier
                            </a>
                            <form method="POST" action="{{ route('livres.destroy', $livre->id) }}" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete-btn" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')">
                                    <i class="uil uil-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">
                {{ $livres->links() }}
            </div>
        @else

        <!--  
            <div class="no-books">
                <i class="uil uil-book-open"></i>
                <h3>Aucun livre trouvé</h3>
                <p>Commencez par ajouter votre premier livre à la bibliothèque.</p>
                <a href="{{ route('livres.create') }}" class="add-btn">
                    <i class="uil uil-plus"></i> Ajouter un livre
                </a>
            </div>
            -->
        @endif
    </div>

@endsection