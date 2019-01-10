<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_authentication_requires_an_email()
    {
        $response = $this->json('POST', '/api/auth/login');

        $response->assertJsonStructure([
            'data' => [
                'errors' => [
                    'email',
                ],
            ],
        ]);
    }

    /** @test */
    function user_authentication_requires_a_password()
    {
        $response = $this->json('POST', '/api/auth/login');

        $response->assertJsonStructure([
            'data' => [
                'errors' => [
                    'password',
                ],
            ],
        ]);
    }

    /** @test */
    function user_authentication_requires_the_correct_password()
    {
        $user = factory(User::class)->create([
            'email' => 'zak@example.com',
            'password' => 'secret',
        ]);

        $response = $this->json('POST', '/api/auth/login', [
            'email' => 'zak@example.com',
            'password' => 'incorrect',
        ]);

        $response->assertJsonFragment([
            'message' => 'Could not sign you in with those credentials',
        ]);
    }

    /** @test */
    function a_user_can_be_authenticated()
    {
        $user = factory(User::class)->create([
            'username' => 'zaknesler',
            'email' => 'zak@example.com',
            'password' => 'secret',
        ]);

        $response = $this->json('POST', '/api/auth/login', [
            'email' => 'zak@example.com',
            'password' => 'secret',
        ]);

        $response->assertJsonFragment([
            'id' => 1,
            'username' => 'zaknesler',
            'email' => 'zak@example.com',
        ]);

        $response->assertJsonStructure([
            'meta' => [
                'token',
            ],
        ]);
    }
}
