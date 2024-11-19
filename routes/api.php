<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/resumes/upload/files', [FileController::class, 'uploadFiles']);
Route::get('/resumes/list', [FileController::class, 'list']);
Route::get('/resumes/{id}/download', [FileController::class, 'downloadPdf']);

Route::get('/resumes/search', [SearchController::class, 'search']);

Route::get('/ai/resumes/search', [SearchController::class, 'aiSearch']);
