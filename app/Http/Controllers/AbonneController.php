<?php

namespace App\Http\Controllers;

use App\Models\Abonne;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AbonneController extends Controller
{
    /**
     * Afficher la liste des abonnés.
     */
    public function index()
    {
        $abonnes = Abonne::with(['emprunts' => function ($query) {
            $query->whereNull('date_retour_effective');
        }])->paginate(10);

        return view('abonnes.index', compact('abonnes'));
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        return view('abonnes.create');
    }

    /**
     * Enregistrer un nouvel abonné.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'classe' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
        ]);

        // Créer l'abonné
        $abonne = Abonne::create($data);

        // Enregistrer l'activité
        ActivityLog::logCreate(
            $abonne,
            'Création de l’abonné : ' . $abonne->nom . ' ' . $abonne->prenom
        );

        return redirect()->route('abonnes.index')
            ->with('success', 'Abonné ajouté avec succès');
    }

    /**
     * Afficher les détails d'un abonné.
     */
    public function show(Abonne $abonne)
    {
        $emprunts = $abonne->emprunts()
            ->with('livre')
            ->paginate(10);

        return view('abonnes.show', compact('abonne', 'emprunts'));
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Abonne $abonne)
    {
        return view('abonnes.edit', compact('abonne'));
    }

    /**
     * Modifier un abonné.
     */
    public function update(Request $request, Abonne $abonne)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'classe' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
        ]);

        // Sauvegarder les anciennes valeurs AVANT la modification
        $oldValues = $abonne->only([
            'nom',
            'prenom',
            'date_naissance',
            'classe',
            'telephone',
            'adresse',
        ]);

        // Modifier l'abonné
        $abonne->update($data);

        // Enregistrer l'activité avec anciennes et nouvelles valeurs
        ActivityLog::logUpdate(
            $abonne,
            $oldValues,
            'Modification de l’abonné : ' . $abonne->nom . ' ' . $abonne->prenom
        );

        return redirect()->route('abonnes.index')
            ->with('success', 'Abonné modifié avec succès');
    }

    /**
     * Supprimer un abonné.
     */
    public function destroy(Abonne $abonne)
    {
        // Sauvegarder les anciennes valeurs AVANT la suppression
        $oldValues = $abonne->only([
            'nom',
            'prenom',
            'date_naissance',
            'classe',
            'telephone',
            'adresse',
        ]);

        $nomComplet = $abonne->nom . ' ' . $abonne->prenom;

        // Supprimer l'abonné
        $abonne->delete();

        // Enregistrer l'activité
        ActivityLog::logDelete(
            $abonne,
            $oldValues,
            'Suppression de l’abonné : ' . $nomComplet
        );

        return redirect()->route('abonnes.index')
            ->with('success', 'Abonné supprimé avec succès');
    }
}

