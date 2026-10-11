<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bibliothèque')</title>
    <link rel="stylesheet" href="{{ asset('css/dash.css') }}">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    @yield('styles')
</head>
<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image">
                <img src="{{ asset('images/LOGO.png') }}" alt="">
            </div>
            <span class="logo-name">Bibliothèque</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li><a href=" /dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="uil uil-estate"></i>
                    <span class="link-name">Tableau de Bord</span>
                </a></li>
                <li><a href="{{ route('livres.index') }}" class="{{ request()->is('livres*') ? 'active' : '' }}">
                    <i class="uil uil-books"></i>
                    <span class="link-name">Livres</span>
                </a></li>
                <li><a href="{{ route('abonnes.index') }}" class="{{ request()->is('abonnes*') ? 'active' : '' }}">
                    <i class="uil uil-users-alt"></i>
                    <span class="link-name">Abonnés</span>
                </a></li>
                <li><a href="{{ route('emprunts.index') }}" class="{{ request()->is('emprunts*') ? 'active' : '' }}">
                    <i class="uil uil-exchange"></i>
                    <span class="link-name">Emprunts</span>
                </a></li>
                <li><a href="{{ route('activity-logs.index') }}" class="{{ request()->is('admin/activity-logs*') ? 'active' : '' }}">
                    <i class="uil uil-clipboard-notes"></i>
                    <span class="link-name">Logs d'Activité</span>
                </a></li>
                <li><a href="{{ route('statistics.index') }}" class="{{ request()->is('statistics') ? 'active' : '' }}">
                    <i class="uil uil-chart"></i>
                    <span class="link-name">Statistiques</span>
                </a></li>
            </ul>

            <ul class="logout-mode">
                <li><a href="#">
                    <i class="uil uil-signout"></i>
                    <span class="link-name">Déconnexion</span>
                </a></li>

                <li class="mode">
                    <a href="#">
                        <i class="uil uil-moon"></i>
                        <span class="link-name">Mode Sombre</span>
                    </a>
                    <div class="mode-toggle">
                        <span class="switch"></span> 
                    </div>    
                </li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        @yield('content')
    </section>

    <script src="{{ asset('js/dash.js') }}"></script>
    @yield('scripts')
</body>
</html>