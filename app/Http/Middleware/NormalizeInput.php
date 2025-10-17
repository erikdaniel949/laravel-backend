<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NormalizeInput
{
    public function handle(Request $request, Closure $next)
    {
        // Si hay archivo subido
        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $path = $file->getRealPath();
            $extension = strtolower($file->getClientOriginalExtension());

            $data = [];

            // CSV → JSON (array)
            if ($extension === 'csv') {
                $rows = array_map('str_getcsv', file($path));
                $header = array_shift($rows);
                foreach ($rows as $row) {
                    $data[] = array_combine($header, $row);
                }
            }

            // JSON → array
            elseif ($extension === 'json') {
                $data = json_decode(file_get_contents($path), true);
            }

            // Agregamos los datos normalizados al Request
            $request->merge(['_normalized_data' => $data]);
        }

        // Si es JSON directo
        elseif ($request->isJson()) {
            $request->merge(['_normalized_data' => $request->json()->all()]);
        }

        return $next($request);
    }
}
