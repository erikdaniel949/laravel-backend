<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Listas;

class ListasControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_listas_index(): void
    {
        // Crear algunas listas de prueba
        $listas = Listas::factory(3)->create();

        $response = $this->getJson("/api/listas");
        
        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => ['id', 'provincia', 'cargo', 'lista', 'alianza']
            ]);
    }

    public function test_listas_show(): void
    {
        $lista = Listas::factory()->create();

        $response = $this->getJson("/api/listas/{$lista->id}");
        
        $response->assertStatus(200)
            ->assertJson([
                'id' => $lista->id,
                'provincia' => $lista->provincia,
                'cargo' => $lista->cargo,
                'lista' => $lista->lista,
                'alianza' => $lista->alianza
            ]);
    }

    public function test_listas_show_not_found(): void
    {
        $response = $this->getJson("/api/listas/999");
        
        $response->assertStatus(404)
            ->assertJson(['mensaje' => 'Lista no encontrada']);
    }

    public function test_listas_store(): void
    {
        $lista = [
            "provincia" => "Buenos Aires",
            "cargo" => "DIPUTADOS",
            "lista" => "Lista A",
            "alianza" => "Alianza XYZ"
        ];

        $response = $this->postJson("/api/listas", $lista);
        
        $response->assertStatus(201)
            ->assertJson([
                'mensaje' => 'Lista creada correctamente',
                'lista' => $lista
            ]);

        $this->assertDatabaseHas("listas", $lista);
    }

    public function test_listas_store_validation_fails(): void
    {
        $lista = [
            "provincia" => "Provincia Inválida",
            "cargo" => "CARGO_INVALIDO",
            "lista" => "Lista A",
            "alianza" => "Alianza XYZ"
        ];

        $response = $this->postJson("/api/listas", $lista);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['provincia', 'cargo']);
    }

    public function test_listas_update(): void
    {
        $listaOriginal = Listas::factory()->create();

        $listaModified = [
            "provincia" => "Buenos Aires",
            "cargo" => "DIPUTADOS",
            "lista" => "Lista A",
            "alianza" => "Alianza XYZ"
        ];

        $response = $this->putJson("/api/listas/{$listaOriginal->id}", $listaModified);

        $response->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Lista actualizada correctamente',
                'lista' => $listaModified
            ]);

        $this->assertDatabaseHas("listas", $listaModified);
    }

    public function test_listas_update_not_found(): void
    {
        $listaModified = [
            "provincia" => "Buenos Aires",
            "cargo" => "DIPUTADOS",
            "lista" => "Lista A",
            "alianza" => "Alianza XYZ"
        ];

        $response = $this->putJson("/api/listas/999", $listaModified);
        
        $response->assertStatus(404)
            ->assertJson(['mensaje' => 'Lista no encontrada']);
    }

    public function test_listas_destroy(): void
    {
        $lista = Listas::factory()->create();

        $response = $this->deleteJson("/api/listas/{$lista->id}");

        $response->assertStatus(200)
            ->assertJson(['mensaje' => 'Lista eliminada correctamente']);

        $this->assertDatabaseMissing('listas', ['id' => $lista->id]);
    }

    public function test_listas_destroy_not_found(): void
    {
        $response = $this->deleteJson("/api/listas/999");
        
        $response->assertStatus(404)
            ->assertJson(['mensaje' => 'Lista no encontrada']);
    }
}