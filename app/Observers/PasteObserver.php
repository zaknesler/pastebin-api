<?php

namespace App\Observers;

use App\Paste;

class PasteObserver
{
    /**
     * Handle the paste "created" event.
     *
     * @param  \App\Paste  $paste
     * @return void
     */
    public function created(Paste $paste)
    {
        $paste->update([
            'slug' => str_random(8),
        ]);
    }
}
