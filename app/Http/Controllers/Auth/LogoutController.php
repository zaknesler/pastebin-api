<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\PrivateUserResource;

class LogoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Unauthenticate the user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        auth()->logout();

        return apiResponse('You have been signed out.', 200);
    }
}
