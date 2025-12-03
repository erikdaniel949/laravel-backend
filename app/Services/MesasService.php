<?php

namespace App\Services;

use App\Repositories\MesasRepository;

class MesasService
{
    protected $repository;

    public function __construct(MesasRepository $repository)
    {
        $this->repository = $repository;
    }

    public function obtenerTodos()
    {
        return $this->repository->obtenerTodos();
    }

    public function obtenerPorId($id)
    {
        return $this->repository->obtenerPorId($id);
    }

    public function crear(array $data)
    {
        return $this->repository->crear($data);
    }

    public function actualizar($id, array $data): bool
    {
        return $this->repository->actualizar($id, $data);
    }

    public function eliminar($id): bool
    {
        return $this->repository->eliminar($id);
    }
}
