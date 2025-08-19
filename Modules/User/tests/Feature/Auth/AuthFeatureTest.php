<?php

namespace Modules\User\Tests\Feature\Auth;

use Tests\TestCase;
use Modules\User\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;
use Modules\User\Database\Factories\UserFactory;

class AuthFeatureTest extends TestCase
{
    public function test_register_user()
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => ['user', 'token']
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }

    public function test_login_user()
    {
        $user = UserFactory::new()->create([
            'password' => Hash::make('password')
        ]);

        $payload = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->postJson('/api/auth/login', $payload);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => ['user', 'token']
                 ]);
    }

    public function test_authenticated_user_can_access_me()
    {
        $user = Userfactory::new()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/user');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'data' => [
                         'id' => $user->id,
                         'email' => $user->email,
                         'name' => $user->name
                     ]
                 ]);
    }

    public function test_guest_cannot_access_me()
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Unauthenticated.',
                 ]);
    }

    public function test_logout_user()
    {
        $user = Userfactory::new()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Logged out successfully',
                 ]);
    }
}
