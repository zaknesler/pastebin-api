<?php

namespace Tests\Feature;

use App\User;
use App\Paste;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyPastesIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_must_be_authenticated_to_view_their_pastes()
    {
        $response = $this->json('GET', '/api/my-pastes');

        $response->assertStatus(401);
    }

    /** @test */
    function a_user_can_view_their_pastes()
    {
        $user = factory(User::class)->create();

        $paste = factory(Paste::class)->create([
            'user_id' => $user->id,
            'visibility' => 'private',
        ]);

        $response = $this->jsonAs($user, 'GET', '/api/my-pastes');

        $response->assertJsonFragment([
            'id' => $paste->id,
        ]);
    }

    /** @test */
    function only_the_users_pastes_are_listed()
    {
        $user = factory(User::class)->create();

        factory(Paste::class)->create();
        $paste = factory(Paste::class)->states('private')->create([
            'user_id' => $user->id,
        ]);

        $response = $this->jsonAs($user, 'GET', '/api/my-pastes');

        $response->assertJsonCount(1, 'data');
    }

    /** @test */
    function my_pastes_endpoint_does_not_index_expired_pastes()
    {
        $user = factory(User::class)->create();

        $paste = factory(Paste::class, 2)->states('expired')->create([
            'user_id' => $user->id,
        ]);

        $response = $this->jsonAs($user, 'GET', '/api/my-pastes');

        $response->assertJsonCount(0, 'data');
    }
}
