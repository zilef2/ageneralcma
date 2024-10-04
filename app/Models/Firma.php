<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firma extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'user_id',
        'inspeccion_id',
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userName()
    {
        $user = $this->user()->first();
        if($user)
            return $user;
        return '';
    }
}
