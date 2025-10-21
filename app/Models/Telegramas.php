<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telegramas extends Model
{
    /** @use HasFactory<\Database\Factories\TelegramasFactory> */
    use HasFactory;
        /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

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
