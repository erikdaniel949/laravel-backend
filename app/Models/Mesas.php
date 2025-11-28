<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Provincias;
use App\Models\Telegramas;

class Mesas extends Model
{
    /** @use HasFactory<\Database\Factories\MesasFactory> */
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

    public function telegramas()
    {
        return $this->hasMany(Telegramas::class, 'id_mesa');
    }

    protected $primaryKey = 'id_mesa';

    protected $fillable = [
        'id_mesa',
        'provincia',
        'circuito',
        'establecimiento',
        'electores',
    ];
}
