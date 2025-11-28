<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Listas;
use App\Models\Candidatos;
use App\Models\Mesas;
use App\Models\Telegramas;

class Provincias extends Model
{
    // Definición de la clave primaria personalizada
    protected $primaryKey = 'provincia';  // Usamos 'provincia' como clave primaria

    // Relaciones con otros modelos (hasMany)
    public function listas()
    {
        return $this->hasMany(Listas::class, 'provincia', 'provincia');
    }

    public function candidatos()
    {
        return $this->hasMany(Candidatos::class, 'provincia', 'provincia');
    }

    public function mesas()
    {
        return $this->hasMany(Mesas::class, 'provincia', 'provincia');
    }

    public function telegramas()
    {
        return $this->hasMany(Telegramas::class, 'provincia', 'provincia');
    }

    // Propiedad para definir los campos rellenables
    protected $fillable = [
        'provincia'
    ];
}