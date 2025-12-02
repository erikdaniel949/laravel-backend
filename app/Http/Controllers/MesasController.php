<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Mesas;

class MesasController extends Controller
{
    private function rules()
    {
        return [
            'id_mesa' => 'required|integer|between:1,10000',
            'provincia' => ['required', Rule::in(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'])],
            'circuito' => 'required|string|max:20',
            'establecimiento' => 'required|string|max:255',
            'electores' => 'required|integer|min:0',
        ];
    }

    public function index()
    {
        $mesas = Mesas::all();
        return response()->json($mesas);
    }
    
    public function show($id)
    {
        $mesa = Mesas::find($id);
        if (!$mesa) {
            return response()->json(['mensaje' => 'Mesa no encontrada'], 404);
        }
        return response()->json($mesa);
    }

    public function store(Request $request)
    {
        // Validacion 
        $validated = $request->validate($this->rules());

        // Crear el registro con los datos validados
        $mesa = Mesas::create($validated);

        return response()->json([
            'mensaje' => 'Mesa creada correctamente',
            'mesa' => $mesa
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $mesa = Mesas::find($id);
        if (!$mesa) {
            return response()->json(['mensaje' => 'Mesa no encontrada'], 404);
        }

        // Validación de campos individuales
        $validated = $request->validate($this->rules());

        // Actualizar la mesa en la BD
        $mesa->update($validated);

        return response()->json([
            'mensaje' => 'Mesa actualizada correctamente',
            'mesa' => $mesa
        ]);
    }

    public function destroy($id)
    {
        $mesa = Mesas::find($id);
        if (!$mesa) {
            return response()->json(['mensaje' => 'Mesa no encontrada'], 404);
        }

        $mesa->delete();

        return response()->json(['mensaje' => 'Mesa eliminada correctamente']);
    }
}