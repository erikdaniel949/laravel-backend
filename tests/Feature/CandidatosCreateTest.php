<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Candidatos;

class CandidatosCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidatos_create(): void
    {
        $response = $this->post('/candidatos/create', [
            'provincia' => 'Buenos Aires',
            'cargo' => 'DIPUTADOS',
            'lista' => 'Lista A',
            'nombre' => 'Juan Perez',
            'orden_en_lista' => 1
        ]);

    
        $response->assertStatus(200);

        $this->assertDatabaseHas('candidatos', [
            'provincia' => 'Buenos Aires',
            'cargo' => 'DIPUTADOS',
            'lista' => 'Lista A',
            'nombre' => 'Juan Perez',
            'orden_en_lista' => 1
        ]);
    }
}
