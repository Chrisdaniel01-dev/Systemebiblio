# 📚 Systemebiblio

[🇬🇧 English](#-english) · [🇫🇷 Français](#-français)

---

## 🇬🇧 English

School library management system built with **Laravel 12** (PHP 8.2+), MySQL, Tailwind CSS and Chart.js. The interface is in French.

### Features

- **Books and categories**: title, author, category, total quantity, available copies, filters.
- **Subscribers (students)**: name, date of birth, class, phone, address.
- **Loans**: record loans and returns, with statuses *in progress*, *late* and *returned*.
- **Dashboard** with recent activities.
- **Statistics** with 6 Chart.js charts (books per category, loans over 12 months, most borrowed books, loan statuses, monthly loans vs. returns, availability).
- **Activity log** (`/admin/activity-logs`): list, export, filters, cleanup.
- Login / logout, user roles, dark mode.

### Installation

Requirements: PHP 8.2+, Composer, Node.js, MySQL (XAMPP), Git.

```bash
git clone https://github.com/Chrisdaniel01-dev/Systemebiblio.git
cd Systemebiblio
composer install
npm install
copy .env.example .env      # Linux / macOS: cp .env.example .env
php artisan key:generate
```

Start Apache and MySQL in XAMPP, then create an empty database `biblio_db` in phpMyAdmin and set it in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biblio_db
DB_USERNAME=root
DB_PASSWORD=
```

Create the tables, then the first administrator:

```bash
php artisan migrate
php artisan tinker
```

```php
App\Models\User::forceCreate([
    'name' => 'Administrator',
    'email' => 'your-email@example.com',
    'password' => bcrypt('choose-a-strong-password'),
    'role' => 'admin',
]);
```

Build the assets and start the app:

```bash
npm run build
php artisan serve
```

Open http://127.0.0.1:8000 and log in at `/login`.

### License

MIT. Author: [Chrisdaniel01-dev](https://github.com/Chrisdaniel01-dev).

---

## 🇫🇷 Français

Système de gestion de bibliothèque scolaire développé avec **Laravel 12** (PHP 8.2+), MySQL, Tailwind CSS et Chart.js. L'interface est en français.

### Fonctionnalités

- **Livres et catégories** : titre, auteur, catégorie, quantité totale, exemplaires disponibles, filtres.
- **Abonnés (élèves)** : nom, date de naissance, classe, téléphone, adresse.
- **Emprunts** : enregistrement des emprunts et des retours, avec les statuts *en cours*, *en retard* et *retourné*.
- **Tableau de bord** avec les activités récentes.
- **Statistiques** avec 6 graphiques Chart.js (livres par catégorie, emprunts sur 12 mois, livres les plus empruntés, statuts des emprunts, emprunts et retours par mois, disponibilité).
- **Journal d'activité** (`/admin/activity-logs`) : liste, export, filtres, nettoyage.
- Connexion / déconnexion, rôles utilisateur, mode sombre.

### Installation

Prérequis : PHP 8.2+, Composer, Node.js, MySQL (XAMPP), Git.

```bash
git clone https://github.com/Chrisdaniel01-dev/Systemebiblio.git
cd Systemebiblio
composer install
npm install
copy .env.example .env      # Linux / macOS : cp .env.example .env
php artisan key:generate
```

Démarrez Apache et MySQL dans XAMPP, créez une base vide `biblio_db` dans phpMyAdmin, puis renseignez-la dans `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biblio_db
DB_USERNAME=root
DB_PASSWORD=
```

Créez les tables, puis le premier administrateur :

```bash
php artisan migrate
php artisan tinker
```

```php
App\Models\User::forceCreate([
    'name' => 'Administrateur',
    'email' => 'votre-email@example.com',
    'password' => bcrypt('choisissez-un-mot-de-passe-solide'),
    'role' => 'admin',
]);
```

Compilez les assets et lancez l'application :

```bash

php artisan serve
```

Ouvrez http://127.0.0.1:8000 et connectez-vous sur `/login`.

### Licence

MIT. Auteur : Chrisdaniel01-dev.