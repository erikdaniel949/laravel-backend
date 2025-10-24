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
        $response = $this->get("/api/candidatos");
        $response->assertStatus(200);
    }

    public function test_candidatos_show(): void
    {
        $candidato = Candidatos::factory()->create();

        $response = $this->get("/api/candidatos/{$candidato->id}");
        $response->assertStatus(200);
    }

    public function test_candidatos_store(): void
    {
        $response = $this->postJson("/api/candidatos/store", [
            "provincia" => "Buenos Aires",
            "cargo" => "DIPUTADOS",
            "lista" => "Lista A",
            "nombre" => "Juan Perez",
            "orden_en_lista" => 1
        ]);

        
        $response->assertStatus(201);

        $this->assertDatabaseHas("candidatos", [
            "provincia" => "Buenos Aires",
            "cargo" => "DIPUTADOS",
            "lista" => "Lista A",
            "nombre" => "Juan Perez",
            "orden_en_lista" => 1
        ]);
    }

    public function test_candidatos_update(): void
    {
        $candidato = Candidatos::factory()->create();

        $response = $this->putJson("/api/candidatos/{$candidato->id}", [
            "provincia" => "CABA",
            "cargo" => "SENADORES",
            "lista" => "Lista B",
            "nombre" => "Maria Gomez",
            "orden_en_lista" => 2
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas("candidatos", [
            "id" => $candidato->id,
            "provincia" => "CABA",
            "cargo" => "SENADORES",
            "lista" => "Lista B",
            "nombre" => "Maria Gomez",
            "orden_en_lista" => 2
        ]);
    }

    public function test_candidatos_destroy(): void
    {
        $candidato = Candidatos::factory()->create();

        $response = $this->delete("candidatos/{$candidato->id}");

        $response->assertStatus(200);

    }
}
