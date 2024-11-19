<?php

namespace App\Listeners;

use App\Events\PdfUploaded;
use App\Jobs\ConvertPdfToTxtJob;

class QueuePdfToTxtConversion
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PdfUploaded $event): void
    {
        ConvertPdfToTxtJob::dispatch($event->id);
    }
}
