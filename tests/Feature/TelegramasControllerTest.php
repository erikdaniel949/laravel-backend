<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Telegramas;

class TelegramasControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_telegramas_index(): void
    {
        // Crear algunos telegramas de prueba
        $telegramas = Telegramas::factory(3)->create();

        $response = $this->getJson("/api/telegramas");
        
        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'id', 
                    'id_mesa', 
                    'provincia', 
                    'lista', 
                    'votos_diputados', 
                    'votos_senadores',
                    'blancos', 
                    'nulos', 
                    'recurridos'
                ]
            ]);
    }

    public function test_telegramas_show(): void
    {
        $telegrama = Telegramas::factory()->create();

        $response = $this->getJson("/api/telegramas/{$telegrama->id}");
        
        $response->assertStatus(200)
            ->assertJson([
                'id' => $telegrama->id,
                'id_mesa' => $telegrama->id_mesa,
                'provincia' => $telegrama->provincia,
                'lista' => $telegrama->lista,
                'votos_diputados' => $telegrama->votos_diputados,
                'votos_senadores' => $telegrama->votos_senadores,
                'blancos' => $telegrama->blancos,
                'nulos' => $telegrama->nulos,
                'recurridos' => $telegrama->recurridos
            ]);
    }

    public function test_telegramas_show_not_found(): void
    {
        $response = $this->getJson("/api/telegramas/999");
        
        $response->assertStatus(404)
            ->assertJson(['mensaje' => 'Telegrama no encontrado']);
    }

    public function test_telegramas_store(): void
    {
        $telegrama = [
            "id_mesa" => "1001",
            "provincia" => "Buenos Aires",
            "lista" => "Lista A",
            "votos_diputados" => 100,
            "votos_senadores" => 95,
            "blancos" => 10,
            "nulos" => 5,
            "recurridos" => 2
        ];

        $response = $this->postJson("/api/telegramas", $telegrama);
        
        $response->assertStatus(201)
            ->assertJson([
                'mensaje' => 'Telegrama creado correctamente',
                'telegrama' => $telegrama
            ]);

        $this->assertDatabaseHas("telegramas", $telegrama);
    }

    public function test_telegramas_store_validation_fails(): void
    {
        $telegrama = [
            "id_mesa" => "1001",
            "provincia" => "Provincia Inválida",
            "lista" => "",
            "votos_diputados" => -1,
            "votos_senadores" => -1,
            "blancos" => -1,
            "nulos" => -1,
            "recurridos" => -1
        ];

        $response = $this->postJson("/api/telegramas", $telegrama);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'provincia', 'lista',
                'votos_diputados', 'votos_senadores',
                'blancos', 'nulos', 'recurridos'
            ]);
    }

    public function test_telegramas_update(): void
    {
        $telegramaOriginal = Telegramas::factory()->create();

        $telegramaModified = [
            "id_mesa" => "1001",
            "provincia" => "Buenos Aires",
            "lista" => "Lista A",
            "votos_diputados" => 100,
            "votos_senadores" => 95,
            "blancos" => 10,
            "nulos" => 5,
            "recurridos" => 2
        ];

        $response = $this->putJson("/api/telegramas/{$telegramaOriginal->id}", $telegramaModified);

        $response->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Telegrama actualizado correctamente',
                'telegrama' => $telegramaModified
            ]);

        $this->assertDatabaseHas("telegramas", $telegramaModified);
    }

    public function test_telegramas_update_not_found(): void
    {
        $telegramaModified = [
            "id_mesa" => "1001",
            "provincia" => "Buenos Aires",
            "lista" => "Lista A",
            "votos_diputados" => 100,
            "votos_senadores" => 95,
            "blancos" => 10,
            "nulos" => 5,
            "recurridos" => 2
        ];

        $response = $this->putJson("/api/telegramas/999", $telegramaModified);
        
        $response->assertStatus(404)
            ->assertJson(['mensaje' => 'Telegrama no encontrado']);
    }

    public function test_telegramas_destroy(): void
    {
        $telegrama = Telegramas::factory()->create();

        $response = $this->deleteJson("/api/telegramas/{$telegrama->id}");

        $response->assertStatus(200)
            ->assertJson(['mensaje' => 'Telegrama eliminado correctamente']);

        $this->assertDatabaseMissing('telegramas', ['id' => $telegrama->id]);
    }

    public function test_telegramas_destroy_not_found(): void
    {
        $response = $this->deleteJson("/api/telegramas/999");
        
        $response->assertStatus(404)
            ->assertJson(['mensaje' => 'Telegrama no encontrado']);
    }
}