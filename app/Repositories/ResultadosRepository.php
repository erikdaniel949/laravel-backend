<?php

namespace App\Repositories;

use App\DAO\ResultadosDAO;

class ResultadosRepository
{
    protected $resultadosDAO;

    public function __construct(ResultadosDAO $resultadosDAO)
    {
        $this->resultadosDAO = $resultadosDAO;
    }

    public function obtenerRankingListas($filtros): array
    {
        return $this->resultadosDAO->obtenerRankingListas($filtros);
    }

    public function obtenerParticipacionNacional(): float
    {
        // Lógica para obtener resultados electorales detallados
        return $this->resultadosDAO->obtenerParticipacionNacional();
    }

    public function obtenerResultadosCandidato($id)
    {
        return $this->resultadosDAO->obtenerResultadosCandidato($id);
    }

    public function obtenerCantidades(): array
    {
        return $this->resultadosDAO->obtenerCantidades();
    }
}