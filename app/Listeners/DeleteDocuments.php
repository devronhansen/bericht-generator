<?php

namespace App\Listeners;

use App\Events\UserAwaitsDocumentZip;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteDocuments
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserAwaitsDocumentZip  $event
     * @return void
     */
    public function handle(UserAwaitsDocumentZip $event)
    {
        //
    }
}
