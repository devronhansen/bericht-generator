<?php

namespace App\Listeners;

use App\Events\UserAwaitsDocumentZip;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Classes\ZipCreator;

class CreateZip
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
        $zipCreator = new ZipCreator();
        $zipCreator->createZipFromDocuments(env("FOLDER_NAME"));
    }
}
