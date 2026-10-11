<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livre;
use App\Models\Abonne;
use App\Models\Emprunt;
use App\Models\Categorie;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        // Basic counts
        $totalLivres = Livre::count();
        $totalAbonnes = Abonne::count();
        $totalEmprunts = Emprunt::count();
        $totalCategories = Categorie::count();

        // Books by category for pie chart
        $booksByCategory = Categorie::withCount('livres')
            ->having('livres_count', '>', 0)
            ->get()
            ->map(function ($category) {
                return [
                    'category' => $category->nom,
                    'count' => $category->livres_count
                ];
            });

        // Loans over time (last 12 months) for line chart
        $loansOverTime = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Emprunt::whereYear('date_debut', $date->year)
                ->whereMonth('date_debut', $date->month)
                ->count();

            $loansOverTime->push([
                'month' => $date->format('M Y'),
                'count' => $count
            ]);
        }

        // Popular books (top 10 most borrowed)
        $popularBooks = Livre::withCount('emprunts')
            ->orderBy('emprunts_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($livre) {
                return [
                    'title' => $livre->titre,
                    'count' => $livre->emprunts_count
                ];
            });

        // Loan status distribution
        $loanStatus = [
            'active' => Emprunt::whereNull('date_retour_effective')->count(),
            'returned' => Emprunt::whereNotNull('date_retour_effective')->count(),
            'overdue' => Emprunt::whereNull('date_retour_effective')
                ->where('date_fin', '<', Carbon::now())
                ->count()
        ];

        // Monthly statistics for current year
        $monthlyStats = collect();
        for ($month = 1; $month <= 12; $month++) {
            $loans = Emprunt::whereYear('date_debut', Carbon::now()->year)
                ->whereMonth('date_debut', $month)
                ->count();

            $returns = Emprunt::whereYear('date_retour_effective', Carbon::now()->year)
                ->whereMonth('date_retour_effective', $month)
                ->count();

            $monthlyStats->push([
                'month' => Carbon::create()->month($month)->format('F'),
                'loans' => $loans,
                'returns' => $returns
            ]);
        }

        // Available vs borrowed books
        $availableBooks = Livre::where('disponible', true)->count();
        $borrowedBooks = Livre::where('disponible', false)->count();

        return view('statistics', compact(
            'totalLivres',
            'totalAbonnes',
            'totalEmprunts',
            'totalCategories',
            'booksByCategory',
            'loansOverTime',
            'popularBooks',
            'loanStatus',
            'monthlyStats',
            'availableBooks',
            'borrowedBooks'
        ));
    }
}
