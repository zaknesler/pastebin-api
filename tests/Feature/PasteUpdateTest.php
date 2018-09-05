<?php

namespace Tests\Feature;

use App\User;
use App\Paste;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasteUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function updating_a_paste_returns_a_successful_status_code()
    {
        $user = $this->authenticate();
        $paste = factory(Paste::class)->states('public')->create();
        $paste->user()->associate($user)->save();

        $response = $this->json('PATCH', "/api/pastes/$paste->slug", [
            'name' => 'This is an updated name',
        ]);

        $response->assertSuccessful();
    }

    /** @test */
    function a_paste_can_be_updated()
    {
        $user = $this->authenticate();
        $paste = factory(Paste::class)->states('public')->create();
        $paste->user()->associate($user)->save();

        $response = $this->json('PATCH', "/api/pastes/$paste->slug", [
            'name' => 'This is an updated name',
            'body' => 'This is an updated body',
            'visibility' => 'unlisted',
        ]);

        $this->assertEquals('This is an updated name', $paste->fresh()->name);
        $this->assertEquals('This is an updated body', $paste->fresh()->body);
        $this->assertEquals('unlisted', $paste->fresh()->visibility);
    }

    /** @test */
    function must_be_authenticated_to_update_a_paste()
    {
        $paste = factory(Paste::class)->create();

        $response = $this->json('PATCH', "/api/pastes/$paste->slug", [
            'name' => 'This is an updated name',
        ]);

        $response->assertStatus(401);
        $response->assertJsonFragment([
            'message' => 'You must be authenticated to perform this action.',
            'status' => 401,
        ]);
    }

    /** @test */
    function must_own_paste_to_update_it()
    {
        $this->authenticate();

        $paste = factory(Paste::class)->states('public')->create();
        $paste->user()->associate(factory(User::class)->create())->save();

        $response = $this->json('PATCH', "/api/pastes/$paste->slug", [
            'name' => 'This is an updated name',
        ]);

        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => 'You do not have access to this paste.',
            'status' => 403,
        ]);

        $this->assertNotEquals('This is an updated name', $paste->fresh()->name);
    }
}
