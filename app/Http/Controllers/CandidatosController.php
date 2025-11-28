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
            'orden_en_lista' => 'required|integer|between:1,10',
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
        // Validacion 
        $validated = $request->validate($this->rules());

        // Crear el registro con los datos validados
        $candidato = Candidatos::create($validated);

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

    public function total($id)
    {
        $candidato = Candidatos::with('provincia')->find($id);

        $votosTotales = $candidato->provincia();

        return response()->json([
            'resultado' => $votosTotales
        ], 200, [], JSON_PRETTY_PRINT);
    }

}
