<?php

namespace App\Services;

use App\Repositories\TelegramasRepository;
use App\Repositories\ListasRepository;
use App\Models\Mesas;
use App\Models\Telegramas;

class TelegramasService
{
    protected $repository;
    protected $listasRepository;

    public function __construct(TelegramasRepository $repository, ListasRepository $listasRepository)
    {
        $this->repository = $repository;
        $this->listasRepository = $listasRepository;
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
        // Resolver id_lista a partir de los campos proporcionados

        // Delegar la validación de consistencia al modelo
        Telegramas::validarConsistencia($data);

        return $this->repository->crear($data);
    }

    public function actualizar($id, array $data): bool
    {
        // Resolver id_lista a partir de los campos proporcionados

        // Delegar la validación de consistencia al modelo
        Telegramas::validarConsistencia($data, $id);

        return $this->repository->actualizar($id, $data);
    }

    public function eliminar($id): bool
    {
        return $this->repository->eliminar($id);
    }
}
