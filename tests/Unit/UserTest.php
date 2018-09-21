<?php

namespace Tests\Unit;

use App\User;
use App\Paste;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /** @test */
    function a_user_can_own_a_paste()
    {
        $user = factory(User::class)->make();
        $paste = factory(Paste::class)->make();

        $paste->user()->associate($user);

        $this->assertTrue($paste->user->is($user));
    }

    /** @test */
    function an_avatar_url_can_be_generated_by_the_users_email()
    {
        $user = factory(User::class)->make([
            'email' => 'example@example.com',
        ]);

        $this->assertEquals('https://www.gravatar.com/avatar/23463b99b62a72f26ed677cc556c44e8?s=100&d=mp', $user->getAvatar(100));
    }
}
