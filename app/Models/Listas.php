<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Provincias;
use App\Models\Telegramas;

class Listas extends Model
{
    /** @use HasFactory<\Database\Factories\ListasFactory> */
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

    public function TelegramasRelacionados()
    {
        return $this->hasMany(Telegramas::class, 'lista', 'lista');
    }

    protected $fillable = [
        'provincia',
        'cargo',
        'lista',
        'alianza',
    ];
}
