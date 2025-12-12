<?php

namespace App\Services;

use App\Repositories\ResultadosRepository;

//Servicio de resultados electorales y estadísticas
class ResultadosService
{
    protected $resultadosRepository;

    public function __construct(ResultadosRepository $resultadosRepository)
    {
        $this->resultadosRepository = $resultadosRepository;
    }

    //Obtener resultados nacionales completos
    public function obtenerRankingListas($filtros = [])
    {
        return $this->resultadosRepository->obtenerRankingListas($filtros);
    }

    public function participacionNacional()
    {
        // Lógica para obtener resultados electorales detallados
        return $this->resultadosRepository->obtenerParticipacionNacional();
    }

    public function obtenerResultadosCandidato($id)
    {
        return $this->resultadosRepository->obtenerResultadosCandidato($id);
    }

    public function obtenerCantidades(): array
    {
        return $this->resultadosRepository->obtenerCantidades();
    }
}