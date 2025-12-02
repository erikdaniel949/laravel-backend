<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Mockery;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ListasController;
use Illuminate\Testing\TestResponse;

class ListasControllerTest extends TestCase
{
    public function test_listas_totales_asserts()
    {
        // 1) Mock de datos de la primera consulta
        $listasMock = collect([
            (object)[ 'lista' => 'Lista A', 'votos_diputados' => 100, 'votos_senadores' => 50 ],
            (object)[ 'lista' => 'Lista B', 'votos_diputados' => 40,  'votos_senadores' => 10 ],
        ]);

        // 2) Mock de la segunda consulta
        $complementariosMock = collect([
            (object)[ 'votos_blancos' => 20, 'votos_nulos' => 5, 'votos_recurridos' => 2 ],
        ]);

        // 3) Mock del Query Builder fluido
        $queryMock = Mockery::mock();
        $queryMock->shouldReceive('select')->andReturnSelf();
        $queryMock->shouldReceive('groupBy')->andReturnSelf();
        $queryMock->shouldReceive('get')
                ->andReturn($listasMock, $complementariosMock);
        DB::shouldReceive('raw')->andReturnUsing(function ($value) {
            return $value;
        });


        // 4) Mock de DB::table()
        DB::shouldReceive('table')
            ->with('telegramas')
            ->andReturn($queryMock);

        // Ejecutar
        $controller = new ListasController();
        $response = $controller->totales();
        $testResponse = new TestResponse($response);

        // 5) Asserts
        $testResponse->assertEquals(140, $json['totales_generales']['votos_diputados']) // 100+40
            ->assertEquals(60,  $json['totales_generales']['votos_senadores']) // 50+10
            ->assertEquals(20,  $json['totales_generales']['blancos'])
            ->assertEquals(5,   $json['totales_generales']['nulos'])
            ->assertEquals(2,   $json['totales_generales']['recurridos']);
    }

}
