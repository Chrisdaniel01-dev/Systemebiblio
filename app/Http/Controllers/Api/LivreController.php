<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Livre;

class LivreController extends Controller
{
    public function index()
    {
        return Livre::with('categorie')->get();
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'titre'=>'required|string|max:255',
            'auteur'=>'nullable|string|max:255',
            'categorie_id'=>'nullable|exists:categories,id',
            'quantite'=>'nullable|integer|min:0',
            'disponible'=>'nullable|integer|min:0',
        ]);
        return response()->json(Livre::create($data),201);
    }

    public function show($id){ return Livre::with('categorie')->findOrFail($id); }

    public function update(Request $req,$id)
    {
        $l = Livre::findOrFail($id);
        $l->update($req->all());
        return response()->json($l);
    }

    public function destroy($id)
    {
        Livre::findOrFail($id)->delete();
        return response()->json(null,204);
    }
}
