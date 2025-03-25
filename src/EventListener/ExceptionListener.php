<?php

namespace App\EventListener;

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

        if ($exception instanceof HttpException) {
            $previousException = $exception->getPrevious();

            if ($previousException instanceof ValidationFailedException) {
                $violations = new ArrayCollection($previousException->getViolations()->getIterator()->getArrayCopy());
                $response = new JsonResponse($violations->map(
                    function (ConstraintViolation $violation) {
                        return array(
                            $violation->getPropertyPath() => $violation->getMessage()
                        );
                    }
                )->toArray(),422, [ 'json_encode_options'=> JSON_UNESCAPED_UNICODE,]);

            }
            else{
                $response = new JsonResponse($exception->getMessage(),$exception->getStatusCode(),[ 'json_encode_options'=> JSON_UNESCAPED_UNICODE,]);
            }
            $event->setResponse($response);
        }

    }
}