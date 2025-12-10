<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProvinciasService;

class ProvinciasController extends Controller
{
	protected $provinciasService;

	public function __construct(ProvinciasService $provinciasService)
	{
		$this->provinciasService = $provinciasService;
	}

	private function rules()
	{
		return [
			'provincia' => 'required|string|max:255'
		];
	}

	public function index()
	{
		$provincias = $this->provinciasService->obtenerTodos();
		return response()->json($provincias);
	}

	public function show($id)
	{
		$provincia = $this->provinciasService->obtenerPorId($id);
		if (!$provincia) {
			return response()->json(['mensaje' => 'Provincia no encontrada'], 404);
		}
		return response()->json($provincia);
	}

	public function store(Request $request)
	{
		$validated = $request->validate($this->rules());

		$provincia = $this->provinciasService->crear($validated);

		return response()->json([
			'mensaje' => 'Provincia creada correctamente',
			'provincia' => $provincia
		], 201);
	}

	public function update(Request $request, $id)
	{
		$provincia = $this->provinciasService->obtenerPorId($id);
		if (!$provincia) {
			return response()->json(['mensaje' => 'Provincia no encontrada'], 404);
		}

		$validated = $request->validate($this->rules());

		$this->provinciasService->actualizar($id, $validated);

		return response()->json([
			'mensaje' => 'Provincia actualizada correctamente',
			'provincia' => $this->provinciasService->obtenerPorId($id)
		]);
	}

	public function destroy($id)
	{
		$provincia = $this->provinciasService->obtenerPorId($id);
		if (!$provincia) {
			return response()->json(['mensaje' => 'Provincia no encontrada'], 404);
		}

		$this->provinciasService->eliminar($id);

		return response()->json(['mensaje' => 'Provincia eliminada correctamente']);
	}
}

