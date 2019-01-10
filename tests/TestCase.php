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

    /**
     * Call the given URI as a JWT-authenticated user.
     *
     * @param  \App\User  $user
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function jsonAs($user, $method, $uri, $data = [], $headers = [])
    {
        $token = auth()->tokenById($user->id);

        return $this->json($method, $uri, $data, array_merge($headers, [
            'Authorization' => 'Bearer ' . $token,
        ]));
    }
}
