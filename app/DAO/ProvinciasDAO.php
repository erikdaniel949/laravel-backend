<?php

namespace App\DAO;

use App\Models\Provincias;

class ProvinciasDAO
{
    public function obtenerTodos()
    {
        return Provincias::all();
    }

    public function obtenerPorId($id)
    {
        return Provincias::find($id);
    }

    public function crear(array $data)
    {
        return Provincias::create($data);
    }

    public function actualizar($id, array $data): bool
    {
        $provincia = Provincias::find($id);
        if (!$provincia) return false;

        return $provincia->update($data);
    }

    public function eliminar($id): bool
    {
        $provincia = $this->obtenerPorId($id);
        if (!$provincia) return false;
        return $provincia->delete();
    }
}
