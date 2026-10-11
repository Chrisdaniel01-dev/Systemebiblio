<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Livre;
use App\Models\Abonne;
use App\Models\Emprunt;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Statistiques
        |--------------------------------------------------------------------------
        */

        $totalLivres = Livre::count();
        $totalAbonnes = Abonne::count();
        $totalEmprunts = Emprunt::count();


        /*
        |--------------------------------------------------------------------------
        | Activités récentes
        |--------------------------------------------------------------------------
        |
        | On utilise maintenant activity_logs.
        | Les dernières activités sont affichées en premier.
        |
        */

        $activites = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();


        return view('dashboard', compact(
            'totalLivres',
            'totalAbonnes',
            'totalEmprunts',
            'activites'
        ));
    }
}
