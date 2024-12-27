<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ObjectNotFoundException extends Exception
{
    public function __construct(int $entityId, string $model)
    {
        parent::__construct($model. ' not found with the following {' . $entityId . '}', Response::HTTP_NOT_FOUND);
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
    }
  
    public function render()
    {
        return new JsonResponse([
            'code' => $this->getCode(),
            'message' => $this->getMessage()
        ], Response::HTTP_NOT_FOUND);
    }
}
