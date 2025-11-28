<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Provincias;
use App\Models\Listas;

class Telegramas extends Model
{
    /** @use HasFactory<\Database\Factories\TelegramasFactory> */
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

    public function mesa()
    {
        return $this->belongsTo(Mesas::class, 'id_mesa', 'id');
    }

    public function listaRelacionada()
    {
        return $this->belongsTo(Lista::class, 'id_lista', 'id');
    }



    protected $fillable = [
        'id_lista',
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
