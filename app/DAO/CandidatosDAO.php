<?php

namespace App\DAO;

use App\Models\Candidatos;

class CandidatosDAO
{
    public function obtenerTodos()
    {
        return Candidatos::all();
    }

    public function obtenerPorId($id)
    {
        return Candidatos::find($id);
    }

    public function crear(array $data)
    {
        return Candidatos::create($data);
    }

    public function actualizar($id, array $data): bool
    {
        $candidato = Candidatos::find($id);
        if (!$candidato) return false;

        return $candidato->update($data);
    }

    public function eliminar($id): bool
    {
        $candidato = $this->obtenerPorId($id);
        if (!$candidato) {
            return false;
        }
        return $candidato->delete();
    }
}
