<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Listas;
use App\Models\Telegramas;
use Illuminate\Support\Facades\DB;

class ListasController extends Controller
{
    private function rules()
    {
        return [
            'provincia' => ['required', Rule::in(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'])],
            'cargo' => 'required|in:DIPUTADOS,SENADORES',
            'lista' => 'required|string|max:20',
            'alianza' => 'required|string|max:255',
        ];
    }

    public function index()
    {
        $listas = Listas::all();
        return response()->json($listas);
    }
    
    public function show($id)
    {
        $lista = Listas::find($id);
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
        $lista = Listas::create($validated);

        return response()->json([
            'mensaje' => 'Lista creada correctamente',
            'lista' => $lista
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $lista = Listas::find($id);
        if (!$lista) {
            return response()->json(['mensaje' => 'Lista no encontrada'], 404);
        }

        // Validación de campos individuales
        $validated = $request->validate($this->rules());

        // Actualizar la lista en la BD
        $lista->update($validated);

        return response()->json([
            'mensaje' => 'Lista actualizada correctamente',
            'lista' => $lista
        ]);
    }

    public function destroy($id)
    {
        $lista = Listas::find($id);
        if (!$lista) {
            return response()->json(['mensaje' => 'Lista no encontrada'], 404);
        }

        $lista->delete();

        return response()->json(['mensaje' => 'Lista eliminada correctamente']);
    }

    public function totales()
    {
        // Agrupar por lista usando Eloquent (modelo Telegramas)
        $listas = Telegramas::select('lista')
            ->selectRaw('SUM(votos_diputados) as votos_diputados, SUM(votos_senadores) as votos_senadores')
            ->groupBy('lista')
            ->get();

        // Totales generales a partir del modelo
        $totalDiputados = (int) Telegramas::sum('votos_diputados');
        $totalSenadores = (int) Telegramas::sum('votos_senadores');
        $totalBlancos = (int) Telegramas::sum('blancos');
        $totalNulos = (int) Telegramas::sum('nulos');
        $totalRecurridos = (int) Telegramas::sum('recurridos');
        $totalEmitidos = $totalDiputados + $totalSenadores + $totalBlancos + $totalNulos + $totalRecurridos;
        
        $resultado = ['totales_generales' => [
            'votos_diputados' => $totalDiputados,
            'votos_senadores' => $totalSenadores,
            'blancos' => $totalBlancos,
            'nulos' => $totalNulos,
            'recurridos' => $totalRecurridos,
            'participacion' => $totalEmitidos,
        ]];

        foreach ($listas as $lista) {
            $vd = (int) $lista->votos_diputados;
            $vs = (int) $lista->votos_senadores;

            $resultado[$lista->lista] = [
                'votos_diputados' => $vd,
                'votos_senadores' => $vs,
                'porcentaje_diputados' => $totalDiputados ? round(($vd / $totalDiputados) * 100, 2) : 0,
                'porcentaje_senadores' => $totalSenadores ? round(($vs / $totalSenadores) * 100, 2) : 0,
            ];
        }

        return response()->json($resultado, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}