<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';

    public $timestamps = false; // si table logs n'a pas updated_at ; ajuste si nécessaire

    protected $fillable = [
        'users_id',
        'action',
        'created_at',
    ];

    protected $casts = [
    'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
