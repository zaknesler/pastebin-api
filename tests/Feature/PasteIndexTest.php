<?php

namespace Tests\Feature;

use App\Paste;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasteIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function pastes_can_be_indexed()
    {
        factory(Paste::class, 3)->states('public')->create();

        $response = $this->json('GET', '/api/pastes');

        $response->assertJsonCount(3, 'data');
    }
}
