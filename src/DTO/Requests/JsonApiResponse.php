<?php

namespace App\DTO\Requests;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsonApiResponse extends JsonResponse
{
    public function __construct(array $data = [], int $status = 200, array $headers = [], bool $json = false)
    {
        if ($status < 400){
            $data = array(
                "success" => true,
                "data"=>$data,
            );
        }
        else{
            $data = array(
                "success" => false,
                "errors"=>$data
            );
        }


        parent::__construct($data, $status, $headers, false);

    }
}