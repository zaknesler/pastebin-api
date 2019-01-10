<?php

Route::prefix('/auth')->namespace('Auth')->group(function () {
    Route::post('/register', 'RegisterController@store');
    Route::post('/login', 'LoginController@store');
    Route::delete('/logout', 'LogoutController@destroy');

    Route::get('/me', 'MeController@view');
});

Route::apiResource('/pastes', 'PasteController');

// Route::get('/dropdown', function () {
//     return response()->json([
//         'languages' => config('pastebin.languages'),
//         'expirations' => config('pastebin.expiration_dates'),
//         'visibilities' => config('pastebin.visibilities'),
//     ]);
// });
