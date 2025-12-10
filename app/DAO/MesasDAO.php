<?php

namespace App\DAO;

use App\Models\Mesas;

class MesasDAO
{
    public function obtenerTodos()
    {
        return Mesas::all();
    }

    public function obtenerPorId($id)
    {
        return Mesas::find($id);
    }

    public function crear(array $data)
    {
        return Mesas::create($data);
    }

    public function actualizar($id, array $data): bool
    {
        $mesa = Mesas::find($id);
        if (!$mesa) return false;

        return $mesa->update($data);
    }

    public function eliminar($id): bool
    {
        $mesa = $this->obtenerPorId($id);
        if (!$mesa) {
            return false;
        }
        return $mesa->delete();
    }
}
