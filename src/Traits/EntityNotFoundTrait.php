<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait EntityNotFoundTrait
{
    public function response404Exception( int $id, string $path): JsonResponse
    {
        return new JsonResponse(json_encode([
            "error" =>
                ["code" => 404,
                    "message" => "Resource not found",
                    "details" => "The requested resource with ID '$id' could not be found.",
                ]],JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        ,Response::HTTP_NOT_FOUND, [], true);
    }
}