<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\Abonne;
use App\Models\ActivityLog;

class EmpruntController extends Controller
{
    public function index()
    {
        $emprunts = Emprunt::with(['abonne', 'livre'])->paginate(10);

        return view('emprunts.index', compact('emprunts'));
    }

    public function create()
    {
        // Livres disponibles
        $livres = Livre::where('disponible', 1)
            ->where('quantite', '>', 0)
            ->get();

        $abonnes = Abonne::all();

        return view('emprunts.create', compact('livres', 'abonnes'));
    }

    /**
     * Création d'un emprunt
     */
    public function store(Request $request)
    {

        $request->validate([
            'livre_nom' => 'required|string',
            'abonne_nom' => 'required|string',
            'date_emprunt' => 'required|date',
            'date_fin' => 'required|date|after:date_emprunt',
            'notes' => 'nullable|string',
        ]);

        

        // Recherche du livre
        $livre = Livre::where(
            'titre',
            'LIKE',
            '%' . $request->livre_nom . '%'
        )->first();

        if (!$livre) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'livre_nom' => 'Livre non trouvé.'
                ]);
        }

        // Vérifier disponibilité
        if ($livre->disponible != 1 || $livre->quantite <= 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'livre_nom' => 'Ce livre n\'est pas disponible actuellement.'
                ]);
        }

        // Recherche de l'abonné
        $abonne = Abonne::where(
            'nom',
            'LIKE',
            '%' . $request->abonne_nom . '%'
        )->first();

        if (!$abonne) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'abonne_nom' => 'Abonné non trouvé.'
                ]);
        }

        // Création de l'emprunt
        try {
            $emprunt = Emprunt::create([
                'livre_id' => $livre->id,
                'abonne_id' => $abonne->id,
                'date_debut' => $request->date_emprunt,
                'date_fin' => $request->date_fin,
                'statut' => 'en cours',
            ]);

           
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'error' => 'Erreur lors de la création de l\'emprunt : ' . $e->getMessage()
                ]);
        }

        // Diminuer la quantité disponible
        $livre->quantite = $livre->quantite - 1;

        if ($livre->quantite <= 0) {
            $livre->disponible = false;
        }

        $livre->save();

        // LOG : création de l'emprunt
        ActivityLog::logActivity(
            'emprunt',
            'Nouvel emprunt : "' . $livre->titre . '" par ' .
            $abonne->nom . ' ' . $abonne->prenom,
            $emprunt,
            null,
            $emprunt->toArray()
        );

        return redirect()
            ->route('emprunts.index')
            ->with('success', 'Emprunt créé avec succès.');
    }

    /**
     * Retour d'un livre
     */
    public function return(Request $request, Emprunt $emprunt)
    {
        return $this->effectuerRetour($emprunt);
    }

    /**
     * Deuxième méthode de retour utilisée éventuellement par les routes
     */
    public function retourner(Emprunt $emprunt)
    {
        return $this->effectuerRetour($emprunt);
    }

    /**
     * Logique commune pour le retour d'un livre
     */
    private function effectuerRetour(Emprunt $emprunt)
    {
        // Éviter un double retour
        if ($emprunt->date_retour_effective) {
            return redirect()->back()
                ->with('error', 'Ce livre a déjà été retourné.');
        }

        // Charger les relations
        $emprunt->load(['abonne', 'livre']);

        $livre = $emprunt->livre;
        $abonne = $emprunt->abonne;

        // Sauvegarder les anciennes valeurs avant modification
        $oldValues = $emprunt->only([
            'livre_id',
            'abonne_id',
            'date_debut',
            'date_fin',
            'date_retour_effective',
            'statut',
        ]);

        // Modifier l'emprunt
        $emprunt->date_retour_effective = now();
        $emprunt->statut = 'retourne';
        $emprunt->save();

        // Réapprovisionner le livre
        $livre->quantite = $livre->quantite + 1;
        $livre->disponible = 1;
        $livre->save();

        // LOG : retour du livre
        ActivityLog::logActivity(
            'retour',
            'Retour du livre : "' . $livre->titre . '" par ' .
            $abonne->nom . ' ' . $abonne->prenom,
            $emprunt,
            $oldValues,
            $emprunt->toArray()
        );

        return redirect()
            ->back()
            ->with('success', 'Livre retourné avec succès.');
    }

    public function show(Emprunt $emprunt)
    {
        $emprunt->load(['abonne', 'livre']);

        return view('emprunts.show', compact('emprunt'));
    }

    public function edit(Emprunt $emprunt)
    {
        $abonnes = Abonne::all();
        $livres = Livre::all();

        return view('emprunts.edit', compact(
            'emprunt',
            'abonnes',
            'livres'
        ));
    }

    /**
     * Modification d'un emprunt
     */
    public function update(Request $request, Emprunt $emprunt)
    {
        $data = $request->validate([
            'abonne_id' => 'required|exists:abonnes,id',
            'livre_id' => 'required|exists:livres,id',
            'date_emprunt' => 'required|date',
            'date_retour_prevue' => 'required|date|after:date_emprunt',
            'date_retour_effective' => 'nullable|date|after_or_equal:date_emprunt',
            'statut' => 'required|in:en cours,retourne,en retard',
        ]);

        // Sauvegarder les anciennes valeurs AVANT la modification
        $oldValues = $emprunt->only([
            'abonne_id',
            'livre_id',
            'date_debut',
            'date_fin',
            'date_retour_effective',
            'statut',
        ]);

        // Modifier l'emprunt
        $emprunt->update($data);

        // Charger les relations pour la description
        $emprunt->load(['abonne', 'livre']);

        // LOG : modification
        ActivityLog::logUpdate(
            $emprunt,
            $oldValues,
            'Modification de l\'emprunt : "' .
            $emprunt->livre->titre . '" pour ' .
            $emprunt->abonne->nom . ' ' .
            $emprunt->abonne->prenom
        );

        return redirect()
            ->route('emprunts.index')
            ->with('success', 'Emprunt modifié avec succès');
    }

    /**
     * Suppression d'un emprunt
     */
    public function destroy(Emprunt $emprunt)
    {
        // Charger les relations avant suppression
        $emprunt->load(['abonne', 'livre']);

        $livreTitre = $emprunt->livre->titre;
        $abonneNom = $emprunt->abonne->nom . ' ' . $emprunt->abonne->prenom;

        // Sauvegarder les anciennes valeurs AVANT suppression
        $oldValues = $emprunt->only([
            'livre_id',
            'abonne_id',
            'date_debut',
            'date_fin',
            'date_retour_effective',
            'statut',
        ]);

        // Supprimer l'emprunt
        $emprunt->delete();

        // LOG : suppression
        ActivityLog::logDelete(
            $emprunt,
            $oldValues,
            'Suppression de l\'emprunt : "' .
            $livreTitre . '" pour ' . $abonneNom
        );

        return redirect()
            ->route('emprunts.index')
            ->with('success', 'Emprunt supprimé avec succès');
    }
}

