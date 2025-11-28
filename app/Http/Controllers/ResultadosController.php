<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listas;

class ResultadosController extends Controller
{
    public function resultados(Request $request)
    {
        // Recibir parámetros de filtro desde la solicitud
        $filtroProvincia = $request->provincia;
        $filtroCargo = $request->cargo;
        $filtroAlianza = $request->alianza;
        $filtroMesaMinima = $request->mesaMinima;
        $filtroMesaMaxima = $request->mesaMaxima;

        // Consulta optimizada con 'with' y filtros condicionales
        $listasFiltradas = Listas::with('telegramaRelacionado')
            ->whereHas('telegramaRelacionado', function($query) use ($filtroMesaMinima, $filtroMesaMaxima) {
                // Filtrar por id_mesa en la relación
                if ($filtroMesaMinima) {
                    $query->where('id_mesa', '>=', $filtroMesaMinima);
                }
                if ($filtroMesaMaxima) {
                    $query->where('id_mesa', '<=', $filtroMesaMaxima);
                }
            })
            ->when($filtroProvincia, function($query) use ($filtroProvincia) {
                $query->where('provincia', $filtroProvincia);
            })
            ->when($filtroCargo, function($query) use ($filtroCargo) {
                $query->where('cargo', $filtroCargo);
            })
            ->when($filtroAlianza, function($query) use ($filtroAlianza) {
                $query->where('alianza', $filtroAlianza);
            })
            ->get();

        return response()->json([
            'listas' => $listasFiltradas
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
