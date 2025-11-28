<?php
namespace App\Repositories;

use App\Models\Listas;

class ListasRepository
{
    protected $model;

    public function __construct(Listas $model)
    {
        $this->model = $model;
    }

    public function obtenerListasFiltradas(array $filtros)
    {
        return $this->model->with('telegramaRelacionado')
            ->whereHas('telegramaRelacionado', function($query) use ($filtros) {
                if (!empty($filtros['mesaMinima'])) {
                    $query->where('id_mesa', '>=', $filtros['mesaMinima']);
                }
                if (!empty($filtros['mesaMaxima'])) {
                    $query->where('id_mesa', '<=', $filtros['mesaMaxima']);
                }
            })
            ->when(!empty($filtros['provincia']), function($query) use ($filtros) {
                $query->where('provincia', $filtros['provincia']);
            })
            ->when(!empty($filtros['cargo']), function($query) use ($filtros) {
                $query->where('cargo', $filtros['cargo']);
            })
            ->when(!empty($filtros['alianza']), function($query) use ($filtros) {
                $query->where('alianza', $filtros['alianza']);
            })
            ->get();
    }
}
