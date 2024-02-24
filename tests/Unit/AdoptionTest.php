<?php

namespace Tests\Feature;

use App\Models\User;
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

        'name' => 'string|required|max:255',
        'contact' => 'string|required|max:20',
        'email' => 'string|required',
        'cpf' => 'string|required',
        'observations' => 'string|required',
        'pet_id' => 'integer|required',

        $body = [
            'name' => "Guilherme",
            'contact' => "47999017307",
            'email' => "email@hotmail.com",
            'cpf' => "10512345654",
            'observations' => "Uma observação",
            'pet_id' =>

        ]


        $response = $this->post('/api/adoptions', $body)

        $response->assertStatus(200);
    }
}
