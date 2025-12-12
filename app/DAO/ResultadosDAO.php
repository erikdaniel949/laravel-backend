<?php

namespace App\DAO;

use App\Models\Listas;
use Illuminate\Support\Facades\DB;

class ResultadosDAO
{
    public function obtenerRankingListas($filtros): array
    {
        $query = Listas::leftJoin('telegramas', function ($join) {
            $join->on('listas.lista', '=', 'telegramas.lista')
                ->whereColumn('listas.provincia', 'telegramas.provincia');
        })
        ->selectRaw('
            listas.id,
            listas.lista,
            listas.provincia,
            listas.cargo,
            listas.alianza,
            SUM(
                CASE 
                    WHEN listas.cargo = "DIPUTADOS" THEN COALESCE(telegramas.votos_diputados, 0)
                    WHEN listas.cargo = "SENADORES" THEN COALESCE(telegramas.votos_senadores, 0)
                    ELSE 0
                END
            ) AS votos
        ')
        ->groupBy('listas.id', 'listas.lista', 'listas.provincia', 'listas.cargo')
        ->orderByDesc('votos');

        // Filtros dinámicos
        $query->when(!empty($filtros['provincia']), fn($q) => $q->where('listas.provincia', $filtros['provincia']));
        $query->when(!empty($filtros['cargo']), fn($q) => $q->where('listas.cargo', $filtros['cargo']));
        $query->when(!empty($filtros['lista']), fn($q) => $q->where('listas.lista', $filtros['lista']));
        $query->when(!empty($filtros['alianza']), fn($q) => $q->where('listas.alianza', $filtros['alianza']));
        $query->when(!empty($filtros['mesaMinima']), fn($q) => $q->where('telegramas.id_mesa', '>=', (int)$filtros['mesaMinima']));
        $query->when(!empty($filtros['mesaMaxima']), fn($q) => $q->where('telegramas.id_mesa', '<=', (int)$filtros['mesaMaxima']));

        return $query->get()->toArray();

    }

    public function obtenerParticipacionNacional(): float
    {
        // 1. Obtener solo las mesas que tienen telegramas cargados
        $totalElectores = DB::table('mesas')->sum('electores');

        $totalVotosEmitidos = DB::table('telegramas')
            ->selectRaw('SUM(votos_diputados + votos_senadores + blancos + nulos + recurridos) as total')
            ->value('total');

        return ($totalVotosEmitidos / $totalElectores) * 100;

    }

    public function obtenerResultadosCandidato($id): ?array
    {
        $resultado = DB::table('candidatos')
            ->leftJoin('listas', function ($join) {
                $join->on('candidatos.lista', '=', 'listas.lista')
                    ->whereColumn('candidatos.provincia', 'listas.provincia')
                    ->whereColumn('candidatos.cargo', 'listas.cargo');
            })
            ->leftJoin('telegramas', function ($join) {
                $join->on('listas.lista', '=', 'telegramas.lista')
                    ->whereColumn('listas.provincia', 'telegramas.provincia');
            })
            ->selectRaw('
                candidatos.id,
                candidatos.nombre,
                candidatos.provincia,
                candidatos.cargo,
                candidatos.lista,

                SUM(
                    CASE 
                        WHEN candidatos.cargo = "DIPUTADOS" THEN COALESCE(telegramas.votos_diputados, 0)
                        WHEN candidatos.cargo = "SENADORES" THEN COALESCE(telegramas.votos_senadores, 0)
                        ELSE 0
                    END
                ) AS votos_obtenidos,

                (
                    SELECT SUM(
                        CASE 
                            WHEN c.cargo = "DIPUTADOS" THEN COALESCE(t.votos_diputados, 0)
                            WHEN c.cargo = "SENADORES" THEN COALESCE(t.votos_senadores, 0)
                            ELSE 0
                        END
                    )
                    FROM listas l
                    JOIN telegramas t ON t.lista = l.lista AND t.provincia = l.provincia
                    JOIN candidatos c ON c.lista = l.lista AND c.provincia = l.provincia AND c.cargo = l.cargo
                    WHERE c.provincia = candidatos.provincia
                    AND c.cargo = candidatos.cargo
                ) AS total_cargo_provincia
            ')
            ->where('candidatos.id', $id)
            ->groupBy('candidatos.id', 'candidatos.nombre', 'candidatos.provincia', 'candidatos.cargo', 'candidatos.lista')
            ->first();

        if ($resultado) {
            $resultado->porcentaje = $resultado->total_cargo_provincia
                ? round(($resultado->votos_obtenidos / $resultado->total_cargo_provincia) * 100, 2)
                : 0;
        }

        return (array) $resultado;
    }

    public function obtenerCantidades(): array
    {
        $cantidadProvincias = DB::table('provincias')->count();
        
        // Contar listas únicas (una lista por provincia se cuenta como una sola)
        $cantidadListas = DB::table('listas')
            ->selectRaw('DISTINCT lista')
            ->count();
        
        $cantidadCandidatos = DB::table('candidatos')->count();
        
        $cantidadMesas = DB::table('mesas')->count();
        
        // Sumar todos los votos (diputados + senadores + blancos + nulos + recurridos)
        $votosTotal = DB::table('telegramas')
            ->selectRaw('SUM(votos_diputados + votos_senadores + blancos + nulos + recurridos) as total')
            ->value('total') ?? 0;
        
        return [
            'provincias' => $cantidadProvincias,
            'listas' => $cantidadListas,
            'candidatos' => $cantidadCandidatos,
            'mesas' => $cantidadMesas,
            'votos_totales' => (int)$votosTotal
        ];
    }

}