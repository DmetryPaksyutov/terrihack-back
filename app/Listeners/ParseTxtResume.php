<?php

namespace App\Listeners;

use App\Events\PdfToTextConverted;
use App\Jobs\ParseFullNameAndAgeFromTxtJob;

class ParseTxtResume
{
    public function handle(PdfToTextConverted $event): void
    {
        ParseFullNameAndAgeFromTxtJob::dispatch($event->resumeId);
    }
}
