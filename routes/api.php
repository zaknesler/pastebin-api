<?php

Route::post('/pastes', 'PasteController@store');
Route::get('/pastes/{paste}', 'PasteController@show');
