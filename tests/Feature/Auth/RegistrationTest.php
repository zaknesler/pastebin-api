<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_registration_requires_an_email()
    {
        $response = $this->json('POST', '/api/auth/register');

        $response->assertJsonStructure([
            'data' => [
                'errors' => [
                    'email',
                ],
            ],
        ]);
    }

    /** @test */
    function user_registration_requires_a_valid_email()
    {
        $response = $this->json('POST', '/api/auth/register', [
            'email' => 'invalid',
        ]);

        $response->assertJsonStructure([
            'data' => [
                'errors' => [
                    'email',
                ],
            ],
        ]);
    }

    /** @test */
    function user_registration_requires_a_unique_email()
    {
        $user = factory(User::class)->create(['email' => 'taken@example.com']);

        $response = $this->json('POST', '/api/auth/register', [
            'email' => 'taken@example.com',
        ]);

        $response->assertJsonStructure([
            'data' => [
                'errors' => [
                    'email',
                ],
            ],
        ]);
    }

    /** @test */
    function user_registration_requires_a_username()
    {
        $response = $this->json('POST', '/api/auth/register');

        $response->assertJsonStructure([
            'data' => [
                'errors' => [
                    'username',
                ],
            ],
        ]);
    }

    /** @test */
    function user_registration_requires_a_password()
    {
        $response = $this->json('POST', '/api/auth/register');

        $response->assertJsonStructure([
            'data' => [
                'errors' => [
                    'password',
                ],
            ],
        ]);
    }

    /** @test */
    function a_user_can_be_registered()
    {
        $this->json('POST', '/api/auth/register', [
            'username' => 'zaknesler',
            'email' => 'zak@example.com',
            'password' => 'secret',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'zak@example.com',
            'username' => 'zaknesler',
        ]);
    }

    /** @test */
    function a_user_resource_is_returned_upon_registration()
    {
        $response = $this->json('POST', '/api/auth/register', [
            'username' => 'zaknesler',
            'email' => 'zak@example.com',
            'password' => 'secret',
        ]);

        $response->assertJsonFragment([
            'id' => 1,
            'username' => 'zaknesler',
            'email' => 'zak@example.com',
        ]);
    }
}
