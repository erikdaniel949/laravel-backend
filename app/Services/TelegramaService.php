<?php

namespace App\Services;

use App\Models\Mesas;
use App\Models\Telegramas;

class TelegramaService
{
    /**
     * Valida que los votos del telegrama no superen los electores de la mesa.
     * Acepta datos validados y opcionalmente el ID del telegrama original (al editar).
     */
    public function consistenciaDeVotos(array $data, $telegramaId = null)
    {
        $idMesa = $data['id_mesa'];
        $lista  = $data['lista'];

        // 1) Obtener electores
        $electores = Mesas::where('id_mesa', $idMesa)->value('electores');

        if (!$electores) {
            throw new \Exception("La mesa $idMesa no existe o no tiene electores asignados.");
        }

        // 2) Votos del telegrama nuevo
        $votosNuevos =
              $data['votos_diputados']
            + $data['votos_senadores']
            + $data['blancos']
            + $data['nulos']
            + $data['recurridos'];

        // 3) Votos existentes en la mesa (excluyendo este telegrama si es update)
        $query = Telegramas::where('id_mesa', $idMesa)
            ->where('lista', '!=', $lista);

        if ($telegramaId) {
            $query->where('id', '!=', $telegramaId);
        }

        $votosExistentes = $query->get()->sum(function ($t) {
            return $t->votos_diputados
                 + $t->votos_senadores
                 + $t->blancos
                 + $t->nulos
                 + $t->recurridos;
        });

        // 4) Validación final
        if ($votosNuevos + $votosExistentes > $electores) {
            throw new \Exception("La suma de votos excede los electores de la mesa $idMesa.");
        }

        return true;
    }
}
