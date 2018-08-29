<?php

Route::get('/pastes', 'PasteController@index');
Route::post('/pastes', 'PasteController@store');
Route::get('/pastes/{paste}', 'PasteController@show');
