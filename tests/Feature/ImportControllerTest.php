<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_import_csv(): void
    {
        // Falsa storage para no tocar disco real
        Storage::fake('local');

        $csvContent = "candidatos\n" .
            "provincia,cargo,lista,nombre,orden_en_lista\n" .
            "Buenos Aires,DIPUTADOS,Lista A,Erik,1\n" .
            "Buenos Aires,SENADORES,Lista B,Erik 2,2\n" .
            "\n" .
            "listas\n" .
            "provincia,cargo,lista,alianza\n" .
            "Córdoba,SENADORES,Lista A,Frente 1\n" .
            "Chubut,DIPUTADOS,Lista B,Frente 2\n" .
            "\n" .
            "mesas\n" .
            "id_mesa,provincia,circuito,establecimiento,electores\n" .
            "1001,CABA,101,Escuela 1,500\n" .
            "1002,CABA,102,Escuela 2,400\n" .
            "\n" .
            "telegramas\n" .
            "id_mesa,provincia,lista,votos_diputados,votos_senadores,blancos,nulos,recurridos\n" .
            "1001,CABA,Lista A,250,200,100,50,10\n" .
            "1002,CABA,Lista B,300,250,150,100,50";
        $file = UploadedFile::fake()->createWithContent('archivo.csv', $csvContent);

        $response = $this->post('/api/import', [
            'archivo' => $file,
            'type' => 'csv'
        ]);

        $response->assertStatus(200);

        $responseData = $response->json();
        
        // Verificar estructura básica
        $this->assertEquals('Archivo CSV importado', $responseData['mensaje']);
        $this->assertEmpty($responseData['errors']);
        $this->assertEquals(['candidatos', 'listas', 'mesas', 'telegramas'], $responseData['tablasLeidas']);
        
        // Verificar que registrosLeidos contiene los datos en el orden correcto
        $this->assertIsArray($responseData['registrosLeidos']);
        
        // Verificar algunos registros específicos
        // Candidatos (primeras dos filas después de headers)
        $this->assertEquals(['Buenos Aires', 'DIPUTADOS', 'Lista A', 'Erik', '1'], $responseData['registrosLeidos'][0]);
        $this->assertEquals(['Buenos Aires', 'SENADORES', 'Lista B', 'Erik 2', '2'], $responseData['registrosLeidos'][1]);
        
        // Listas (siguientes dos filas después de headers)
        $this->assertEquals(['Córdoba', 'SENADORES', 'Lista A', 'Frente 1'], $responseData['registrosLeidos'][2]);
        $this->assertEquals(['Chubut', 'DIPUTADOS', 'Lista B', 'Frente 2'], $responseData['registrosLeidos'][3]);
        
        // Mesas
        $this->assertEquals(['1001', 'CABA', '101', 'Escuela 1', '500'], $responseData['registrosLeidos'][4]);
        $this->assertEquals(['1002', 'CABA', '102', 'Escuela 2', '400'], $responseData['registrosLeidos'][5]);
        
        // Telegramas
        $this->assertEquals(['1001', 'CABA', 'Lista A', '250', '200', '100', '50', '10'], $responseData['registrosLeidos'][6]);
        $this->assertEquals(['1002', 'CABA', 'Lista B', '300', '250', '150', '100', '50'], $responseData['registrosLeidos'][7]);
        
        // Verificar que los datos fueron guardados en la base de datos
        $this->assertDatabaseHas('candidatos', [
            'provincia' => 'Buenos Aires',
            'cargo' => 'DIPUTADOS',
            'lista' => 'Lista A',
            'nombre' => 'Erik',
            'orden_en_lista' => 1
        ]);
        
        $this->assertDatabaseHas('listas', [
            'provincia' => 'Córdoba',
            'cargo' => 'SENADORES',
            'lista' => 'Lista A',
            'alianza' => 'Frente 1'
        ]);
        
        $this->assertDatabaseHas('mesas', [
            'id_mesa' => 1001,
            'provincia' => 'CABA',
            'circuito' => '101',
            'establecimiento' => 'Escuela 1',
            'electores' => 500
        ]);
        
        $this->assertDatabaseHas('telegramas', [
            'id_mesa' => 1001,
            'provincia' => 'CABA',
            'lista' => 'Lista A',
            'votos_diputados' => 250,
            'votos_senadores' => 200,
            'blancos' => 100,
            'nulos' => 50,
            'recurridos' => 10
        ]);

    }

    public function test_import_import_json(): void
    {
        // Falsa storage para no tocar disco real
        Storage::fake('local');

        $json = json_encode([
            "candidatos"=> [
                [
                    "provincia"=> "Buenos Aires",
                    "cargo"=> "DIPUTADOS",
                    "lista"=> "Lista A",
                    "nombre"=> "Erik",
                    "orden_en_lista"=> 1
                ],
                [
                    "provincia"=> "Buenos Aires",
                    "cargo"=> "SENADORES",
                    "lista"=> "Lista B",
                    "nombre"=> "Erik 2",
                    "orden_en_lista"=> 2
                ]
            ],
            "listas"=> [
                [
                    "provincia"=> "Buenos Aires",
                    "cargo"=> "DIPUTADOS",
                    "lista"=> "Lista A",
                    "alianza"=> "Frente 1"
                ],
                [
                    "provincia"=> "Buenos Aires",
                    "cargo"=> "SENADORES",
                    "lista"=> "Lista B",
                    "alianza"=> "Frente 2"
                ]
            ],
            "mesas"=> [
                [
                    "id_mesa"=> 1,
                    "provincia"=> "Buenos Aires",
                    "circuito"=> "Circuito 1",
                    "establecimiento"=> "Escuela 1",
                    "electores"=> 500
                ],
                [
                    "id_mesa"=> 2,
                    "provincia"=> "Buenos Aires",
                    "circuito"=> "Circuito 2",
                    "establecimiento"=> "Escuela 2",
                    "electores"=> 600
                ]
            ],
            "telegramas"=> [
                [
                    "id_mesa"=> 1,
                    "provincia"=> "Buenos Aires",
                    "lista"=> "Lista A",
                    "votos_diputados"=> 300,
                    "votos_senadores"=> 200,
                    "blancos"=> 10,
                    "nulos"=> 5,
                    "recurridos"=> 2
                ],
                [
                    "id_mesa"=> 2,
                    "provincia"=> "Buenos Aires",
                    "lista"=> "Lista B",
                    "votos_diputados"=> 400,
                    "votos_senadores"=> 250,
                    "blancos"=> 15,
                    "nulos"=> 3,
                    "recurridos"=> 1
                ]
            ]
        ]);

        $file = UploadedFile::fake()->createWithContent('archivo.json', $json);

        $response = $this->post('/api/import', [
            'archivo' => $file,
            'type' => 'json'
        ]);

        $response->assertStatus(200);
        
        $response->assertJsonPath('mensaje', 'Archivo JSON importado')
            ->assertJsonPath('errors', [])
            ->assertJsonPath('tablasLeidas', ['candidatos', 'listas', 'mesas', 'telegramas'])
            ->assertJsonStructure([
                'mensaje',
                'errors',
                'tablasLeidas',
                'registrosLeidos' => [
                    '*' => [
                        // Campos comunes
                        'provincia',
                        // Otros campos serán opcionales
                    ]
                ]
            ]);
            $this->assertDatabaseHas('candidatos', [
                'provincia' => 'Buenos Aires',
                'cargo' => 'DIPUTADOS',
                'lista' => 'Lista A',
                'nombre' => 'Erik',
                'orden_en_lista' => 1
            ]);
        
            $this->assertDatabaseHas('listas', [
                'provincia' => 'Buenos Aires',
                'cargo' => 'DIPUTADOS',
                'lista' => 'Lista A',
                'alianza' => 'Frente 1'
            ]);
            
            $this->assertDatabaseHas('mesas', [
                'id_mesa' => 1,
                'provincia' => 'Buenos Aires',
                'circuito' => 'Circuito 1',
                'establecimiento' => 'Escuela 1',
                'electores' => 500
            ]);
            
            $this->assertDatabaseHas('telegramas', [
                "id_mesa"=> 1,
                "provincia"=> "Buenos Aires",
                "lista"=> "Lista A",
                "votos_diputados"=> 300,
                "votos_senadores"=> 200,
                "blancos"=> 10,
                "nulos"=> 5,
                "recurridos"=> 2
            ]);
    }
}
