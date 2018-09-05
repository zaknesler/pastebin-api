<?php

namespace Tests\Feature;

use App\User;
use App\Paste;
use Tests\TestCase;
use App\Http\Resources\PasteResource;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasteCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_paste_can_be_created_by_a_guest()
    {
        $response = $this->json('POST', '/api/pastes', [
            'name' => 'this is an example paste',
            'body' => 'this is the body of the paste',
            'visibility' => 'public',
        ]);

        $response->assertJsonFragment([
            'user' => null,
        ]);
    }

    /** @test */
    function a_paste_can_have_a_language()
    {
        $response = $this->json('POST', '/api/pastes', [
            'name' => 'this is an example paste',
            'body' => 'this is the body of the paste',
            'visibility' => 'public',
            'language' => 'html',
        ]);

        $response->assertJsonFragment([
            'language' => 'html',
        ]);
    }

    /** @test */
    function creating_a_paste_returns_a_successful_status_code()
    {
        $response = $this->json('POST', '/api/pastes', [
            'name' => 'this is an example paste',
            'body' => 'this is the body of the paste',
            'visibility' => 'public',
        ]);

        $response->assertSuccessful();
    }

    /** @test */
    function a_paste_can_be_created_by_a_user()
    {
        $this->actingAs(factory(User::class)->create([
            'name' => 'Example',
            'email' => 'example@example.com'
        ]));

        $response = $this->json('POST', '/api/pastes', [
            'name' => 'this is an example paste',
            'body' => 'this is the body of the paste',
            'visibility' => 'public',
        ]);

        $response->assertJsonFragment([
            'user' => [
                'name' => 'Example',
                'email' => 'example@example.com',
            ],
        ]);
    }

    /** @test */
    function a_paste_can_be_unlisted()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->json('POST', '/api/pastes', [
            'name' => 'this is an example paste',
            'body' => 'this is the body of the paste',
            'visibility' => 'unlisted',
        ]);

        $response->assertJsonFragment([
            'visibility' => 'unlisted',
        ]);
    }

    /** @test */
    function a_paste_can_be_private()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->json('POST', '/api/pastes', [
            'name' => 'this is an example paste',
            'body' => 'this is the body of the paste',
            'visibility' => 'private',
        ]);

        $response->assertJsonFragment([
            'visibility' => 'private',
        ]);
    }

    /** @test */
    function a_pastes_body_can_be_large()
    {
        $response = $this->json('POST', '/api/pastes', [
            'name' => 'this is an example paste',
            'body' => file_get_contents(base_path('tests/stubs/under-limit.txt')),
            'visibility' => 'public',
        ]);

        $response->assertSuccessful();
    }

    /** @test */
    function a_paste_can_have_an_expiration_date()
    {
        $response = $this->json('POST', '/api/pastes', [
            'name' => 'this is an example paste',
            'body' => 'this is the body of the paste',
            'visibility' => 'public',
            'expires_at' => $date = now()->addDays(1)->toDateTimeString(),
        ]);

        $this->assertEquals($date, Paste::first()->expires_at->toDateTimeString());
    }

    /** @test */
    function must_be_authenticated_to_create_a_private_paste()
    {
        $response = $this->json('POST', '/api/pastes', [
            'name' => 'this is an example paste',
            'body' => 'this is the body of the paste',
            'visibility' => 'private',
        ]);

        $this->assertEquals(0, Paste::count());
        $response->assertStatus(401);
        $response->assertJsonFragment([
            'message' => 'You must be authenticated to perform this action.',
            'status' => 401,
        ]);
    }

    /** @test */
    function paste_is_not_created_when_validation_fails()
    {
        $response = $this->json('POST', '/api/pastes');

        $this->assertEquals(0, Paste::count());
    }

    /** @test */
    function a_paste_must_have_a_name()
    {
        $response = $this->json('POST', '/api/pastes', [
            'body' => 'this is the body of the paste',
            'visibility' => 'public',
        ]);

        $response->assertJsonStructure([
            'errors' => [
                'name',
            ],
        ]);
    }

    /** @test */
    function a_paste_must_have_a_body()
    {
        $response = $this->json('POST', '/api/pastes', [
            'name' => 'this is an example paste',
            'visibility' => 'public',
        ]);

        $response->assertJsonStructure([
            'errors' => [
                'body',
            ],
        ]);
    }

    /** @test */
    function a_paste_must_have_a_visibility()
    {
        $response = $this->json('POST', '/api/pastes', [
            'name' => 'this is an example paste',
            'body' => 'this is the body of the paste',
        ]);

        $response->assertJsonStructure([
            'errors' => [
                'visibility',
            ],
        ]);
    }

    /** @test */
    function a_pastes_body_must_not_exceed_max_length()
    {
        $response = $this->json('POST', '/api/pastes', [
            'name' => 'this is an example paste',
            'body' => file_get_contents(base_path('tests/stubs/over-limit.txt')),
            'visibility' => 'public',
        ]);

        $response->assertJsonStructure([
            'errors' => [
                'body',
            ],
        ]);
    }

    /** @test */
    function a_paste_language_must_be_valid()
    {
        $response = $this->json('POST', '/api/pastes', [
            'name' => 'this is an example paste',
            'body' => 'this is the body of the paste',
            'visibility' => 'public',
            'language' => 'invalid',
        ]);

        $response->assertJsonStructure([
            'errors' => [
                'language',
            ],
        ]);
    }

    /** @test */
    function a_paste_expiration_date_must_be_in_the_future()
    {
        $response = $this->json('POST', '/api/pastes', [
            'name' => 'this is an example paste',
            'body' => 'this is the body of the paste',
            'visibility' => 'public',
            'expires_at' => now()->subDays(1),
        ]);

        $response->assertJsonStructure([
            'errors' => [
                'expires_at',
            ],
        ]);
    }

    /** @test */
    function a_paste_visibility_must_be_valid()
    {
        $response = $this->json('POST', '/api/pastes', [
            'name' => 'this is an example paste',
            'body' => 'this is the body of the paste',
            'visibility' => 'invalid',
        ]);

        $response->assertJsonStructure([
            'errors' => [
                'visibility',
            ],
        ]);
    }
}
