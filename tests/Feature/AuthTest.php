<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testUnauthorized()
    {
        $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ])->assertStatus(401);

        $this->assertGuest();
    }

    public function testGuestsCannotAccessProtectedEndpoints()
    {
        $this->postJson('/api/fields')->assertStatus(401);
    }

    public function testSuccessfulLogin()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('password')
        ]);

        $this->postJson('/api/auth/login', ['email' => $user->email, 'password' => 'password'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in'
            ]);

        $this->assertAuthenticatedAs($user, 'api');
    }

    public function testAuthenticatedUsersCanAccessProtectedEndpoints()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('password')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $token = $response->json('access_token');

        $this->getJson('/api/auth/me', ['Authorization' => "Bearer $token"])
            ->assertStatus(200)
            ->assertExactJson([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    public function testLogout()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $this->postJson('/api/auth/logout', [], ['Authorization' => "Bearer $token"])
            ->assertStatus(200)
            ->assertJsonStructure(['message']);

        $this->assertGuest('api');
    }

    public function testRefreshToken()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $this->postJson('/api/auth/refresh', [], ['Authorization' => "Bearer $token"])
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in'
            ]);
    }
}
