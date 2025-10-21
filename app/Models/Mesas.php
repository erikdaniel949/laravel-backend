<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesas extends Model
{
    /** @use HasFactory<\Database\Factories\MesasFactory> */
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'id_mesa',
        'provincia',
        'circuito',
        'establecimiento',
        'electores',
    ];
}
