<?php

namespace App\DAO;

use App\Models\Telegramas;

class TelegramasDAO
{
    public function obtenerTodos()
    {
        return Telegramas::all();
    }

    public function obtenerPorId($id)
    {
        return Telegramas::find($id);
    }

    public function crear(array $data)
    {
        return Telegramas::create($data);
    }

    public function actualizar($id, array $data): bool
    {
        $telegrama = Telegramas::find($id);
        if (!$telegrama) return false;

        return $telegrama->update($data);
    }

    public function eliminar($id): bool
    {
        $telegrama = $this->obtenerPorId($id);
        if (!$telegrama) {
            return false;
        }
        return $telegrama->delete();
    }
}
