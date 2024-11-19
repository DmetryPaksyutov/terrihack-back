<?php

namespace App\Http\Controllers;

use App\Events\PdfUploaded;
use App\Exceptions\FileNotFoundException;
use App\Http\Requests\DownloadPdfRequest;
use App\Http\Requests\ResumeListRequest;
use App\Http\Requests\UploadFilesRequest;
use App\Http\Resources\ResumeResource;
use App\Services\FileService\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    public function __construct(
        private readonly FileService $fileService,
    ) {
    }

    public function uploadFiles(UploadFilesRequest $request): JsonResponse
    {
        $uploadedFilesInfo = collect($request->allFiles()['pdf_files'] ?? [])
            ->map(function ($pdfFile) {
                $path = $pdfFile->store('pdf_files', 'public');

                return $path ? [
                    'path' => "app/public/$path",
                    'fileName' => $pdfFile->getClientOriginalName(),
                ] : false;
            })
            ->filter();

        $ids = $this->fileService->insert($uploadedFilesInfo);

        if (!is_null($ids)) {
            $ids->each(fn($id) => PdfUploaded::dispatch($id));
        }

        return new JsonResponse([
            'message' => 'Файлы успешно загружены',
            'data' => []
        ], 200);
    }

    public function list(ResumeListRequest $request): AnonymousResourceCollection
    {
        $files = $this->fileService->getList($request->getPerPage(), $request->getSortOrder());

        return ResumeResource::collection($files);
    }

    /**
     * @throws \Throwable
     */
    public function downloadPdf(DownloadPdfRequest $request): BinaryFileResponse
    {
        $file = $this->fileService->getById($request->getId());

        throw_if(is_null($file), new FileNotFoundException());

        return response()->download(storage_path($file->pdf_path), $file->name);
    }
}
