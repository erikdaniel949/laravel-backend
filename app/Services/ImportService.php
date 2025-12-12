<?php

namespace App\Services;

use App\Repositories\CandidatosRepository;
use App\Repositories\ListasRepository;
use App\Repositories\MesasRepository;
use App\Repositories\TelegramasRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ImportService
{
    protected $candidatosRepository;
    protected $listasRepository;
    protected $mesasRepository;
    protected $telegramasRepository;

    protected $candidatosRules;
    protected $listasRules;
    protected $mesasRules;
    protected $telegramasRules;

    public function __construct(CandidatosRepository $candidatosRepository, ListasRepository $listasRepository, MesasRepository $mesasRepository, TelegramasRepository $telegramasRepository)
    {
        $this->candidatosRepository = $candidatosRepository;
        $this->listasRepository = $listasRepository;
        $this->mesasRepository = $mesasRepository;
        $this->telegramasRepository = $telegramasRepository;

        $this->candidatosRules = [
            'provincia' => [
                'required',
                Rule::in([
                    'Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes',
                    'Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones',
                    'Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe',
                    'Santiago del Estero','Tierra del Fuego','Tucumán'
                ]),
            ],
            'cargo' => 'required|in:DIPUTADOS,SENADORES',
            'lista' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'orden_en_lista' => 'required|integer|min:1|max:50',
        ];
        $this->listasRules = [
            'provincia' => ['required', Rule::in(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'])],
            'cargo' => 'required|in:DIPUTADOS,SENADORES',
            'lista' => 'required|string|max:255',
            'alianza' => 'required|string|max:255',
        ];
        $this->mesasRules = [
            'id_mesa' => 'required|integer|between:1,100000',
            'provincia' => ['required', Rule::in(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'])],
            'circuito' => 'required|string|max:255',
            'establecimiento' => 'required|string|max:255',
            'electores' => 'required|integer|min:0',
        ];
        $this->telegramasRules = [
            'id_mesa' => 'required|integer|between:1,100000',
            'provincia' => ['required', Rule::in(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'])],
            'lista' => 'required|string|max:255',
            'votos_diputados' => 'required|integer|min:0',
            'votos_senadores' => 'required|integer|min:0',
            'blancos' => 'required|integer|min:0',
            'nulos' => 'required|integer|min:0',
            'recurridos' => 'required|integer|min:0',
        ];
    }

    public function importarCSV($archivo)
    {
        $errors = [];
        $tablasLeidas = [];
        $registrosLeidos = [];

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
                    

                    $validator = Validator::make(array_combine($headers, $data), $this->candidatosRules);
                    if ($validator->fails()) {
                        $errors[] = [
                            'tabla' => $table,
                            'data' => $data,
                            'errors' => $validator->errors()->all()
                        ];
                        continue; // Salta a la próxima fila
                    }

                    // Crear el registro con los datos validados
                    $this->candidatosRepository->crear($validator->validated());
                }

                if ($table[0] === 'listas') {
                    $validator = Validator::make(array_combine($headers, $data), $this->listasRules);
                    if ($validator->fails()) {
                        $errors[] = [
                            'tabla' => $table,
                            'data' => $data,
                            'errors' => $validator->errors()->all()
                        ];
                        continue; // Salta a la próxima fila
                    }

                    // Crear el registro con los datos validados
                    $this->listasRepository->crear($validator->validated());
                }

                if ($table[0] === 'mesas') {
                    
                    $validator = Validator::make(array_combine($headers, $data), $this->mesasRules);
                    if ($validator->fails()) {
                        $errors[] = [
                            'tabla' => $table,
                            'data' => $data,
                            'errors' => $validator->errors()->all()
                        ];
                        continue; // Salta a la próxima fila
                    }
                    // Crear el registro con los datos validados
                    $this->mesasRepository->crear($validator->validated());
                }

                if ($table[0] === 'telegramas') {

                    // Validación
                    $validated = Validator::make(array_combine($headers, $data), $this->telegramasRules);
                    if ($validated->fails()) {
                        $errors[] = [
                            'tabla'  => $table,
                            'data'   => $data,
                            'errors' => $validated->errors()->all()
                        ];
                        continue;
                    }

                    // Crear el registro con los datos validados
                    $this->telegramasRepository->crear($validated->validated());
                }

            }
            fclose($handle);
        }
        return [
            'errors' => $errors,
            'tablasLeidas' => $tablasLeidas,
            'registrosLeidos' => $registrosLeidos,
        ];
        
    }

    public function importarJSON($archivo)
    {
        $json = json_decode(file_get_contents($archivo));
        $errors = [];
        $tablasLeidas = [];
        $registrosLeidos = [];
        
        if (isset($json->candidatos)) {
            $tablasLeidas[] = 'candidatos';
            foreach ($json->candidatos as $candidato) {
                $registrosLeidos[] = $candidato;
                $data = array_combine(
                    ['provincia','cargo','lista','nombre','orden_en_lista'],
                    (array) $candidato
                );

                $validator = Validator::make($data, $this->candidatosRules);

                if ($validator->fails()) {
                    $errors[] = $validator->errors()->all();
                    continue;
                }
                $this->candidatosRepository->crear($validator->validated());
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

                $validator = Validator::make($data, $this->listasRules);

                if ($validator->fails()) {
                    $errors[] = $validator->errors()->all();
                    continue;
                }
                $this->listasRepository->crear($validator->validated());
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

                $validator = Validator::make($data, $this->mesasRules);

                if ($validator->fails()) {
                    $errors[] = $validator->errors()->all();
                    continue;
                }
                $this->mesasRepository->crear($validator->validated());
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

                $validator = Validator::make($data, $this->telegramasRules);

                if ($validator->fails()) {
                    $errors[] = [
                        'tabla' => 'telegramas',
                        'data' => $data,
                        'errors' => $validator->errors()->all()
                    ];
                    continue;
                }
                $this->telegramasRepository->crear($validator->validated());
            }
        }
        return [
            'errors' => $errors,
            'tablasLeidas' => $tablasLeidas,
            'registrosLeidos' => $registrosLeidos,
        ];
    }
}