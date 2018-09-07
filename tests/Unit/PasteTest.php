<?php

namespace Tests\Unit;

use App\Paste;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_slug_is_set_when_a_post_is_created()
    {
        $paste = Paste::create([
            'name' => 'Example paste',
            'body' => 'This is the body of the example paste',
            'visibility' => 'public',
        ]);

        $this->assertNotNull($paste->slug);
    }
}
