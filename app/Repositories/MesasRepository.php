<?php

namespace App\Repositories;

use App\DAO\MesasDAO;

class MesasRepository
{
    protected $dao;

    public function __construct(MesasDAO $dao)
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
