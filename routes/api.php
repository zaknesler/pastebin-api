<?php

Route::apiResource('/pastes', 'PasteController');

Route::get('supported-languages', function () {
    return apiResponse([
        'languages' => Cache::rememberForever('pastebin.languages', function () {
            return config('pastebin.languages');
        })
    ], 200);
});
