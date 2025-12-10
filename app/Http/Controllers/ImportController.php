<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidatos;
use App\Models\Listas;
use App\Models\Mesas;
use App\Models\Telegramas;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Services\ImportService;


class ImportController extends Controller
{
    protected $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }


    public function import(Request $request)
    {
        $archivo = $request->file('archivo');
        $extension = strtolower($archivo->getClientOriginalExtension());

        // Lógica para archivos csv
        if ($extension === 'csv') {
            /* CSV de ejemplo:
                candidatos
                provincia,cargo,lista,nombre,orden_en_lista
                Buenos Aires,DIPUTADOS,Lista A,Erik,1
                Buenos Aires,SENADORES,Lista B,Erik 2,2

                listas
                provincia,cargo,lista,alianza
                Córdoba,SENADORES,Lista A,Frente 1
                Chubut,DIPUTADOS,Lista B,Frente 2

                mesas
                id_mesa,provincia,circuito,establecimiento,electores
                1001,CABA,101,Escuela 1,500
                1002,CABA,102,Escuela 2,400

                telegramas
                id_mesa,provincia,lista,votos_diputados,votos_senadores,blancos,nulos,recurridos
                1001,CABA,Lista A,250,200,100,50,10
                1002,CABA,Lista B,300,250,150,100,50
            */
            $respuesta = $this->importService->importarCSV($archivo->getPathname());

            return response()->json([
                'mensaje' => 'Archivo CSV importado', 
                'errors' => $respuesta['errors'],
                'tablasLeidas' => $respuesta['tablasLeidas'],
                'registrosLeidos' => $respuesta['registrosLeidos'],
            ], empty($errors) ? 200 : 207, ['Content-Type' => 'application/json; charset=utf-8'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        // Lógica para archivos json
        if ($extension === 'json'){
            /* JSON de ejemplo:
            {
                "candidatos": [
                    {"provincia": "Buenos Aires", "cargo": "DIPUTADOS", "lista": "Lista A", "nombre": "Erik", "orden_en_lista": 1},
                    {"provincia": "Buenos Aires", "cargo": "SENADORES", "lista": "Lista B", "nombre": "Erik 2", "orden_en_lista": 2}
                ],
                "listas": [
                    {"provincia": "Córdoba", "cargo": "SENADORES", "lista": "Lista A", "alianza": "Frente 1"},
                    {"provincia": "Chubut", "cargo": "DIPUTADOS", "lista": "Lista B", "alianza": "Frente 2"}
                ],
                "mesas": [
                    {"id_mesa": 1, "provincia": "CABA", "circuito": "101", "establecimiento": "Escuela 1", "electores": 500},
                    {"id_mesa": 2, "provincia": "CABA", "circuito": "102", "establecimiento": "Escuela 2", "electores": 400}
                ],
                "telegramas": [
                    {"id_mesa": 1, "provincia": "CABA",  "lista": "Lista A", "votos_diputados": 250, "votos_senadores": 200, "blancos": 100,  "nulos": 50,  "recurridos": 10},
                    {"id_mesa": 2, "provincia": "CABA",  "lista": "Lista B",  "votos_diputados": 300,  "votos_senadores": 250,  "blancos": 150,  "nulos": 100,  "recurridos": 50}
                ]
            }
            */
            $respuesta = $this->importService->importarJSON($archivo->getPathname());
            return response()->json([
                'mensaje' => 'Archivo JSON importado',
                'errors' => $respuesta['errors'],
                'tablasLeidas' => $respuesta['tablasLeidas'],
                'registrosLeidos' => $respuesta['registrosLeidos'],
            ], empty($errors) ? 200 : 207, ['Content-Type' => 'application/json; charset=utf-8'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        return response()->json(['mensaje' => 'Archivo no soportado'], 400);
    }
}
