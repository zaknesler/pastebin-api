<?php

namespace Tests\Unit\Resources;

use App\User;
use App\Paste;
use Tests\TestCase;
use App\Http\Resources\PasteResource;
use App\Http\Resources\PublicUserResource;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasteResourceTest extends TestCase
{
    /** @test */
    function a_paste_response_can_be_resolved()
    {
        $paste = factory(Paste::class)->make([
            'name' => 'Example Paste',
            'slug' => 'example-paste',
            'body' => 'This is the body of the paste.',
            'visibility' => 'public',
        ])->user()->associate(factory(User::class)->make());

        $pasteResource = (new PasteResource($paste))->resolve();

        $this->assertTrue($pasteResource['user'] instanceof PublicUserResource);
        $this->assertArraySubset([
            'name' => 'Example Paste',
            'slug' => 'example-paste',
            'body' => 'This is the body of the paste.',
            'visibility' => 'public',
        ], $pasteResource);
    }
}
