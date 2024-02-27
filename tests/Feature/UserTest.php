<?php

namespace Tests\Feature;

use App\Models\User;
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

        //Verificar se o status code esta como esperado
        $response->assertStatus(201);

        $response->assertJson([
            "message" => "Autorizado",
            "status"=> 201,
            "data"=> [
                 "token"=> true,
                 "permissions"=> true,
            ]
        ]);      
    }

    //testo permisos de usuarios
    public function test_user_admin_permissions_load_correct()
    {

        $this->seed(Profiles::class);
        $this->seed(InitialUser::class);

        $response = $this->post('/api/login',[
            'email' => env("DEFAULT_EMAIL"),
            'password' => env("DEFAULT_PASSWORD"),
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            "data"=> [
                "permissions"=>[
                'create-races',
                'get-races',
                'create-species',
                'get-species',
                'delete-species',
                'create-pets',
                'get-pets',
                'delete-pets',
                'create-profissionals',
                'get-profissionals']
            ],
        ]);      
    }

     //testo permisos de veterinario
     public function test_user_veterinary_permissions_load_correct()
     {
 
         $this->seed(Profiles::class);
         $this->seed(InitialUser::class);

        $user = User::factory()->create(['profile_id' => 2 , 'password'=> '12345678']);
 
         $response = $this->post('/api/login',[
             'email' => $user->email,
             'password' => '12345678'
         ]);
 
         $response->assertStatus(201);
 
         $response->assertJson([
             "data"=> [
                 "permissions"=>[
                    'create-races',
                    'get-races',
                    'create-species',
                    'get-species',
                    'delete-species',
                    'create-pets',
                    'get-pets',
                    'delete-pets',
                    'create-vaccines'
                ]
             ],
         ]);      
     }

      //testo logout
    /*  public function test_make_logout_in_application()
      {
  
          $this->seed(Profiles::class);
          $this->seed(InitialUser::class);
 
         $user = User::factory()->create(['profile_id' => 1]);
  
          $response = $this->actingAs($user)->post('/api/logout');
  
          $response->assertStatus(204);            
           
      }*/
}