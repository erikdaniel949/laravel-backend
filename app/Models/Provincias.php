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
    protected $primaryKey = 'provincia';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Relaciones con otros modelos (hasMany)
    public function listasRelacionadas()
    {
        return $this->hasMany(Listas::class, 'provincia', 'provincia');
    }

    public function candidatosRelacionados()
    {
        return $this->hasMany(Candidatos::class, 'provincia', 'provincia');
    }

    public function mesasRelacionadas()
    {
        return $this->hasMany(Mesas::class, 'provincia', 'provincia');
    }

    public function telegramasRelacionados()
    {
        return $this->hasMany(Telegramas::class, 'provincia', 'provincia');
    }

    // Propiedad para definir los campos rellenables
    protected $fillable = [
        'provincia'
    ];
}
