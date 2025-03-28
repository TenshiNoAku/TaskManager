<?php

namespace App\EventListener;

use App\DTO\Requests\JsonApiResponse;
use App\Services\ServiceException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Doctrine\Common\Collections\ArrayCollection;
class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event) {
        $exception = $event->getThrowable();

        if ($exception instanceof ServiceException) {
            $previousException = $exception->getPrevious();

            if ($previousException instanceof ValidationFailedException) {
                $violations = new ArrayCollection($previousException->getViolations()->getIterator()->getArrayCopy());
                $response = new JsonApiResponse($violations->map(
                    function (ConstraintViolation $violation) {
                        return array(
                            $violation->getPropertyPath() => $violation->getMessage()
                        );
                    }
                )->toArray(),422, [ 'json_encode_options'=> JSON_UNESCAPED_UNICODE| JSON_UNESCAPED_SLASHES,]);

            }
            else{
                $response = new JsonApiResponse($exception->getData(),$exception->getStatusCode(),[ 'json_encode_options'=> JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES],true);
            }
            $event->setResponse($response);

        }
//        else{
//            $response = new JsonApiResponse(array(['code'=>$exception->getCode(),"message"=>$exception->getMessage()]),$exception->getCode(),[ 'json_encode_options'=> JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES],true);
//        }


    }
}