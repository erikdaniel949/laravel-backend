<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Provincias;
use App\Models\Listas;
use App\Models\Mesas;

class Telegramas extends Model
{
    /** @use HasFactory<\Database\Factories\TelegramasFactory> */
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

    public function mesaRelacionada()
    {
        return $this->belongsTo(Mesas::class, 'id_mesa', 'is_mesa');
    }

    protected $fillable = [
        'id_mesa',
        'provincia',
        'lista',
        'votos_diputados',
        'votos_senadores',
        'blancos',
        'nulos',
        'recurridos',
    ];
}
