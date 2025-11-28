<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Provincias;
use App\Models\Candidatos;
use App\Models\Telegramas;


class Listas extends Model
{
    /** @use HasFactory<\Database\Factories\ListasFactory> */
    use HasFactory;
    protected $table = 'listas';


        /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public function provinciaRelacionada()
    {
        return $this->belongsTo(Provincias::class, 'provincia', 'provincia');
    }

    public function candidatosRelacionados()
    {
        return $this->hasMany(Candidatos::class, 'lista', 'lista');
    }

    public function telegramaRelacionado()
    {
        return $this->hasOne(Telegramas::class, 'id_lista', 'id');
    }




    protected $fillable = [
        'provincia',
        'cargo',
        'lista',
        'alianza',
    ];
}
