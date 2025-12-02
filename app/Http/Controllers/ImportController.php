<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidatos;
use App\Models\Listas;
use App\Models\Mesas;
use App\Models\Telegramas;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Services\TelegramaService;


class ImportController extends Controller
{
    protected $telegramaService;

    public function __construct(TelegramaService $telegramaService)
    {
        $this->telegramaService = $telegramaService;
    }


    public function import(Request $request)
    {
        $archivo = $request->file('archivo');
        $extension = strtolower($archivo->getClientOriginalExtension());

        $errors = [];
        $tablasLeidas = [];
        $registrosLeidos = [];

        $candidatosRules = [
            'provincia' => ['required', Rule::in(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'])],
            'cargo' => 'required|in:DIPUTADOS,SENADORES' ,
            'lista' => 'required|string|max:20',
            'nombre' => 'required|string|max:255',
            'orden_en_lista' => 'required|integer|max:10',
        ];
        $listasRules = [
            'provincia' => ['required', Rule::in(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'])],
            'cargo' => 'required|in:DIPUTADOS,SENADORES',
            'lista' => 'required|string|max:20',
            'alianza' => 'required|string|max:255',
        ];
        $mesasRules = [
            'id_mesa' => 'required|integer|between:1,10000',
            'provincia' => ['required', Rule::in(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'])],
            'circuito' => 'required|string|max:20',
            'establecimiento' => 'required|string|max:255',
            'electores' => 'required|integer|min:0',
        ];
        $telegramasRules = [
            'id_mesa' => 'required|integer|between:1,10000',
            'provincia' => ['required', Rule::in(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'])],
            'lista' => 'required|string|max:20',
            'votos_diputados' => 'required|integer|min:0',
            'votos_senadores' => 'required|integer|min:0',
            'blancos' => 'required|integer|min:0',
            'nulos' => 'required|integer|min:0',
            'recurridos' => 'required|integer|min:0',
        ];

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
            if (($handle = fopen($archivo, 'r')) !== false) {
                $table = fgetcsv($handle, 1000, ','); // Leer la primera fila como nombre de tabla

                $tablasLeidas[] = $table[0];

                $headers = fgetcsv($handle, 1000, ','); // Leer la segunda fila como encabezados
                while(($data = fgetcsv($handle, 1000, ',')) !== false) {
                    if ($data[0] === null) {
                        $table = fgetcsv($handle, 1000, ','); // Leer la siguiente fila como nombre de tabla

                        $tablasLeidas[] = $table[0];

                        $headers = fgetcsv($handle, 1000, ','); // Leer la siguiente fila como encabezados
                        continue;
                    }
                    $registrosLeidos[] = $data;

                    if ($table[0] === 'candidatos') {
                        

                        $validator = Validator::make(array_combine($headers, $data), $candidatosRules);
                        if ($validator->fails()) {
                            $errors[] = [
                                'tabla' => $table,
                                'data' => $data,
                                'errors' => $validator->errors()->all()
                            ];
                            continue; // Salta a la próxima fila
                        }

                        // Crear el registro con los datos validados
                        Candidatos::create($validator->validated());
                    }

                    if ($table[0] === 'listas') {
                        $validator = Validator::make(array_combine($headers, $data), $listasRules);
                        if ($validator->fails()) {
                            $errors[] = [
                                'tabla' => $table,
                                'data' => $data,
                                'errors' => $validator->errors()->all()
                            ];
                            continue; // Salta a la próxima fila
                        }

                        // Crear el registro con los datos validados
                        Listas::create($validator->validated());
                    }

                    if ($table[0] === 'mesas') {
                        
                        $validator = Validator::make(array_combine($headers, $data), $mesasRules);
                        if ($validator->fails()) {
                            $errors[] = [
                                'tabla' => $table,
                                'data' => $data,
                                'errors' => $validator->errors()->all()
                            ];
                            continue; // Salta a la próxima fila
                        }
                        // Crear el registro con los datos validados
                        Mesas::create($validator->validated());
                    }

                    if ($table[0] === 'telegramas') {

                        // Validación
                        $validated = Validator::make(array_combine($headers, $data), $telegramasRules);
                        if ($validated->fails()) {
                            $errors[] = [
                                'tabla'  => $table,
                                'data'   => $data,
                                'errors' => $validated->errors()->all()
                            ];
                            continue;
                        }

                        $dataValidated = $validated->validated();
                        $service = new TelegramaService();

                        try {
                            $telegramaExistente = Telegramas::where('id_mesa', $dataValidated['id_mesa'])
                                ->where('lista', $dataValidated['lista'])
                                ->first();

                            if ($telegramaExistente) {
                                // Update → hay que excluir el viejo
                                $this->telegramaService->consistenciaDeVotos($dataValidated, $telegramaExistente->id);
                                $telegramaExistente->update($dataValidated);
                            } else {
                                // Create → no excluir nada
                                $this->telegramaService->consistenciaDeVotos($dataValidated, null);
                                Telegramas::create($dataValidated);
                            }
                        } catch (\Exception $e) {
                            $errors[] = [
                                'tabla' => $table,
                                'data' => $data,
                                'errors' => [$e->getMessage()]
                            ];
                            continue;
                        }

                        Telegramas::updateOrCreate(
                            [
                                'id_mesa' => $dataValidated['id_mesa'],
                                'lista' => $dataValidated['lista']
                            ],
                            $dataValidated
                        );

                    }

                }
                fclose($handle);
            }
            return response()->json([
                'mensaje' => 'Archivo CSV importado', 
                'errors' => $errors,
                'tablasLeidas' => $tablasLeidas,
                'registrosLeidos' => $registrosLeidos,
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
            $json = json_decode(file_get_contents($archivo));
            if (isset($json->candidatos)) {
                $tablasLeidas[] = 'candidatos';
                foreach ($json->candidatos as $candidato) {
                    $registrosLeidos[] = $candidato;
                    $data = array_combine(
                        ['provincia','cargo','lista','nombre','orden_en_lista'],
                        (array) $candidato
                    );

                    $validator = Validator::make($data, $candidatosRules);

                    if ($validator->fails()) {
                        $errors[] = $validator->errors()->all();
                        continue;
                    }
                    Candidatos::create($validator->validated());
                }
            }
            if (isset($json->listas)) {
                $tablasLeidas[] = 'listas';
                foreach ($json->listas as $lista) {
                    $registrosLeidos[] = $lista;
                    $data = array_combine(
                        ['provincia','cargo','lista','alianza'],
                        (array) $lista
                    );

                    $validator = Validator::make($data, $listasRules);

                    if ($validator->fails()) {
                        $errors[] = $validator->errors()->all();
                        continue;
                    }
                    Listas::create($validator->validated());
                }
            }
            if (isset($json->mesas)) {
                $tablasLeidas[] = 'mesas';
                foreach ($json->mesas as $mesa) {
                    $registrosLeidos[] = $mesa;
                    $data = array_combine(
                        ['id_mesa','provincia','circuito','establecimiento','electores'],
                        (array) $mesa
                    );

                    $validator = Validator::make($data, $mesasRules);

                    if ($validator->fails()) {
                        $errors[] = $validator->errors()->all();
                        continue;
                    }
                    Mesas::create($validator->validated());
                }
            }
            if (isset($json->telegramas)) {
                $tablasLeidas[] = 'telegramas';
                foreach ($json->telegramas as $telegrama) {
                    $registrosLeidos[] = $telegrama;
                    $data = array_combine(
                        ['id_mesa','provincia','lista','votos_diputados','votos_senadores','blancos','nulos','recurridos'],
                        (array) $telegrama
                    );

                    $validator = Validator::make($data, $telegramasRules);

                    if ($validator->fails()) {
                        $errors[] = [
                            'tabla' => 'telegramas',
                            'data' => $data,
                            'errors' => $validator->errors()->all()
                        ];
                        continue;
                    }
                    // Consistencia de votos: sumar los votos y comparar con electores de la mesa si existe
                    $validated = $validator->validated();
                    $sumVotes = (int) ($validated['votos_diputados'] ?? 0)
                        + (int) ($validated['votos_senadores'] ?? 0)
                        + (int) ($validated['blancos'] ?? 0)
                        + (int) ($validated['nulos'] ?? 0)
                        + (int) ($validated['recurridos'] ?? 0);

                    $mesa = Mesas::where('id_mesa', $validated['id_mesa'])->first();
                    if (!$mesa) {
                        $errors[] = [
                            'tabla' => 'telegramas',
                            'data' => $data,
                            'errors' => ["Mesa no encontrada con id_mesa {$validated['id_mesa']}"]
                        ];
                        continue;
                    }

                    if ($sumVotes > (int) $mesa->electores) {
                        $errors[] = [
                            'tabla' => 'telegramas',
                            'data' => $data,
                            'errors' => ["Suma de votos ({$sumVotes}) mayor que electores ({$mesa->electores}) en mesa {$validated['id_mesa']}"]
                        ];
                        continue;
                    }

                    Telegramas::create($validated);
                }
            }


            return response()->json([
                'mensaje' => 'Archivo JSON importado',
                'errors' => $errors,
                'tablasLeidas' => $tablasLeidas,
                'registrosLeidos' => $registrosLeidos
            ], empty($errors) ? 200 : 207, ['Content-Type' => 'application/json; charset=utf-8'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        return response()->json(['mensaje' => 'Archivo no soportado'], 400);
    }
}
