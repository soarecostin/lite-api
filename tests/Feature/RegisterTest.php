<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider validationDataProvider
     */
    public function testValidation(array $invalidData, string $invalidParameter)
    {
        $validData = [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $data = array_merge($validData, $invalidData);

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([$invalidParameter]);
    }

    public function validationDataProvider()
    {
        return [
            [['name' => null], 'name'],
            [['name' => ''], 'name'],
            [['name' => []], 'name'],

            [['email' => null], 'email'],
            [['email' => ''], 'email'],
            [['email' => []], 'email'],
            [['email' => 'abc'], 'email'],

            [['password' => null], 'password'],
            [['password' => ''], 'password'],
            [['password' => []], 'password'],
            [['password' => 'abc'], 'password'],

            [['password_confirmation' => null], 'password'],
            [['password_confirmation' => ''], 'password'],
            [['password_confirmation' => []], 'password'],
            [['password_confirmation' => 'abc'], 'password'],
        ];
    }

    public function testPasswordConfirmationValidation()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'another-password'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function testSuccessfulRegistration()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertExactJson(['success'=>true]);
    }
}
