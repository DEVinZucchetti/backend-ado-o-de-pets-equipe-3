<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\Race;
use App\Models\Specie;
use App\Models\User;
use Database\Seeders\Profiles;
use Database\Seeders\InitialUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdoptionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_add_new_adoption(): void
    {

        $specie = Specie::factory()->create();
        $race = Race::factory()->create();
        $pet  = Pet::factory()->create(['race_id' => $race->id, 'specie_id' => $specie->id]);

        Pet::factory(20)->create(['race_id' => $race->id, 'specie_id' => $specie->id]);

        $body = [
            'name' => 'Eliana',
            'contact' => '85 88181-1111',
            'email' => 'em@gmail.com',
            'cpf' => '12471899352',
            'observations' => 'Quero um gato pra pegar rato',
            'pet_id' => $pet->id
        ];

        $response = $this->post('/api/pets/adocao', $body);

        $this->assertDatabaseCount('adoptions', 1);

        $response->assertStatus(201);
        $response->assertJson([
            ...$body,
            'status' => 'PENDENTE'
        ]);
    }
}
