<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unccessful_registration()
    {
        $this->json('POST', 'api/register', ['Accept' => 'application/json'])
            ->assertStatus(422);
    }

    public function test_successful_registration()
    {
        $userData = [
            "name" => 'test',
            "email" => 'test@example.com',
            "password" => "123456",
            "password_confirmation" => "123456"
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "user" => ['id', 'name', 'email', 'created_at', 'updated_at'],
                "token"
            ]);
    }

    public function test_unccessful_login()
    {
        $this->json('POST', 'api/login', ['Accept' => 'application/json'])
            ->assertStatus(422);
    }

    public function test_successful_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('secret'),
        ]);

        $response = $this->post('/api/login', [
            'email' => 'test@example.com',
            'password' => 'secret',
        ]);

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_logout()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
     
        $response = $this->post('/api/logout');
     
        $response->assertStatus(200);
    }

    public function test_get_user_details()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
     
        $response = $this->get('/api/user');
     
        $response->assertStatus(200);
    }

    public function test_update_user()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
     
        $response = $this->put('/api/user', [
            'email' => 'test@example.com',
            'name' => 'test',
            'password' => 'secret',
        ]);
     
        $response->assertStatus(200);
    }
}