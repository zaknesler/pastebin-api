<?php

namespace Tests\Feature;

use App\User;
use App\Paste;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasteShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_public_paste_can_be_viewed()
    {
        $paste = factory(Paste::class)->states('public')->create();

        $response = $this->json('GET', "/api/pastes/$paste->slug");

        $response->assertJsonStructure([
            'data' => [
                'name',
                'slug',
                'body',
                'visibility',
                'user',
            ],
        ]);
    }

    /** @test */
    function viewing_a_paste_returns_a_successful_status_code()
    {
        $paste = factory(Paste::class)->states('public')->create();

        $response = $this->json('GET', "/api/pastes/$paste->slug");

        $response->assertSuccessful();
    }

    /** @test */
    function an_unlisted_paste_can_be_viewed()
    {
        $paste = factory(Paste::class)->states('unlisted')->create();

        $response = $this->json('GET', "/api/pastes/$paste->slug");

        $response->assertJsonStructure([
            'data' => [
                'name',
                'slug',
                'body',
                'visibility',
                'user',
            ],
        ]);
    }

    /** @test */
    function viewing_an_expired_paste_fails()
    {
        $paste = factory(Paste::class)->states('public')->create([
            'expires_at' => now()->subDays(1),
        ]);

        $response = $this->json('GET', "/api/pastes/$paste->slug");

        $response->assertStatus(410);
        $response->assertJsonFragment([
            'message' => 'This paste has expired.',
            'status' => 410,
        ]);
    }

    /** @test */
    function viewing_a_private_paste_fails()
    {
        $paste = factory(Paste::class)->states('private')->create();
        $paste->user()->associate(factory(User::class)->create())->save();

        $response = $this->json('GET', "/api/pastes/$paste->slug");

        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => 'You do not have access to this paste.',
            'status' => 403,
        ]);
    }

    /** @test */
    function a_user_can_see_their_own_private_paste()
    {
        $this->actingAs($user = factory(User::class)->create());

        $paste = factory(Paste::class)->states('private')->create();
        $paste->user()->associate($user)->save();

        $response = $this->json('GET', "/api/pastes/$paste->slug");

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                'name',
                'slug',
                'body',
                'visibility',
                'user',
            ],
        ]);
    }
}
