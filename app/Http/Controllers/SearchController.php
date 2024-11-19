<?php

namespace App\Http\Controllers;

use App\Http\Requests\AiSearchRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\EmployeeResumeResource;
use App\Services\AI\AiService;
use App\Services\Resume\ResumeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function search(SearchRequest $request, ResumeService $service): AnonymousResourceCollection
    {
        $items = $service->searchByEmployeeResume($request->getDto());
        return EmployeeResumeResource::collection($items);
    }

    public function aiSearch(AiSearchRequest $request, AiService $service): JsonResponse
    {
        $answer = $service->getQueryParamsByUserQueryString(
            $request->input('q'),
            SearchRequest::RULES,
        );

        if (!$answer) {
            return response()->json(
                [
                    'message' => 'Retry later',
                ],
                Response::HTTP_BAD_GATEWAY,
            );
        }

        return response()->json([
            'data' => $answer,
        ]);
    }
}
