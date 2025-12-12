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

    public function obtenerCandidatosDeUnaLista(array $filtros)
    {
        $query = Candidatos::query();

        if (isset($filtros['provincia'])) {
            $query->where('provincia', $filtros['provincia']);
        }
        if (isset($filtros['cargo'])) {
            $query->where('cargo', $filtros['cargo']);
        }
        if (isset($filtros['lista'])) {
            $query->where('lista', $filtros['lista']);
        }

        // Mantener orden por orden_en_lista para asignación de bancas
        return $query->orderBy('orden_en_lista')->get();
    }
}
