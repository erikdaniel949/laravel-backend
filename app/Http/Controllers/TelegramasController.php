<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Services\TelegramasService;

class TelegramasController extends Controller
{
    protected $telegramasService;

    public function __construct(TelegramasService $telegramasService)
    {
        $this->telegramasService = $telegramasService;
    }

    private function rules()
    {
        return [
            'id_mesa' => 'required|integer|min:1|exists:mesas,id',
            'provincia' => ['required', Rule::in(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'])],
            'lista' => 'required|string|max:255',
            'votos_diputados' => 'required|integer|min:0',
            'votos_senadores' => 'required|integer|min:0',
            'blancos' => 'required|integer|min:0',
            'nulos' => 'required|integer|min:0',
            'recurridos' => 'required|integer|min:0',
        ];
    }

    public function index()
    {
        $telegramas = $this->telegramasService->obtenerTodos();
        return response()->json($telegramas);
    }
    
    public function show($id)
    {
        $telegrama = $this->telegramasService->obtenerPorId($id);
        if (!$telegrama) {
            return response()->json(['mensaje' => 'Telegrama no encontrado'], 404);
        }
        return response()->json($telegrama);
    }

    public function store(Request $request)
    {
        // Validacion 
        $validated = $request->validate($this->rules());

        // Crear el registro con los datos validados
        $telegrama = $this->telegramasService->crear($validated);

        return response()->json([
            'mensaje' => 'Telegrama creado correctamente',
            'telegrama' => $telegrama
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $telegrama = $this->telegramasService->obtenerPorId($id);
        if (!$telegrama) {
            return response()->json(['mensaje' => 'Telegrama no encontrado'], 404);
        }

        // Validación de campos individuales
        $validated = $request->validate($this->rules());

        // Actualizar el telegrama en la BD
        $this->telegramasService->actualizar($id, $validated);

        return response()->json([
            'mensaje' => 'Telegrama actualizado correctamente',
            'telegrama' => $this->telegramasService->obtenerPorId($id)
        ]);
    }

    public function destroy($id)
    {
        $telegrama = $this->telegramasService->obtenerPorId($id);
        if (!$telegrama) {
            return response()->json(['mensaje' => 'Telegrama no encontrado'], 404);
        }

        $this->telegramasService->eliminar($id);

        return response()->json(['mensaje' => 'Telegrama eliminado correctamente']);
    }
}