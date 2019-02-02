<?php

Route::prefix('/auth')->namespace('Auth')->group(function () {
    Route::post('/register', 'RegisterController@store');
    Route::post('/login', 'LoginController@store');
    Route::delete('/logout', 'LogoutController@destroy');

    Route::get('/me', 'MeController@view');
});

Route::apiResource('/pastes', 'PasteController');

Route::get('/my-pastes', 'MyPasteController@index');
