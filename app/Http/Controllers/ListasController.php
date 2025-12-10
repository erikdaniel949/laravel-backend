<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Services\ListasService;

class ListasController extends Controller
{
    protected $listasService;

    public function __construct(ListasService $listasService)
    {
        $this->listasService = $listasService;
    }

    private function rules()
    {
        return [
            'provincia' => ['required', Rule::in(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'])],
            'cargo' => 'required|in:DIPUTADOS,SENADORES',
            'lista' => 'required|string|max:255',
            'alianza' => 'required|string|max:255',
        ];
    }

    public function index()
    {
        $listas = $this->listasService->obtenerTodos();
        return response()->json($listas);
    }
    
    public function show($id)
    {
        $lista = $this->listasService->obtenerPorId($id);
        if (!$lista) {
            return response()->json(['mensaje' => 'Lista no encontrada'], 404);
        }
        return response()->json($lista);
    }

    public function store(Request $request)
    {
        // Validacion 
        $validated = $request->validate($this->rules());

        // Crear el registro con los datos validados
        $lista = $this->listasService->crear($validated);

        return response()->json([
            'mensaje' => 'Lista creada correctamente',
            'lista' => $lista
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $lista = $this->listasService->obtenerPorId($id);
        if (!$lista) {
            return response()->json(['mensaje' => 'Lista no encontrada'], 404);
        }

        // Validación de campos individuales
        $validated = $request->validate($this->rules());

        // Actualizar la lista en la BD
        $this->listasService->actualizar($id, $validated);

        return response()->json([
            'mensaje' => 'Lista actualizada correctamente',
            'lista' => $this->listasService->obtenerPorId($id)
        ]);
    }

    public function destroy($id)
    {
        $lista = $this->listasService->obtenerPorId($id);
        if (!$lista) {
            return response()->json(['mensaje' => 'Lista no encontrada'], 404);
        }

        $this->listasService->eliminar($id);

        return response()->json(['mensaje' => 'Lista eliminada correctamente']);
    }
}