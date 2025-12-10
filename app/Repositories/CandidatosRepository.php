<?php

namespace App\Repositories;

use App\DAO\CandidatosDAO;

class CandidatosRepository
{
    protected $dao;

    public function __construct(CandidatosDAO $dao)
    {
        $this->dao = $dao;
    }

    public function obtenerTodos()
    {
        return $this->dao->obtenerTodos();
    }

    public function obtenerPorId($id)
    {
        return $this->dao->obtenerPorId($id);
    }

    public function crear(array $data)
    {
        return $this->dao->crear($data);
    }

    public function actualizar($id, array $data): bool
    {
        return $this->dao->actualizar($id, $data);
    }

    public function eliminar($id): bool
    {
        return $this->dao->eliminar($id);
    }
}
