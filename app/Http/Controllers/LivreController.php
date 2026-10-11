<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Categorie;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LivreController extends Controller
{
    public function index()
    {
        $livres = Livre::with('categorie')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Categorie::all();

        return view('livres.index', compact('livres', 'categories'));
    }

    public function create()
    {
        $categories = Categorie::all();

        return view('livres.create', compact('categories'));
    }

    public function showJson(Livre $livre)
    {
        return response()->json($livre);
    }

    public function edit(Livre $livre)
    {
        $categories = Categorie::all();

        return view('livres.edit', compact('livre', 'categories'));
    }

    public function show(Livre $livre)
    {
        return view('livres.show', compact('livre'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
            'quantite_disponible' => 'required|integer|min:0'
        ]);

        $data = $validated;

        $data['quantite'] = $validated['quantite_disponible'];
        $data['disponible'] = $validated['quantite_disponible'] > 0 ? 1 : 0;

        $livre = Livre::create($data);

        // Enregistrer l'activité
        ActivityLog::logCreate(
            $livre,
            'Ajout du livre : ' . $livre->titre
        );

        return redirect()->route('livres.index')
            ->with('success', 'Livre ajouté avec succès!');
    }

    public function update(Request $request, Livre $livre)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
            'quantite_disponible' => 'required|integer|min:0'
        ]);

        // Sauvegarder les anciennes valeurs avant modification
        $oldValues = $livre->getOriginal();

        $data = $validated;

        $data['quantite'] = $validated['quantite_disponible'];
        $data['disponible'] = $validated['quantite_disponible'] > 0 ? 1 : 0;

        $livre->update($data);

        // Enregistrer l'activité
        ActivityLog::logUpdate(
            $livre,
            $oldValues,
            'Modification du livre : ' . $livre->titre
        );

        return redirect()->route('livres.index')
            ->with('success', 'Livre modifié avec succès!');
    }

    public function destroy(Livre $livre)
    {
        // Sauvegarder les anciennes valeurs avant suppression
        $oldValues = $livre->toArray();
        $titre = $livre->titre;

        $livre->delete();

        // Enregistrer l'activité
        ActivityLog::logDelete(
            $livre,
            $oldValues,
            'Suppression du livre : ' . $titre
        );

        return redirect()->route('livres.index')
            ->with('success', 'Livre supprimé avec succès!');
    }
}