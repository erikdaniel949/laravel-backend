<?php
namespace App\Services;

use App\Repositories\ListasRepository;

class ListasService
{
    protected $repository;

    public function __construct(ListasRepository $repository)
    {
        $this->repository = $repository;
    }

    public function filtrarListas(array $filtros)
    {
        return $this->repository->obtenerListasFiltradas($filtros);
    }
}
