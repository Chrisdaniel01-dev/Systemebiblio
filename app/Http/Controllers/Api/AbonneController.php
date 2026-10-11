<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Abonne;

class AbonneController extends Controller
{
    public function index()
    {
        $abonnes = Abonne::all();
        return response()->json($abonnes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:abonnes,email',
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'adresse' => 'nullable|string',
        ]);

        $abonne = Abonne::create($validated);
        return response()->json($abonne, 201);
    }

    public function show(Abonne $abonne)
    {
        return response()->json($abonne);
    }

    public function update(Request $request, Abonne $abonne)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:abonnes,email,' . $abonne->id,
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'adresse' => 'nullable|string',
        ]);

        $abonne->update($validated);
        return response()->json($abonne);
    }

    public function destroy(Abonne $abonne)
    {
        $abonne->delete();
        return response()->json(['message' => 'Abonné supprimé avec succès']);
    }
}
