<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Provincias;
use App\Models\Listas;

class Candidatos extends Model
{
    
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public function provinciaRelacionada()
    {
        return $this->belongsTo(Provincias::class, 'provincia', 'provincia');
    }

    public function listaRelacionada()
    {
        return $this->belongsTo(Listas::class, 'lista', 'lista');
    }

    protected $fillable = [
        'provincia',
        'cargo',
        'lista',
        'nombre',
        'orden_en_lista',
    ];
    
    
}
