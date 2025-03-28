<?php

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ServiceException extends HttpException
{
    private array $data;

    public function getData(): array
    {
        return $this->data;
    }
    public function __construct(int $statusCode, array $data = [] ,string $message = '', ?\Throwable $previous = null, array $headers = [], int $code = 0)
    {
        $this->data = $data;
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }


}