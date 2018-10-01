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
        factory(Paste::class, 3)->create();

        $response = $this->json('GET', '/api/pastes');

        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    function only_public_pastes_are_indexed()
    {
        factory(Paste::class)->states('public')->create();
        factory(Paste::class)->states('unlisted')->create();
        factory(Paste::class)->states('private')->create();

        $response = $this->json('GET', '/api/pastes');

        $response->assertJsonCount(1, 'data');
    }

    /** @test */
    function expired_pastes_are_not_indexed()
    {
        factory(Paste::class)->create();
        factory(Paste::class)->create([
            'expires_at' => now()->subDays(1),
        ]);

        $response = $this->json('GET', '/api/pastes');

        $response->assertJsonCount(1, 'data');
    }

    /** @test */
    function pastes_are_paginated()
    {
        factory(Paste::class)->create();

        $response = $this->json('GET', '/api/pastes');

        $response->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);
    }
}
