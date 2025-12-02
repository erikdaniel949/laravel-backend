<?php
namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Models\Candidatos;
use App\Http\controllers\CandidatosController;
use Illuminate\Testing\TestResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CandidatosControllerTest extends TestCase
{
    public function test_candidatos_index()
    {
        // Mockear el modelo para evitar que acceda a la BD
        $modelMock = Mockery::mock('overload:' . Candidatos::class);
        $modelMock->shouldReceive('all')
            ->once()
            ->andReturn([
                [
                    'id' => 1,
                    'provincia' => 'Buenos Aires',
                    'cargo' => 'DIPUTADOS',
                    'lista' => 'Lista A',
                    'nombre' => 'nombre de prueba',
                    'orden_en_lista' => 1
                ],
                [
                    'id' => 2,
                    'provincia' => 'Buenos Aires',
                    'cargo' => 'SENADORES',
                    'lista' => 'Lista A',
                    'nombre' => 'nombre de prueba2',
                    'orden_en_lista' => 2
                ]
            ]);

        // Crear controller
        $controller = new CandidatosController();

        // Ejecutar método
        $response = $controller->index();

        // Envolver respuesta en TestResponse para usar asserts de Laravel
        $testResponse = TestResponse::fromBaseResponse($response);

        $testResponse
            ->assertStatus(200)
            ->assertJsonFragment(['nombre' => 'nombre de prueba2']);
    }

    public function test_candidatos_show_asserts(): void
    {   
        // Creamos un mock del modelo Candidatos
        $modelMock = Mockery::mock('overload:' . Candidatos::class);
        
        // Simulamos que 'find(1)' devuelve un objeto candidato
        $modelMock->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn((object) [
                'id' => 1,
                'provincia' => 'Buenos Aires',
                'cargo' => 'DIPUTADOS',
                'lista' => 'Lista A',
                'nombre' => 'nombre de prueba',
                'orden_en_lista' => 1
            ]);

        $controller = new CandidatosController();

        $response = $controller->show(1);

        $testResponse = TestResponse::fromBaseResponse($response);

        $testResponse->assertStatus(200)
            ->assertJson([
                'id' => 1,
                'provincia' => 'Buenos Aires',
                'cargo' => 'DIPUTADOS',
                'lista' => 'Lista A',
                'nombre' => 'nombre de prueba',
                'orden_en_lista' => 1
            ]);
    }

    public function test_candidatos_show_fails()
    {
        $modelMock = Mockery::mock('overload:' . Candidatos::class);

        $modelMock->shouldReceive('find')
            ->with(12345)
            ->once()
            ->andReturn(null);

        $controller = new CandidatosController();

        $response = $controller->show(12345);

        $testResponse = TestResponse::fromBaseResponse($response);

        $testResponse->assertStatus(404)
            ->assertJson([
                'mensaje' => 'Candidato no encontrado'
            ]);
    }

    public function test_candidatos_store_asserts()
    {
        $modelMock = Mockery::mock('overload:' . Candidatos::class);

        $modelMock->shouldReceive('create')
            ->with([
                'provincia' => 'Buenos Aires',
                'cargo' => 'DIPUTADOS',
                'lista' => 'Lista A',
                'nombre' => 'nombre de prueba',
                'orden_en_lista' => 1
            ])
            ->once()
            ->andReturn([
                'id' => 1,
                'provincia' => 'Buenos Aires',
                'cargo' => 'DIPUTADOS',
                'lista' => 'Lista A',
                'nombre' => 'nombre de prueba',
                'orden_en_lista' => 1
            ]);

        $controller = new CandidatosController();

        $response = $controller->store(Request::create('/candidatos', 'POST', [
            'provincia' => 'Buenos Aires',
            'cargo' => 'DIPUTADOS',
            'lista' => 'Lista A',
            'nombre' => 'nombre de prueba',
            'orden_en_lista' => 1
        ]));

        $testResponse = TestResponse::fromBaseResponse($response);

        $testResponse->assertStatus(201)
            ->assertJson([
                'mensaje' => 'Candidato creado correctamente',
                'candidato' => [
                    'id' => 1,
                    'provincia' => 'Buenos Aires',
                    'cargo' => 'DIPUTADOS',
                    'lista' => 'Lista A',
                    'nombre' => 'nombre de prueba',
                    'orden_en_lista' => 1
                ]
            ]);
    }

    public function test_candidatos_store_fails_validation()
    {
        $modelMock = Mockery::mock('overload:' . Candidatos::class);

        $controller = new CandidatosController();

        $this->expectException(ValidationException::class);

        $controller->store(Request::create('/candidatos', 'POST', [
            'provincia' => '', // inválido
            'cargo' => 'INVALIDO', // inválido
            'lista' => '', // inválido
            'nombre' => '', // inválido
            'orden_en_lista' => 20 // inválido
        ]));
    }
    
    public function test_candidatos_update_asserts()
    {
        // Mock del modelo
        $modelMock = Mockery::mock('overload:' . Candidatos::class);

        $candidatoMock = Mockery::mock(stdClass::class);
        $candidatoMock->shouldReceive('update')
            ->with([
                'provincia' => 'Córdoba',
                'cargo' => 'SENADORES',
                'lista' => 'Lista B',
                'nombre' => 'nombre actualizado',
                'orden_en_lista' => 2
            ])
            ->once()
            ->andReturnTrue(); // ✅ simula éxito, no devuelve el array

        // Simular que el objeto tiene las propiedades ya actualizadas
        $candidatoMock->id = 1;
        $candidatoMock->provincia = 'Córdoba';
        $candidatoMock->cargo = 'SENADORES';
        $candidatoMock->lista = 'Lista B';
        $candidatoMock->nombre = 'nombre actualizado';
        $candidatoMock->orden_en_lista = 2;


        // Cuando se llame a `find(1)`, devolverá ese mock de candidato
        $modelMock->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($candidatoMock);

        // Ejecutar el update del controller
        $controller = new CandidatosController();
        $response = $controller->update(Request::create('/candidatos/1', 'PUT', [
            'provincia' => 'Córdoba',
            'cargo' => 'SENADORES',
            'lista' => 'Lista B',
            'nombre' => 'nombre actualizado',
            'orden_en_lista' => 2
        ]), 1);

        $testResponse = TestResponse::fromBaseResponse($response);

        $testResponse->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Candidato actualizado correctamente',
                'candidato' => [
                    'id' => 1,
                    'provincia' => 'Córdoba',
                    'cargo' => 'SENADORES',
                    'lista' => 'Lista B',
                    'nombre' => 'nombre actualizado',
                    'orden_en_lista' => 2
                ]
            ]);
    }

    public function test_candidatos_update_fails_validation()
    {
        $modelMock = Mockery::mock('overload:' . Candidatos::class);

        $candidatoMock = Mockery::mock(stdClass::class);

        $modelMock->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($candidatoMock);

        $controller = new CandidatosController();

        $this->expectException(ValidationException::class);

        $controller->update(Request::create('/candidatos/1', 'PUT', [
            'provincia' => '', // inválido
            'cargo' => 'INVALIDO', // inválido
            'lista' => '', // inválido
            'nombre' => '', // inválido
            'orden_en_lista' => 20 // inválido
        ]), 1);
    }

    public function test_candidatos_destroy_asserts()
    {
        $modelMock = Mockery::mock('overload:' . Candidatos::class);
        $candidatoMock = Mockery::mock(stdClass::class);

        $candidatoMock->shouldReceive('delete')
            ->once()
            ->andReturnTrue();

        $modelMock->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($candidatoMock);

        $controller = new CandidatosController();

        $response = $controller->destroy(1);

        $testResponse = TestResponse::fromBaseResponse($response);

        $testResponse->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Candidato eliminado correctamente'
            ]);
    }

    public function test_candidatos_destroy_fails()
    {
        $modelMock = Mockery::mock('overload:' . Candidatos::class);

        $modelMock->shouldReceive('find')
            ->with(9999)
            ->once()
            ->andReturn(null);

        $controller = new CandidatosController();

        $response = $controller->destroy(9999);

        $testResponse = TestResponse::fromBaseResponse($response);

        $testResponse->assertStatus(404)
            ->assertJson([
                'mensaje' => 'Candidato no encontrado'
            ]);
    }
}