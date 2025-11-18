<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Provincias;

class Listas extends Model
{
    /** @use HasFactory<\Database\Factories\ListasFactory> */
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

    protected $fillable = [
        'provincia',
        'cargo',
        'lista',
        'alianza',
    ];
}
