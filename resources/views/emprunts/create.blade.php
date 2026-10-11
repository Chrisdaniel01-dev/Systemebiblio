@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/emprunt.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="overview">
    <div class="title">
        <i class="uil uil-plus"></i>
        <span class="text">Nouveau Emprunt</span>
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
    <button class="add-btn" type="button" onclick="window.history.back()">
        Retour à la liste
    </button>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="emprunts-container">

    <form id="empruntForm"
          method="POST"
          action="{{ route('emprunts.store') }}">

        @csrf

        {{-- LIVRE --}}
        <div class="form-group">
            <label for="livre_nom">Nom du livre :</label>

            <input
                type="text"
                id="livre_nom"
                name="livre_nom"
                placeholder="Tapez le nom du livre..."
                value="{{ old('livre_nom') }}"
                autocomplete="off"
                required
            >

            <div id="livre-suggestions" class="suggestions"></div>
        </div>

        {{-- ABONNÉ --}}
        <div class="form-group">
            <label for="abonne_nom">Nom de l'abonné :</label>

            <input
                type="text"
                id="abonne_nom"
                name="abonne_nom"
                placeholder="Tapez le nom de l'abonné..."
                value="{{ old('abonne_nom') }}"
                autocomplete="off"
                required
            >

            <div id="abonne-suggestions" class="suggestions"></div>
        </div>

        {{-- DATE EMPRUNT --}}
        <div class="form-group">
            <label for="date_emprunt">
                Date d'emprunt :
            </label>

            <input
                type="date"
                id="date_emprunt"
                name="date_emprunt"
                value="{{ old('date_emprunt', date('Y-m-d')) }}"
                required
            >
        </div>

        {{-- DATE RETOUR --}}
        <div class="form-group">
            <label for="date_fin">
                Date de retour prévue :
            </label>

            <input
                type="date"
                id="date_fin"
                name="date_fin"
                value="{{ old('date_fin', date('Y-m-d', strtotime('+15 days'))) }}"
                required
            >
        </div>

        <div class="form-actions">
            <button type="button" onclick="window.history.back()">Annuler</button>

            <button type="submit" name="test_submit" value="1">
                CRÉER L'EMPRUNT
            </button>
        </div>

    </form>

</div>



@endsection