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

    public function provinciaRelacionada()
    {
        return $this->belongsTo(Provincias::class, 'provincia', 'provincia');
    }

    public function telegramasRelacionados()
    {
        return $this->belongsTo(Telegramas::class, 'id_mesa', 'id_mesa');
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
