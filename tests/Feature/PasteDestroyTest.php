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
        $this->withoutExceptionHandling();

        $paste = factory(Paste::class)->create();
        $paste->user()->associate($this->authenticate())->save();

        $response = $this->json('DELETE', "/api/pastes/$paste->slug");

        // dd($response->json());

        $this->assertEquals(0, Paste::count());
        // $response->assertJsonFragment([
        //     'message' => 'Paste has been deleted.',
        // ]);
    }
}
