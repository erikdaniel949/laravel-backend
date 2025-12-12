<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listas;
use Illuminate\Support\Facades\DB;
use App\Services\ResultadosService;

class ResultadosController extends Controller
{
    protected $resultadosService;

    public function __construct(ResultadosService $resultadosService)
    {
        $this->resultadosService = $resultadosService;
    }

    public function rankingListas(Request $request)
    {
        $filtros = $request->query();
        $rankingListas = $this->resultadosService->obtenerRankingListas($filtros);

        return response()->json($rankingListas, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function participacionNacional(Request $request)
    {
        $participacionNacional = $this->resultadosService->participacionNacional();

        return response()->json(['participacion_nacional' => $participacionNacional], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function verCandidato($id)
    {
        $candidato = $this->resultadosService->obtenerResultadosCandidato($id);

        if (!$candidato) {
            return response()->json(['mensaje' => 'Candidato no encontrado'], 404);
        }

        return response()->json($candidato, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function cantidades()
    {
        $cantidades = $this->resultadosService->obtenerCantidades();

        return response()->json($cantidades, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}