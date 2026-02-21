<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class BaseApiController extends Controller
{
    use AuthorizesRequests;

    protected function success(mixed $data = null, string $message = '操作成功', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function error(string $message = '操作失败', int $code = 422, mixed $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    protected function paginate($collection, $perPage = 15): array
    {
        $page = request('page', 1);
        $perPage = request('per_page', $perPage);

        $items = $collection->forPage($page, $perPage)->values();
        $total = $collection->count();

        return [
            'data' => $items,
            'meta' => [
                'current_page' => (int) $page,
                'per_page' => (int) $perPage,
                'total' => $total,
                'last_page' => (int) \ceil($total / $perPage),
            ],
        ];
    }
}
