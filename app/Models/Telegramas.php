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
        return $this->belongsTo(Mesas::class, 'id_mesa', 'id_mesa');
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

    /**
     * Valida que los votos de un telegrama no excedan los electores de la mesa.
     * @param array $data
     * @param int|null $telegramaId
     * @throws \Exception
     * @return bool
     */
    public static function validarConsistencia(array $data, $telegramaId = null)
    {
        $idMesa = $data['id_mesa'];
        $lista  = $data['lista'];

        $electores = Mesas::where('id_mesa', $idMesa)->value('electores');
        if (!$electores) {
            throw new \Exception("La mesa $idMesa no existe o no tiene electores asignados.");
        }

        $votosNuevos =
              ($data['votos_diputados'] ?? 0)
            + ($data['votos_senadores'] ?? 0)
            + ($data['blancos'] ?? 0)
            + ($data['nulos'] ?? 0)
            + ($data['recurridos'] ?? 0);

        $query = self::where('id_mesa', $idMesa)
            ->where('lista', '!=', $lista);

        if ($telegramaId) {
            $query->where('id', '!=', $telegramaId);
        }

        $votosExistentes = $query->get()->sum(function ($t) {
            return ($t->votos_diputados ?? 0)
                 + ($t->votos_senadores ?? 0)
                 + ($t->blancos ?? 0)
                 + ($t->nulos ?? 0)
                 + ($t->recurridos ?? 0);
        });

        if ($votosNuevos + $votosExistentes > $electores) {
            throw new \Exception("La suma de votos excede los electores de la mesa $idMesa.");
        }

        return true;
    }
}
