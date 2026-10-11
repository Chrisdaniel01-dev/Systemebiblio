@extends('layouts.app')

@section('title', 'Statistiques des Logs d\'Activité')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Statistiques des Logs d'Activité</h1>
            <a href="{{ route('activity-logs.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                <i class="uil uil-arrow-left"></i> Retour aux logs
            </a>
        </div>

        <!-- Date Range Filter -->
        <form method="GET" class="mb-6 bg-gray-50 p-4 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        <i class="uil uil-filter"></i> Filtrer
                    </button>
                </div>
            </div>
        </form>

        <!-- Statistics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-blue-500 text-white p-4 rounded-lg">
                <div class="text-2xl font-bold">{{ number_format($statistics['total_activities']) }}</div>
                <div class="text-blue-100">Total Activités</div>
            </div>
            <div class="bg-green-500 text-white p-4 rounded-lg">
                <div class="text-2xl font-bold">{{ number_format(count($statistics['activities_by_type'])) }}</div>
                <div class="text-green-100">Types d'Actions</div>
            </div>
            <div class="bg-yellow-500 text-white p-4 rounded-lg">
                <div class="text-2xl font-bold">{{ number_format(count($statistics['activities_by_user'])) }}</div>
                <div class="text-yellow-100">Utilisateurs Actifs</div>
            </div>
            <div class="bg-purple-500 text-white p-4 rounded-lg">
                <div class="text-2xl font-bold">{{ $endDate->diffInDays($startDate) + 1 }}</div>
                <div class="text-purple-100">Jours Analysés</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Activities by Type -->
            <div class="bg-white border rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Répartition par Type d'Action</h3>
                <div class="space-y-3">
                    @foreach($statistics['activities_by_type'] as $type => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3
                                    @if($type === 'create') bg-green-500
                                    @elseif($type === 'update') bg-blue-500
                                    @elseif($type === 'delete') bg-red-500
                                    @elseif($type === 'login') bg-yellow-500
                                    @elseif($type === 'logout') bg-gray-500
                                    @else bg-purple-500
                                    @endif"></div>
                                <span class="capitalize">{{ $type }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-semibold mr-2">{{ number_format($count) }}</span>
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full"
                                         style="width: {{ ($count / max($statistics['activities_by_type'])) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Activities by User -->
            <div class="bg-white border rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Activité par Utilisateur</h3>
                <div class="space-y-3">
                    @foreach($statistics['activities_by_user'] as $userName => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3 bg-indigo-500"></div>
                                <span class="truncate">{{ $userName }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-semibold mr-2">{{ number_format($count) }}</span>
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-500 h-2 rounded-full"
                                         style="width: {{ ($count / max($statistics['activities_by_user'])) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="mt-8 bg-white border rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Activités Récentes</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left">Utilisateur</th>
                            <th class="px-4 py-2 text-left">Action</th>
                            <th class="px-4 py-2 text-left">Description</th>
                            <th class="px-4 py-2 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($statistics['recent_activities'] as $activity)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $activity->user ? $activity->user->name : 'Système' }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        @if($activity->action_type === 'create') bg-green-100 text-green-800
                                        @elseif($activity->action_type === 'update') bg-blue-100 text-blue-800
                                        @elseif($activity->action_type === 'delete') bg-red-100 text-red-800
                                        @elseif($activity->action_type === 'login') bg-yellow-100 text-yellow-800
                                        @elseif($activity->action_type === 'logout') bg-gray-100 text-gray-800
                                        @else bg-purple-100 text-purple-800
                                        @endif">
                                        {{ $activity->action_type_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ Str::limit($activity->description, 50) }}</td>
                                <td class="px-4 py-2">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Export Options -->
        <div class="mt-8 bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Exporter les Statistiques</h3>
            <div class="flex space-x-4">
                <a href="{{ route('activity-logs.export', ['format' => 'csv'] + request()->query()) }}"
                   class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                    <i class="uil uil-file-export"></i> Exporter CSV
                </a>
                <a href="{{ route('activity-logs.export', ['format' => 'json'] + request()->query()) }}"
                   class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    <i class="uil uil-file-export"></i> Exporter JSON
                </a>
                <a href="{{ route('activity-logs.export', ['format' => 'xml'] + request()->query()) }}"
                   class="bg-purple-500 text-white px-4 py-2 rounded-md hover:bg-purple-600">
                    <i class="uil uil-file-export"></i> Exporter XML
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
