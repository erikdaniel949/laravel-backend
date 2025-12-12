<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Services\CalculoDeBancasService;

class CalculoDeBancasController extends Controller
{
    protected $calculoDeBancasService;
    public function __construct(CalculoDeBancasService $calculoDeBancasService)
    {
        $this->calculoDeBancasService = $calculoDeBancasService;
    }

    private function rules()
    {
        return [
            'provincia' => ['required', Rule::in(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'])],
        ];
    }

    public function calcular(Request $request)
    {
        // Use Validator to ensure we always return JSON on validation errors (avoid browser redirect)
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $data = $validator->validated();
        $provincia = $data['provincia'];

        try {
            $diputados = $this->calculoDeBancasService->calcularDiputados($provincia);
            $senadores = $this->calculoDeBancasService->calcularSenadores($provincia);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        return response()->json([
            'mensaje' => 'Cálculo de bancas realizado correctamente',
            'diputados' => $diputados,
            'senadores' => $senadores,
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function asignarBancas(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $data = $validator->validated();
        $provincia = $data['provincia'];
        try {
            $resultado = $this->calculoDeBancasService->asignarBancas($provincia);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        return response()->json([
            'mensaje' => 'Asignación de bancas realizada correctamente',
            'resultado' => $resultado,
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
