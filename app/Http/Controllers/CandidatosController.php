<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Candidatos;


class CandidatosController extends Controller
{

    private function rules()
    {
        return [
            'provincia' => ['required', Rule::in(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'])],
            'cargo' => 'required|in:DIPUTADOS,SENADORES' ,
            'lista' => 'required|string|max:20',
            'nombre' => 'required|string|max:255',
            'orden_en_lista' => 'required|integer|max:10',
        ];
    }

    public function index()
    {
        $candidatos = Candidatos::all();
        return response()->json($candidatos);
    }
    
    public function show($id)
    {
        $candidato = Candidatos::find($id);
        if (!$candidato) {
            return response()->json(['mensaje' => 'Candidato no encontrado'], 404);
        }
        return response()->json($candidato);
    }

    public function store(Request $request)
    {
        $data = $request->_normalized_data ?? [];
        
        // Validación de campos individuales
        $validated = Validator::make($data, $this->rules());

        // Guardar el candidato en BD
        $candidato = Candidatos::create($validated->validated());

        return response()->json([
            'mensaje' => 'Candidato creado correctamente',
            'candidato' => $candidato
        ], 201);
    }

    public function update(Request $request, $id)
    {

        $candidato = Candidatos::find($id);
        if (!$candidato) {
            return response()->json(['mensaje' => 'Candidato no encontrado'], 404);
        }

        // Validación de campos individuales
        $validated = $request->validate($this->rules());


        // Actualizar el candidato en la BD
        $candidato->update($validated);

        return response()->json([
            'mensaje' => 'Candidato actualizado correctamente',
            'candidato' => $candidato
        ]);
    }

    public function destroy($id)
    {
        $candidato = Candidatos::find($id);
        if (!$candidato) {
            return response()->json(['mensaje' => 'Candidato no encontrado'], 404);
        }

        $candidato->delete();

        return response()->json(['mensaje' => 'Candidato eliminado correctamente']);
    }

    public function import(Request $request)
    {
        $data = $request->_normalized_data ?? [];

        $errores = [];
        $registrosGuardados = 0;

        foreach ($data as $index => $fila) {
            $validator = Validator::make($fila, $this->rules());

            if ($validator->fails()) {
                // registrar error de esta fila
                $errores[$index] = $validator->errors()->all();
                continue;
            }

            // guardar fila válida en BD
            Candidatos::create($fila);

            $registrosGuardados++;
        }
        return response()->json([
            'registros_guardados' => $registrosGuardados,
            'errores' => $errores,
        ]);

    }
}
