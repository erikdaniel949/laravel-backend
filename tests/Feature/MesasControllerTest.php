<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Mesas;

class MesasControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_mesas_index(): void
    {
        // Crear algunas mesas de prueba
        $mesas = Mesas::factory(3)->create();

        $response = $this->getJson("/api/mesas");
        
        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => ['id_mesa', 'provincia', 'circuito', 'establecimiento', 'electores']
            ]);
    }

    public function test_mesas_show(): void
    {
        $mesa = Mesas::factory()->create();

        $response = $this->getJson("/api/mesas/{$mesa->id_mesa}");
        
        $response->assertStatus(200)
            ->assertJson([
                'id_mesa' => $mesa->id_mesa,
                'provincia' => $mesa->provincia,
                'circuito' => $mesa->circuito,
                'establecimiento' => $mesa->establecimiento,
                'electores' => $mesa->electores
            ]);
    }

    public function test_mesas_show_not_found(): void
    {
        $response = $this->getJson("/api/mesas/999");
        
        $response->assertStatus(404)
            ->assertJson(['mensaje' => 'Mesa no encontrada']);
    }

    public function test_mesas_store(): void
    {
        $mesa = [
            "id_mesa" => "1001",
            "provincia" => "Buenos Aires",
            "circuito" => "001",
            "establecimiento" => "Escuela N°1",
            "electores" => 350
        ];

        $response = $this->postJson("/api/mesas", $mesa);
        
        $response->assertStatus(201)
            ->assertJson([
                'mensaje' => 'Mesa creada correctamente',
                'mesa' => $mesa
            ]);

        $this->assertDatabaseHas("mesas", $mesa);
    }

    public function test_mesas_store_validation_fails(): void
    {
        $mesa = [
            "id_mesa" => "1001",
            "provincia" => "Provincia Inválida",
            "circuito" => "",
            "establecimiento" => "",
            "electores" => -1
        ];

        $response = $this->postJson("/api/mesas", $mesa);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['provincia', 'circuito', 'establecimiento', 'electores']);
    }

    public function test_mesas_update(): void
    {
        $mesaOriginal = Mesas::factory()->create();

        $mesaModified = [
            "id_mesa" => "1001",
            "provincia" => "Buenos Aires",
            "circuito" => "001",
            "establecimiento" => "Escuela N°1",
            "electores" => 350
        ];

        $response = $this->putJson("/api/mesas/{$mesaOriginal->id_mesa}", $mesaModified);

        $response->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Mesa actualizada correctamente',
                'mesa' => $mesaModified
            ]);

        $this->assertDatabaseHas("mesas", $mesaModified);
    }

    public function test_mesas_update_not_found(): void
    {
        $mesaModified = [
            "id_mesa" => "1001",
            "provincia" => "Buenos Aires",
            "circuito" => "001",
            "establecimiento" => "Escuela N°1",
            "electores" => 350
        ];

        $response = $this->putJson("/api/mesas/999", $mesaModified);
        
        $response->assertStatus(404)
            ->assertJson(['mensaje' => 'Mesa no encontrada']);
    }

    public function test_mesas_destroy(): void
    {
        $mesa = Mesas::factory()->create();

        $response = $this->deleteJson("/api/mesas/{$mesa->id_mesa}");

        $response->assertStatus(200)
            ->assertJson(['mensaje' => 'Mesa eliminada correctamente']);

        $this->assertDatabaseMissing('mesas', ['id_mesa' => $mesa->id_mesa]);
    }

    public function test_mesas_destroy_not_found(): void
    {
        $response = $this->deleteJson("/api/mesas/999");
        
        $response->assertStatus(404)
            ->assertJson(['mensaje' => 'Mesa no encontrada']);
    }
}