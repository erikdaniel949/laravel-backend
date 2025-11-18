<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Provincias;

class Candidatos extends Model
{
    
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public function provincia()
    {
        return $this->belongsTo(Provincias::class, 'provincia', 'provincia');
    }

    protected $fillable = [
        'provincia',
        'cargo',
        'lista',
        'nombre',
        'orden_en_lista',
    ];
    
    
}
