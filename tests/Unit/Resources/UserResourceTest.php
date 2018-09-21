<?php

namespace Tests\Unit\Resources;

use App\User;
use Tests\TestCase;
use App\Http\Resources\UserResource;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserResourceTest extends TestCase
{
    /** @test */
    function a_user_response_can_be_resolved()
    {
        $user = factory(User::class)->make([
            'name' => 'Example User',
            'email' => 'user@example.com',
        ]);

        $userResource = new UserResource($user);

        $this->assertArraySubset([
            'name' => 'Example User',
            'email' => 'user@example.com',
            'avatar' => 'https://www.gravatar.com/avatar/b58996c504c5638798eb6b511e6f49af?s=50&d=mp',
        ], $userResource->resolve());
    }
}
