<?php

namespace Tests\Feature;

use Database\Seeders\Profiles;
use Database\Seeders\InitialUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_admin_can_done_login()
    {

        $this->seed(Profiles::class);
        $this->seed(InitialUser::class);

        $response = $this->post('/api/login',[
            'email' => env("DEFAULT_EMAIL"),
            'password' => env("DEFAULT_PASSWORD"),
        ]);

        $response->assertStatus(201);


        /*
        $response = $this->get('/');

        $response->assertStatus(200);
        */
    }
}
