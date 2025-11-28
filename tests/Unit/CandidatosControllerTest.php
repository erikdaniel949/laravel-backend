<?php
namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Models\Candidatos;

class CandidatosControllerTest extends TestCase
{
    public function test_candidatos_index()
    {
        $modelMock = Mockery::mock('alias:App\Models\Candidatos');

        $modelMock->shouldReceive('all')
            ->once()
            ->andReturn((object) [
                [
                    'id' => 1,
                    'provincia' => 'Buenos Aires',
                    'cargo' => 'DIPUTADOS' ,
                    'lista' => 'Lista A',
                    'nombre' => 'nombre de prueba',
                    'orden_en_lista' => 1
                ],
                [
                    'id' => 2,
                    'provincia' => 'Buenos Aires',
                    'cargo' => 'SENADORES' ,
                    'lista' => 'Lista A',
                    'nombre' => 'nombre de prueba2',
                    'orden_en_lista' => 2
                ]
                ]);
                
    }


    public function test_candidatos_show_asserts(): void
    {   
        // Creamos un mock del modelo Candidatos
        $modelMock = Mockery::mock('alias:App\Models\Candidatos');
        
        // Simulamos que 'find(1)' devuelve un objeto candidato
        $modelMock->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn((object) [
                'id' => 1,
                'provincia' => 'Buenos Aires',
                'cargo' => 'DIPUTADOS' ,
                'lista' => 'Lista A',
                'nombre' => 'nombre de prueba',
                'orden_en_lista' => 1
            ]);

        // Hacemos la solicitud GET a la API
        $response = $this->json('GET', '/api/candidatos/1');

        // Verificamos que la respuesta sea la esperada
        $response->assertStatus(200)
            ->assertJson([
                'id' => 1,
                'provincia' => 'Buenos Aires',
                'cargo' => 'DIPUTADOS' ,
                'lista' => 'Lista A',
                'nombre' => 'nombre de prueba',
                'orden_en_lista' => 1
            ]);
    }
    public function test_candidatos_show_fail()
    {
        $modelMock = Mockery::mock('alias:App\Models\Candidatos');

        $modelMock->shouldReceive('find')
            ->with(12345)
            ->once()
            ->andReturn(null);

        $response = $this->json('GET', '/api/candidatos/12345');

        $response->assertStatus(404)
            ->assertJson([
                'mensaje' => 'Candidato no encontrado'
            ]);
    }
}
