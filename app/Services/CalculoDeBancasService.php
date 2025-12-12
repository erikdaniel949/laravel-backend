<?php

namespace App\Services;

use App\Repositories\ResultadosRepository;
use App\Repositories\CandidatosRepository;

class CalculoDeBancasService
{
    protected $resultadosRepository;
    protected $candidatosRepository;

    public function __construct(ResultadosRepository $resultadosRepository, CandidatosRepository $candidatosRepository)
    {
        $this->resultadosRepository = $resultadosRepository;
        $this->candidatosRepository = $candidatosRepository;
    }

    public function calcularDiputados($provincia)
    {
        // Bancas asignadas por provincia
        $bancasPorProvincia = [
            "Buenos Aires"       => 35,
            "Catamarca"          => 5,
            "Chaco"              => 7,
            "Chubut"             => 5,
            "Córdoba"            => 18,
            "Corrientes"         => 7,
            "Entre Ríos"         => 9,
            "Formosa"            => 5,
            "Jujuy"              => 5,
            "La Pampa"           => 5,
            "La Rioja"           => 5,
            "Mendoza"            => 10,
            "Misiones"           => 7,
            "Neuquén"            => 5,
            "Río Negro"          => 5,
            "Salta"              => 9,
            "San Juan"           => 5,
            "San Luis"           => 5,
            "Santa Cruz"         => 5,
            "Santa Fe"           => 19,
            "Santiago del Estero"=> 7,
            "Tierra del Fuego"   => 5,
            "Tucumán"            => 9,
            "CABA"               => 13,
        ];

        $rankingListas = $this->resultadosRepository->obtenerRankingListas(['provincia' => $provincia, 'cargo' => 'DIPUTADOS']);
        
        $bancasAsignadas = [];
        foreach ($rankingListas as $lista) {
            $bancasAsignadas[$lista['lista']] = 0;
        }
        for ($i = 0; $i < $bancasPorProvincia[$provincia]; $i++) {
            $maxCociente = 0;
            $listaGanadora = null;

            foreach ($rankingListas as $lista) {
                $cociente = $lista['votos'] / ($bancasAsignadas[$lista['lista']] + 1);

                if ($cociente > $maxCociente) {
                    $maxCociente = $cociente;
                    $listaGanadora = $lista['lista'];
                }
            }

            $bancasAsignadas[$listaGanadora]++;
        }

        $resultado = [];

        foreach ($rankingListas as $lista) {
            $resultado[] = [
                'id'                 => $lista['id'],
                'lista'              => $lista['lista'],
                'alianza'            => $lista['alianza'],
                'votos'              => $lista['votos'],
                'bancas_asignadas'   => $bancasAsignadas[$lista['lista']]
            ];
        }

        return $resultado;
    }
    public function calcularSenadores($provincia)
    {
        $rankingListas = $this->resultadosRepository->obtenerRankingListas(['provincia' => $provincia, 'cargo' => 'SENADORES']);
        $listaMayoria = $rankingListas[0];
        $listaPrimeraMinoria = $rankingListas[1] ?? null;
        $resultado = [];
        $resultado[] = [
            'id'                 => $listaMayoria['id'],
            'lista'              => $listaMayoria['lista'],
            'alianza'            => $listaMayoria['alianza'],
            'votos'              => $listaMayoria['votos'],
            'bancas_asignadas'   => 2
        ];
        if ($listaPrimeraMinoria) {
            $resultado[] = [
                'id'                 => $listaPrimeraMinoria['id'],
                'lista'              => $listaPrimeraMinoria['lista'],
                'alianza'            => $listaPrimeraMinoria['alianza'],
                'votos'              => $listaPrimeraMinoria['votos'],
                'bancas_asignadas'   => 1
            ];
        }
        return $resultado;
    }

    public function asignarBancas($provincia)
    {
        $listasConBancasDiputados = $this->calcularDiputados($provincia);
        $listasConBancasSenadores = $this->calcularSenadores($provincia);

        $resultadoDiputados = [];
        $resultadoSenadores = [];

        // ============================
        // ASIGNACIÓN DE DIPUTADOS
        // ============================
        foreach ($listasConBancasDiputados as $lista) {

            // obtener todos los candidatos de una sola vez
            $candidatos = $this->candidatosRepository->obtenerCandidatosDeUnaLista([
                'provincia' => $provincia,
                'cargo' => 'DIPUTADOS',
                'lista' => $lista['lista']
            ]);

            $asignados = [];

            // asignar bancas según orden en lista
            for ($i = 0; $i < $lista['bancas_asignadas']; $i++) {
                $asignados[] = $candidatos[$i] ?? null;
            }

            $lista['diputados_asignados'] = $asignados;
            $resultadoDiputados[] = $lista;
        }

        // ============================
        // ASIGNACIÓN DE SENADORES
        // ============================
        foreach ($listasConBancasSenadores as $lista) {

            $candidatos = $this->candidatosRepository->obtenerCandidatosDeUnaLista([
                'provincia' => $provincia,
                'cargo' => 'SENADORES',
                'lista' => $lista['lista']
            ]);

            $asignados = [];

            for ($i = 0; $i < $lista['bancas_asignadas']; $i++) {
                $asignados[] = $candidatos[$i] ?? null;
            }

            $lista['senadores_asignados'] = $asignados;
            $resultadoSenadores[] = $lista;
        }

        return [
            'diputados' => $resultadoDiputados,
            'senadores' => $resultadoSenadores,
        ];
    }

}