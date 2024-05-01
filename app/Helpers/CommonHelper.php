<?php

  namespace App\Helpers;
  class CommonHelper
  {
    /**
     * @throws \HttpResponseException
     */
    function validateOperationState(bool $operationState): void
    {
      if (!$operationState) {
        throw new \HttpResponseException("Error occurred when to write", 400);
      }
    }
  }

