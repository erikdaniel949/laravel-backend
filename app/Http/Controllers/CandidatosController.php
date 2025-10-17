<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Candidatos;


class CandidatosController extends Controller
{
    public function create(Request $request)
    {
        // Validación de campos individuales
        $validated = $request->validate([
            'provincia' => 'required|string|max:50',
            'cargo' => 'required|string|max:20',
            'lista' => 'required|string|max:20',
            'nombre' => 'required|string|max:255',
            'orden_en_lista' => 'required|integer|max:1000',
        ]);

        // Guardar el candidato en BD
        $candidato = Candidatos::create($validated);

        return response()->json([
            'mensaje' => 'Candidato creado correctamente',
            'candidato' => $candidato
        ]);
    }


    public function import(Request $request)
    {
        $data = $request->_normalized_data ?? [];

        $errores = [];
        $registrosGuardados = 0;

        foreach ($data as $fila) {
            $validator = Validator::make($fila, [
                'provincia' => 'required|string|max:50',
                'cargo' => 'required|string|max:20',
                'lista' => 'required|string|max:20',
                'nombre' => 'required|string|max:255',
                'orden_en_lista' => 'required|integer|max:1000',
            ]);

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
