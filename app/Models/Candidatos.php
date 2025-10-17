<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidatos extends Model
{
    
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'provincia',
        'cargo',
        'lista',
        'nombre',
        'orden_en_lista',
    ];
    
    
}
