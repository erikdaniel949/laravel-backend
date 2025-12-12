<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Services\CandidatosService;


class CandidatosController extends Controller
{
    protected $candidatosService;

    public function __construct(CandidatosService $candidatosService)
    {
        $this->candidatosService = $candidatosService;
    }

    private function rules()
    {
        return [
            'provincia' => ['required', Rule::in(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'])],
            'cargo' => 'required|in:DIPUTADOS,SENADORES' ,
            'lista' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'orden_en_lista' => 'required|integer|between:1,100',
        ];
    }

    public function index()
    {
        $candidatos = $this->candidatosService->obtenerTodos();
        return response()->json($candidatos);
    }
    
    public function show($id)
    {
        $candidato = $this->candidatosService->obtenerPorId($id);
        if (!$candidato) {
            return response()->json(['mensaje' => 'Candidato no encontrado'], 404);
        }
        return response()->json($candidato);
    }

   public function store(Request $request)
    {
        // Validacion 
        $validated = $request->validate($this->rules());

        // Crear el registro con los datos validados
        $candidato = $this->candidatosService->crear($validated);

        return response()->json([
            'mensaje' => 'Candidato creado correctamente',
            'candidato' => $candidato
        ], 201);
    }


    public function update(Request $request, $id)
    {

        $candidato = $this->candidatosService->obtenerPorId($id);
        if (!$candidato) {
            return response()->json(['mensaje' => 'Candidato no encontrado'], 404);
        }

        // Validación de campos individuales
        $validated = $request->validate($this->rules());


        // Actualizar el candidato en la BD
        $this->candidatosService->actualizar($id, $validated);

        return response()->json([
            'mensaje' => 'Candidato actualizado correctamente',
            'candidato' => $this->candidatosService->obtenerPorId($id)
        ]);
    }

    public function destroy($id)
    {
        $candidato = $this->candidatosService->obtenerPorId($id);
        if (!$candidato) {
            return response()->json(['mensaje' => 'Candidato no encontrado'], 404);
        }

        $this->candidatosService->eliminar($id);

        return response()->json(['mensaje' => 'Candidato eliminado correctamente']);
    }
/*
    public function total($id)
    {
        $candidato = Candidatos::with('provincia')->find($id);

        $votosTotales = $candidato->provincia();

        return response()->json([
            'resultado' => $votosTotales
        ], 200, [], JSON_PRETTY_PRINT);
    }
*/
}
