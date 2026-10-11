<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livre extends Model
{
    use HasFactory;

    protected $table = 'livres';

    protected $fillable = [
        'titre',
        'auteur',
        'categorie_id',
        'quantite',
        'disponible',
    ];

    protected $casts = [
        'disponible' => 'boolean',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function emprunts()
    {
        return $this->hasMany(Emprunt::class, 'livre_id');
    }

    // Accessor for quantite_disponible (used by views)
    public function getQuantiteDisponibleAttribute()
    {
        return $this->quantite;
    }

    // Mutator for quantite_disponible (when setting from forms)
    public function setQuantiteDisponibleAttribute($value)
    {
        $this->attributes['quantite'] = $value;
        $this->attributes['disponible'] = $value > 0 ? 1 : 0;
    }
}
