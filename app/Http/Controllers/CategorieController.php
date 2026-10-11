<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        Categorie::create($data);
        return redirect()->route('categories.index')->with('success', 'Catégorie ajoutée avec succès');
    }

    public function show(Categorie $categorie)
    {
        $livres = $categorie->livres()->paginate(10);
        return view('categories.show', compact('categorie', 'livres'));
    }

    public function edit(Categorie $categorie)
    {
        return view('categories.edit', compact('categorie'));
    }

    public function update(Request $request, Categorie $categorie)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom,' . $categorie->id,
            'description' => 'nullable|string',
        ]);

        $categorie->update($data);
        return redirect()->route('categories.index')->with('success', 'Catégorie modifiée avec succès');
    }

    public function destroy(Categorie $categorie)
    {
        $categorie->delete();
        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès');
    }
}
