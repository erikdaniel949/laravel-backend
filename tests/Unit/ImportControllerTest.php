<?php

namespace Tests\Unit;


use Tests\TestCase;
use Mockery;;
use App\Models\Candidatos;
use App\Http\controllers\ImportController;
use Illuminate\Testing\TestResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ImportControllerTest extends TestCase
{
    public function test_import_candidatos_csv_asserts(): void
    {
        $csvFile = base_path('tests/Unit/fixtures/candidatos_test_200.csv');

        $modelMock = Mockery::mock('overload:' . Candidatos::class);
        $modelMock->shouldReceive('create')
            ->times(3)
            ->andReturn([
                'provincia' => 'Buenos Aires',
                'cargo' => 'DIPUTADOS',
                'lista' => 'Lista A',
                'nombre' => 'nombre 1',
                'orden_en_lista' => 1
            ]);

        $controller = new ImportController();
        $request = Request::create('/import', 'POST', [], [], [
            'archivo' => new UploadedFile(
                $csvFile,
                'candidatos_test_200.csv',
                'text/csv',
                null,
                true
            )
        ]);

        $response = $controller->import($request);

        $testResponse = new TestResponse($response);

        $testResponse->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Archivo CSV importado', 
                'errors' => [],
                'tablasLeidas' => ['candidatos'],
                'registrosLeidos' => [
                    [
                        'Buenos Aires',
                        'DIPUTADOS',
                        'Lista A',
                        'nombre 1',
                        '1'
                    ],
                    [
                        'Buenos Aires',
                        'SENADORES',
                        'Lista A',
                        'nombre 2',
                        '2'
                    ],
                    [
                        'Buenos Aires',
                        'DIPUTADOS',
                        'Lista B',
                        'nombre 3',
                        '1'
                    ]
                ]
            ]);
    }

    function test_import_candidatos_csv_partially_asserts()
    {
        $csvFile = base_path('tests/Unit/fixtures/candidatos_test_207.csv');

        // Simular un error lanzando una excepción al llamar a create
        $modelMock = Mockery::mock('overload:' . Candidatos::class);
        $modelMock->shouldReceive('create')
            ->andReturn([
                'provincia' => 'Buenos Aires',
                'cargo' => 'DIPUTADOS',
                'lista' => 'Lista A',
                'nombre' => 'nombre 1',
                'orden_en_lista' => 1
            ]);

        $controller = new ImportController();
        $request = Request::create('/import', 'POST', [], [], [
            'archivo' => new UploadedFile(
                $csvFile,
                'candidatos_test_207.csv',
                'text/csv',
                null,
                true
            )
        ]);

        $response = $controller->import($request);

        $testResponse = new TestResponse($response);

        $testResponse->assertStatus(207)
            ->assertJson([
                'mensaje' => 'Archivo CSV importado',
                'errors' => [
                    [
                        'tabla' => [
                            'candidatos'
                        ],
                        'data' => [
                            'Buenos aires',
                            'SENADORES',
                            'Lista 1',
                            'Mal',
                            '2'
                        ],
                        'errors' => [
                            'The selected provincia is invalid.'
                        ]
                    ],
                    [
                        'tabla' => [
                            'candidatos'
                        ],
                        'data' => [
                            'Buenos Aires',
                            'DIPUTADOS',
                            'Lista B',
                            'Mal',
                            '1000'
                        ],
                        'errors' => [
                            'The orden en lista field must not be greater than 10.'
                        ]
                    ]
                ],
                'tablasLeidas' => ['candidatos'],
            ]);
    }

}
