<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;

class CategorieController extends Controller
{
    public function index(){ return Categorie::all(); }
    public function store(Request $r){ $d = $r->validate(['nom'=>'required|string']); return response()->json(Categorie::create($d),201); }
    public function show($id){ return Categorie::findOrFail($id); }
    public function update(Request $r,$id){ $c=Categorie::findOrFail($id); $c->update($r->all()); return response()->json($c); }
    public function destroy($id){ Categorie::findOrFail($id)->delete(); return response()->json(null,204); }
}
