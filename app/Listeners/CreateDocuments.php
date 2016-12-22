<?php

namespace App\Listeners;

use App\Events\UserAwaitsDocumentZip;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Classes\DocumentFactory;

class CreateDocuments
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  UserAwaitsDocumentZip $event
     * @return void
     */
    public function handle(UserAwaitsDocumentZip $event)
    {
        $documentFactory = new DocumentFactory();
        $documentFactory->createDocumentsWith($event->getDemands());
    }
}
