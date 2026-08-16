<?php

namespace App\Providers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerApiResponseMacros();
    }

    private function registerApiResponseMacros(): void
    {
        Response::macro('api', function (bool $success, int $status, ?string $message = null, $data = null, $paginate = null): JsonResponse {
            return response()->json([
                'success' => $success,
                'status' => $status,
                'message' => $message,
                'data' => $data ?: null,
                'paginate' => $paginate ? [
                    'per_page' => $data->perPage(),
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                ] : null,
            ], $status);
        });

        Response::macro('ok', function (?string $message = null, $data = null, bool $paginate = false): JsonResponse {
            return Response::api(true, 200, $message, $data, $paginate);
        });

        Response::macro('created', function (?string $message = null, $data = null): JsonResponse {
            return Response::api(true, 201, $message, $data);
        });

        Response::macro('noContent', function (): JsonResponse {
            return Response::api(true, 204);
        });

        Response::macro('error', function (?string $message = null, $data = null, int $status = 400): JsonResponse {
            return Response::api(false, $status, $message, $data);
        });

        Response::macro('unauthorized', function (): JsonResponse {
            return Response::api(false, 401, __('lang.unauthorized'));
        });

        Response::macro('unauthenticated', function (): JsonResponse {
            return Response::api(false, 401, __('lang.unauthenticated'));
        });

        Response::macro('forbidden', function (): JsonResponse {
            return Response::api(false, 403, __('lang.forbidden'));
        });

        Response::macro('notFound', function (): JsonResponse {
            return Response::api(false, 404, __('lang.not_found'));
        });

        Response::macro('internalError', function (): JsonResponse {
            return Response::api(false, 500, __('lang.server_error'));
        });

        Response::macro('methodNotAllowed', function (): JsonResponse {
            return Response::api(false, 405, __('lang.method_not_allowed'));
        });

        Response::macro('validationError', function ($validator): JsonResponse {
            return Response::api(false, 422, $validator->errors()->first());
        });
    }
}
