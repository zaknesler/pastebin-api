<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Sign into the application as a user.
     *
     * @param  \App\User $user
     * @param  array  $overrides
     * @return \App\User
     */
    protected function authenticate($user = null, $overrides = [])
    {
        $this->be($user ?: $user = factory(User::class)->create($overrides));

        return $user;
    }
}
