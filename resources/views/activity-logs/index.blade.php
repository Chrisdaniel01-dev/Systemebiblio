@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/activity-logs.css') }}">
@endsection

@section('title', 'Logs d\'Activité')

@section('content')

<div class="activity-logs-page">

    <div class="activity-card">

        <div class="activity-logs-header">

            <div class="activity-logs-title">
                <div class="activity-logs-title-icon">
                    <i class="uil uil-clipboard-notes"></i>
                </div>

                <div>
                    <h1>Logs d'Activité</h1>
                    <p>Historique des actions effectuées dans la bibliothèque</p>
                </div>
            </div>

            <div class="activity-logs-actions">

                <a href="{{ route('activity-logs.statistics') }}"
                   class="activity-btn activity-btn-primary">
                    <i class="uil uil-chart-bar"></i>
                    Statistiques
                </a>

                <a href="{{ route('activity-logs.export', request()->query()) }}"
                   class="activity-btn activity-btn-success">
                    <i class="uil uil-export"></i>
                    Exporter
                </a>

            </div>

        </div>

        <div class="activity-stats">

            <!-- Total Logs -->
            <div class="activity-stat">
                <div class="activity-stat-header">
                    <div>
                        <div class="activity-stat-number">
                            {{ number_format($stats['total_logs']) }}
                        </div>
                        <div class="activity-stat-label">
                            Total Logs
                        </div>
                    </div>

                    <div class="activity-stat-icon blue">
                        <i class="uil uil-file-search-alt"></i>
                    </div>
                </div>
            </div>

            <!-- Aujourd'hui -->
            <div class="activity-stat">
                <div class="activity-stat-header">
                    <div>
                        <div class="activity-stat-number">
                            {{ number_format($stats['today_logs']) }}
                        </div>
                        <div class="activity-stat-label">
                            Aujourd'hui
                        </div>
                    </div>

                    <div class="activity-stat-icon green">
                        <i class="uil uil-calendar-alt"></i>
                    </div>
                </div>
            </div>

            <!-- Cette semaine -->
            <div class="activity-stat">
                <div class="activity-stat-header">
                    <div>
                        <div class="activity-stat-number">
                            {{ number_format($stats['this_week_logs']) }}
                        </div>
                        <div class="activity-stat-label">
                            Cette semaine
                        </div>
                    </div>

                    <div class="activity-stat-icon yellow">
                        <i class="uil uil-calendar-slash"></i>
                    </div>
                </div>
            </div>

            <!-- Ce mois -->
            <div class="activity-stat">
                <div class="activity-stat-header">
                    <div>
                        <div class="activity-stat-number">
                            {{ number_format($stats['this_month_logs']) }}
                        </div>
                        <div class="activity-stat-label">
                            Ce mois
                        </div>
                    </div>

                    <div class="activity-stat-icon purple">
                        <i class="uil uil-chart"></i>
                    </div>
                </div>
            </div>

        </div>

        <form method="GET" class="activity-filters">

            <div class="activity-filter-grid">

                <!-- Utilisateur -->
                <div class="activity-filter-group">
                    <label for="user_id">Utilisateur</label>

                    <select id="user_id" name="user_id">
                        <option value="">Tous les utilisateurs</option>

                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Type d'action -->
                <div class="activity-filter-group">
                    <label for="action_type">Type d'action</label>

                    <select id="action_type" name="action_type">
                        <option value="">Toutes les actions</option>

                        @foreach($actionTypes as $type)
                            <option value="{{ $type }}"
                                {{ request('action_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Modèle -->
                <div class="activity-filter-group">
                    <label for="model_type">Modèle</label>

                    <select id="model_type" name="model_type">
                        <option value="">Tous les modèles</option>

                        @foreach($modelTypes as $type)
                            <option value="{{ $type }}"
                                {{ request('model_type') == $type ? 'selected' : '' }}>
                                {{ class_basename($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Statut -->
                <div class="activity-filter-group">
                    <label for="status">Statut</label>

                    <select id="status" name="status">
                        <option value="">Tous les statuts</option>

                        <option value="success"
                            {{ request('status') == 'success' ? 'selected' : '' }}>
                            Succès
                        </option>

                        <option value="error"
                            {{ request('status') == 'error' ? 'selected' : '' }}>
                            Erreur
                        </option>

                        <option value="warning"
                            {{ request('status') == 'warning' ? 'selected' : '' }}>
                            Attention
                        </option>
                    </select>
                </div>

                <!-- Date début -->
                <div class="activity-filter-group">
                    <label for="start_date">Date début</label>

                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ request('start_date') }}"
                    >
                </div>

                <!-- Date fin -->
                <div class="activity-filter-group">
                    <label for="end_date">Date fin</label>

                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ request('end_date') }}"
                    >
                </div>

            </div>

            <!-- Recherche + boutons -->
            <div class="activity-filter-bottom">

                <input
                    type="text"
                    name="search"
                    placeholder="Rechercher dans les logs..."
                    value="{{ request('search') }}"
                    class="activity-search"
                >

                <div class="activity-filter-actions">

                    <button type="submit" class="activity-filter-button filter">
                        <i class="uil uil-filter"></i>
                        Filtrer
                    </button>

                    <a
                        href="{{ route('activity-logs.index') }}"
                        class="activity-filter-button reset"
                    >
                        <i class="uil uil-times"></i>
                        Réinitialiser
                    </a>

                </div>

            </div>

        </form>

        <div class="activity-results">
            Affichage de {{ $logs->firstItem() ?? 0 }} à {{ $logs->lastItem() ?? 0 }} sur {{ $logs->total() }} résultats
        </div>

            <div class="activity-table-wrapper">

                <table class="activity-table">

                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Type d'action</th>
                            <th>Modèle</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th class="activity-actions-column">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($logs as $log)

                            <tr>

                                <!-- Utilisateur -->
                                <td>
                                    <div class="activity-user">
                                        <div class="activity-user-avatar">
                                            <i class="uil uil-user"></i>
                                        </div>

                                        <span>
                                            {{ $log->user ? $log->user->name : 'Système' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Type d'action -->
                                <td>
                                    @php
                                        $badgeClass = match($log->action_type) {
                                            'create', 'creation' => 'create',
                                            'update', 'modification' => 'update',
                                            'delete', 'suppression' => 'delete',
                                            'emprunt' => 'emprunt',
                                            'retour' => 'retour',
                                            'login', 'connexion' => 'login',
                                            'logout', 'deconnexion' => 'logout',
                                            'consultation' => 'consultation',
                                            'export' => 'export',
                                            'import' => 'import',
                                            default => 'other',
                                        };
                                    @endphp

                                    <span class="activity-badge {{ $badgeClass }}">
                                        {{ $log->action_type_label }}
                                    </span>
                                </td>

                                <!-- Modèle -->
                                <td>
                                    <span class="activity-model">
                                        {{ $log->model_name ?? '-' }}
                                    </span>
                                </td>

                                <!-- Description -->
                                <td class="activity-description"
                                    title="{{ $log->description }}">
                                    {{ Str::limit($log->description, 60) }}
                                </td>

                                <!-- Date -->
                                <td>
                                    <div class="activity-date">
                                        <div class="activity-date-main">
                                            {{ $log->created_at->format('d/m/Y') }}
                                        </div>

                                        <div class="activity-date-time">
                                            {{ $log->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="activity-actions-column">
                                    <div class="activity-row-actions">

                                        <a
                                            href="{{ route('activity-logs.show', $log) }}"
                                            class="activity-row-action view"
                                            title="Voir les détails"
                                        >
                                            <i class="uil uil-eye"></i>
                                        </a>

                                        <button
                                            type="button"
                                            onclick="showLogDetails({{ $log->id }})"
                                            class="activity-row-action info"
                                            title="Détails rapides"
                                        >
                                            <i class="uil uil-info-circle"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="activity-empty">

                                    <i class="uil uil-file-times"></i>

                                    <div class="activity-empty-title">
                                        Aucun log d'activité trouvé
                                    </div>

                                    <div class="activity-empty-text">
                                        Essayez de modifier vos critères de recherche
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="activity-pagination">
                {{ $logs->appends(request()->query())->links() }}
            </div>

    </div>

</div>

@endsection