<?php

namespace App\Jobs;

use App\Events\PdfToTextConverted;
use App\Models\Resume;
use App\Repositories\FileRepository;
use App\Services\FileService\DTO\UpdateFileDTO;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;

class ConvertPdfToTxtJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $id,
    ) {
        $this->queue = 'default';
    }

    /**
     * Execute the job.
     */
    public function handle(FileRepository $fileRepository): void
    {
        try {
            $file = $fileRepository->getById($this->id);
            $text = Pdf::getText(storage_path($file->pdf_path));

            $txtFilePath = str_replace('app/public/pdf_files', 'txt_files', $file->pdf_path);
            $txtFilePath = preg_replace('/\.pdf$/', '.txt', $txtFilePath);

            Storage::disk('public')->put($txtFilePath, $text);

            $fileRepository->updateById(
                $this->id,
                new UpdateFileDTO(
                    txtPath: "app/public/$txtFilePath",
                    status: Resume::STATUS_IN_TEXT,
                ));

            PdfToTextConverted::dispatch($this->id);
        } catch (\Exception $e) {
            Log::error(self::class . ': ' . $e->getMessage());
            $fileRepository->updateById(
                $this->id,
                new UpdateFileDTO(
                    status: Resume::STATUS_ERROR,
                    statusText: "Ошибка при получении текста из файла",
                ));
        }
    }
}
