<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprunt extends Model
{
    use HasFactory;

    protected $table = 'emprunt';

    protected $fillable = [
        'abonne_id',
        'livre_id',
        'date_debut',
        'date_fin',
        'date_retour_effective',
        'statut',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_retour' => 'date',
        'date_retour_effective' => 'date',
    ];

    public function abonne()
    {
        return $this->belongsTo(Abonne::class, 'abonne_id');
    }

    public function livre()
    {
        return $this->belongsTo(Livre::class, 'livre_id');
    }
}
