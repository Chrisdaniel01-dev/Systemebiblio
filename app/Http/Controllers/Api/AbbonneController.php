<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Abonne;

class AbonneController extends Controller
{
    public function index() { return Abonne::all(); }

    public function store(Request $req)
    {
        $data = $req->validate([
            'nom'=>'required|string|max:255',
            'prenom'=>'required|string|max:255',
            'date_naissance'=>'nullable|date',
            'classe'=>'nullable|string|max:255',
            'telephone'=>'nullable|string|max:255',
            'adresse'=>'nullable|string|max:255',
        ]);
        return response()->json(Abonne::create($data),201);
    }

    public function show($id) { return Abonne::findOrFail($id); }

    public function update(Request $req, $id)
    {
        $ab = Abonne::findOrFail($id);
        $ab->update($req->all());
        return response()->json($ab);
    }

    public function destroy($id)
    {
        Abonne::findOrFail($id)->delete();
        return response()->json(null,204);
    }
}
