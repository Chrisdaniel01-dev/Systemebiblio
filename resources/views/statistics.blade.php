@extends('layouts.app')

@section('title', 'Statistiques du Système')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/statistics.css') }}">
@endsection

@section('content')
<div class="top">
    <i class="uil uil-bars slidebar-toggle"></i>

    <div class="search-box">
        <i class="uil uil-search"></i>
        <input type="text" placeholder="Rechercher dans les statistiques...">
    </div>
    <img src="{{ asset('images/images (1).jpg') }}" alt="">
</div>

<div class="dash-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-chart"></i>
            <span class="text">Statistiques Complètes du Système</span>
        </div>

        <!-- Summary Cards -->
        <div class="boxes">
            <div class="box box1">
                <i class="uil uil-books"></i>
                <span class="text">Total Livres</span>
                <span class="number">{{ $totalLivres }}</span>
            </div>
            <div class="box box2">
                <i class="uil uil-user-circle"></i>
                <span class="text">Total Abonnés</span>
                <span class="number">{{ $totalAbonnes }}</span>
            </div>
            <div class="box box3">
                <i class="uil uil-exchange"></i>
                <span class="text">Total Emprunts</span>
                <span class="number">{{ $totalEmprunts }}</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <div class="chart-row">
            <!-- Books by Category -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3><i class="uil uil-books"></i> Livres par Catégorie</h3>
                </div>
                <div class="chart-wrapper">
                    <canvas id="booksByCategoryChart"></canvas>
                </div>
            </div>

            <!-- Loans Over Time -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3><i class="uil uil-chart-line"></i> Évolution des Emprunts</h3>
                </div>
                <div class="chart-wrapper">
                    <canvas id="loansOverTimeChart"></canvas>
                </div>
            </div>
        </div>

        <div class="chart-row">
            <!-- Popular Books -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3><i class="uil uil-star"></i> Livres les Plus Empruntés</h3>
                </div>
                <div class="chart-wrapper">
                    <canvas id="popularBooksChart"></canvas>
                </div>
            </div>

            <!-- Loan Status -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3><i class="uil uil-exchange"></i> Statut des Emprunts</h3>
                </div>
                <div class="chart-wrapper">
                    <canvas id="loanStatusChart"></canvas>
                </div>
            </div>
        </div>

        <div class="chart-row">
            <!-- Monthly Statistics -->
            <div class="chart-container full-width">
                <div class="chart-header">
                    <h3><i class="uil uil-calendar-alt"></i> Statistiques Mensuelles ({{ date('Y') }})</h3>
                </div>
                <div class="chart-wrapper">
                    <canvas id="monthlyStatsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="chart-row">
            <!-- Availability Status -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3><i class="uil uil-check-circle"></i> Disponibilité des Livres</h3>
                </div>
                <div class="chart-wrapper">
                    <canvas id="availabilityChart"></canvas>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3><i class="uil uil-info-circle"></i> Résumé Rapide</h3>
                </div>
                <div class="quick-stats">
                    <div class="stat-item">
                        <span class="stat-label">Livres Disponibles:</span>
                        <span class="stat-value">{{ $availableBooks }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Livres Empruntés:</span>
                        <span class="stat-value">{{ $borrowedBooks }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Taux de Disponibilité:</span>
                        <span class="stat-value">{{ $totalLivres > 0 ? round(($availableBooks / $totalLivres) * 100, 1) : 0 }}%</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Emprunts Actifs:</span>
                        <span class="stat-value">{{ $loanStatus['active'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Books by Category Chart
    const booksByCategoryCtx = document.getElementById('booksByCategoryChart').getContext('2d');
    new Chart(booksByCategoryCtx, {
        type: 'pie',
        data: {
            labels: @json($booksByCategory->pluck('category')),
            datasets: [{
                data: @json($booksByCategory->pluck('count')),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                    '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                title: {
                    display: true,
                    text: 'Répartition par Catégorie'
                }
            }
        }
    });

    // Loans Over Time Chart
    const loansOverTimeCtx = document.getElementById('loansOverTimeChart').getContext('2d');
    new Chart(loansOverTimeCtx, {
        type: 'line',
        data: {
            labels: @json($loansOverTime->pluck('month')),
            datasets: [{
                label: 'Nombre d\'emprunts',
                data: @json($loansOverTime->pluck('count')),
                borderColor: '#36A2EB',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Évolution Mensuelle'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Popular Books Chart
    const popularBooksCtx = document.getElementById('popularBooksChart').getContext('2d');
    new Chart(popularBooksCtx, {
        type: 'bar',
        data: {
            labels: @json($popularBooks->pluck('title')),
            datasets: [{
                label: 'Nombre d\'emprunts',
                data: @json($popularBooks->pluck('count')),
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Top 10 des Livres'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });

    // Loan Status Chart
    const loanStatusCtx = document.getElementById('loanStatusChart').getContext('2d');
    new Chart(loanStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Actifs', 'Retournés', 'En Retard'],
            datasets: [{
                data: [
                    {{ $loanStatus['active'] ?? 0 }},
                    {{ $loanStatus['returned'] ?? 0 }},
                    {{ $loanStatus['overdue'] ?? 0 }}
                ],
                backgroundColor: [
                    '#36A2EB', // Active - Blue
                    '#4BC0C0', // Returned - Teal
                    '#FF6384'  // Overdue - Red
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                title: {
                    display: true,
                    text: 'Statut des Emprunts'
                }
            }
        }
    });

    // Monthly Statistics Chart
    const monthlyStatsCtx = document.getElementById('monthlyStatsChart').getContext('2d');
    new Chart(monthlyStatsCtx, {
        type: 'bar',
        data: {
            labels: @json($monthlyStats->pluck('month')),
            datasets: [
                {
                    label: 'Emprunts',
                    data: @json($monthlyStats->pluck('loans')),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Retours',
                    data: @json($monthlyStats->pluck('returns')),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Emprunts vs Retours Mensuels'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });

    // Availability Chart
    const availabilityCtx = document.getElementById('availabilityChart').getContext('2d');
    new Chart(availabilityCtx, {
        type: 'pie',
        data: {
            labels: ['Disponibles', 'Empruntés'],
            datasets: [{
                data: [{{ $availableBooks }}, {{ $borrowedBooks }}],
                backgroundColor: [
                    '#4BC0C0', // Available - Teal
                    '#FF9F40'  // Borrowed - Orange
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                title: {
                    display: true,
                    text: 'Disponibilité des Livres'
                }
            }
        }
    });
});
</script>
@endsection
@endsection
