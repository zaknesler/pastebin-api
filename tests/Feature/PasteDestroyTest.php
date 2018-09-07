<?php

namespace Tests\Feature;

use App\Paste;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasteDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_paste_can_be_deleted()
    {
        $paste = factory(Paste::class)->create();
        $paste->user()->associate($this->authenticate())->save();

        $response = $this->json('DELETE', "/api/pastes/$paste->slug");

        $this->assertEquals(0, Paste::count());
        $response->assertStatus(202);
        $response->assertJsonFragment([
            'status' => 202,
            'message' => 'Paste has been deleted.',
        ]);
    }

    /** @test */
    function must_be_signed_in_to_delete_paste()
    {
        $paste = factory(Paste::class)->create();

        $response = $this->json('DELETE', "/api/pastes/$paste->slug");

        $this->assertEquals(1, Paste::count());
        $response->assertStatus(401);
        $response->assertJsonFragment([
            'status' => 401,
            'message' => 'You must be authenticated to perform this action.',
        ]);
    }

    /** @test */
    function must_own_paste_to_delete_it()
    {
        $this->authenticate();
        $paste = factory(Paste::class)->create();

        $response = $this->json('DELETE', "/api/pastes/$paste->slug");

        $this->assertEquals(1, Paste::count());
        $response->assertStatus(403);
        $response->assertJsonFragment([
            'status' => 403,
            'message' => 'You do not have access to this paste.',
        ]);
    }
}
