<?php

  namespace App\Helpers;
  class CommonHelper
  {
    /**
     * @throws \HttpResponseException
     */
    public function validateOperationState(bool $operationState): void
    {
      if (!$operationState) {
        throw new \HttpResponseException("Error occurred when to write", 400);
      }
    }

    public function checkIfExpired(string $timestampEntity)
    {
      // Ubah timestamp kadaluwarsa dari string menjadi objek Carbon
      $expirationDateTime = \Carbon\Carbon::parse($timestampEntity);

      // Periksa apakah timestamp kadaluwarsa lebih besar dari waktu saat ini (token masih berlaku)
      return $expirationDateTime->isFuture();
    }
  }

