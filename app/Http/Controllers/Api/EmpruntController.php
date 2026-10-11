<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Emprunt;
use App\Models\Livre;
use Illuminate\Support\Facades\DB;

class EmpruntController extends Controller
{
    public function index()
    {
        return Emprunt::with(['abonne','livre'])->get();
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'abonne_id'=>'required|exists:abonne,id',
            'livre_id'=>'required|exists:livres,id',
            'date_debut'=>'nullable|date',
            'date_fin'=>'nullable|date',
        ]);

        return DB::transaction(function() use($data) {
            $livre = Livre::findOrFail($data['livre_id']);
            if (($livre->disponible ?? 0) <= 0) {
                return response()->json(['message'=>'Livre non disponible'], 422);
            }
            // create emprunt
            $emprunt = Emprunt::create([
                'abonne_id' => $data['abonne_id'],
                'livre_id' => $data['livre_id'],
                'date_debut' => $data['date_debut'] ?? now()->toDateString(),
                'date_fin' => $data['date_fin'] ?? null,
                'statut' => 'en cours'
            ]);

            // decrement disponible
            $livre->decrement('disponible', 1);
            return response()->json($emprunt, 201);
        });
    }

    public function show($id)
    {
        return Emprunt::with(['abonne','livre'])->findOrFail($id);
    }

    public function update(Request $req, $id)
    {
        $data = $req->validate([
            'date_retour'=>'nullable|date',
            'statut'=>'nullable|string',
            'date_fin'=>'nullable|date',
        ]);

        return DB::transaction(function() use($id,$data) {
            $e = Emprunt::findOrFail($id);
            $prevStatut = $e->statut;
            $e->fill($data);
            $e->save();

            // si on marque retourné et qu'avant c'était 'en cours', on incrémente disponible
            if (($data['statut'] ?? null) && str_contains(strtolower($data['statut']), 'retour')) {
                if (strtolower($prevStatut) !== 'retourné' && strtolower($prevStatut) !== 'retour') {
                    $livre = Livre::findOrFail($e->livre_id);
                    $livre->increment('disponible', 1);
                }
            }

            // si on ajoute date_retour et statut pas fourni, on peut aussi incrémenter
            if (!empty($data['date_retour']) && (strtolower($prevStatut) === 'en cours')) {
                $livre = Livre::findOrFail($e->livre_id);
                $livre->increment('disponible', 1);
            }

            return response()->json($e);
        });
    }

    public function destroy($id)
    {
        return DB::transaction(function() use($id) {
            $e = Emprunt::findOrFail($id);
            // si emprunt en cours, restituer la dispo
            if (strtolower($e->statut) === 'en cours') {
                $livre = Livre::findOrFail($e->livre_id);
                $livre->increment('disponible', 1);
            }
            $e->delete();
            return response()->json(null,204);
        });
    }
}
