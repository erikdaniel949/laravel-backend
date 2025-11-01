<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Candidatos;

class CandidatosControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidatos_index(): void
    {
        // Crear algunos candidatos de prueba
        $candidatos = Candidatos::factory(3)->create();

        $response = $this->getJson("/api/candidatos");
        
        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => ['id', 'provincia', 'cargo', 'lista', 'nombre', 'orden_en_lista']
            ]);
    }

    public function test_candidatos_show(): void
    {
        $candidato = Candidatos::factory()->create();

        $response = $this->getJson("/api/candidatos/{$candidato->id}");
        
        $response->assertStatus(200)
            ->assertJson([
                'id' => $candidato->id,
                'provincia' => $candidato->provincia,
                'cargo' => $candidato->cargo,
                'lista' => $candidato->lista,
                'nombre' => $candidato->nombre,
                'orden_en_lista' => $candidato->orden_en_lista
            ]);
    }

    public function test_candidatos_show_not_found(): void
    {
        $response = $this->getJson("/api/candidatos/999");
        
        $response->assertStatus(404)
            ->assertJson(['mensaje' => 'Candidato no encontrado']);
    }

    public function test_candidatos_store(): void
    {
        $candidato = [
            "provincia" => "Buenos Aires",
            "cargo" => "DIPUTADOS",
            "lista" => "Lista A",
            "nombre" => "Juan Perez",
            "orden_en_lista" => 1
        ];

        $response = $this->postJson("/api/candidatos", $candidato);
        
        $response->assertStatus(201)
            ->assertJson([
                'mensaje' => 'Candidato creado correctamente',
                'candidato' => $candidato
            ]);

        $this->assertDatabaseHas("candidatos", $candidato);
    }

    public function test_candidatos_store_validation_fails(): void
    {
        $candidato = [
            "provincia" => "Provincia Inválida",
            "cargo" => "CARGO_INVALIDO",
            "lista" => "Lista A",
            "nombre" => "Juan Perez",
            "orden_en_lista" => 1
        ];

        $response = $this->postJson("/api/candidatos", $candidato);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['provincia', 'cargo']);
    }

    public function test_candidatos_update(): void
    {
        $candidatoOriginal = Candidatos::factory()->create();

        $candidatoModified = [
            "provincia" => "Buenos Aires",
            "cargo" => "DIPUTADOS",
            "lista" => "Lista A",
            "nombre" => "Juan Perez",
            "orden_en_lista" => 1
        ];

        $response = $this->putJson("/api/candidatos/{$candidatoOriginal->id}", $candidatoModified);

        $response->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Candidato actualizado correctamente',
                'candidato' => $candidatoModified
            ]);

        $this->assertDatabaseHas("candidatos", $candidatoModified);
    }

    public function test_candidatos_update_not_found(): void
    {
        $candidatoModified = [
            "provincia" => "Buenos Aires",
            "cargo" => "DIPUTADOS",
            "lista" => "Lista A",
            "nombre" => "Juan Perez",
            "orden_en_lista" => 1
        ];

        $response = $this->putJson("/api/candidatos/999", $candidatoModified);
        
        $response->assertStatus(404)
            ->assertJson(['mensaje' => 'Candidato no encontrado']);
    }

    public function test_candidatos_destroy(): void
    {
        $candidato = Candidatos::factory()->create();

        $response = $this->deleteJson("/api/candidatos/{$candidato->id}");

        $response->assertStatus(200)
            ->assertJson(['mensaje' => 'Candidato eliminado correctamente']);

        $this->assertDatabaseMissing('candidatos', ['id' => $candidato->id]);
    }

    public function test_candidatos_destroy_not_found(): void
    {
        $response = $this->deleteJson("/api/candidatos/999");
        
        $response->assertStatus(404)
            ->assertJson(['mensaje' => 'Candidato no encontrado']);
    }
}
