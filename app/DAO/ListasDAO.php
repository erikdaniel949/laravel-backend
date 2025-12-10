<?php

namespace App\DAO;

use App\Models\Listas;

class ListasDAO
{
    public function obtenerTodos()
    {
        return Listas::all();
    }

    public function obtenerPorId($id)
    {
        return Listas::find($id);
    }

    public function crear(array $data)
    {
        return Listas::create($data);
    }

    public function actualizar($id, array $data): bool
    {
        $lista = Listas::find($id);
        if (!$lista) return false;

        return $lista->update($data);
    }

    public function eliminar($id): bool
    {
        $lista = $this->obtenerPorId($id);
        if (!$lista) {
            return false;
        }
        return $lista->delete();
    }
}
