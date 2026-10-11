<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonne extends Model
{
    use HasFactory;

    protected $table = 'abonne';

    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'classe',
        'telephone',
        'adresse',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function emprunts()
    {
        return $this->hasMany(Emprunt::class, 'abonne_id');
    }
}
