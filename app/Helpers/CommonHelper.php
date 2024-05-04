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

    public function slugifyString(string $initializeString): string
    {
      // Trim whitespace dari awal dan akhir string
      $slugifiedString = trim($initializeString);

      // Konversi ke huruf kecil (lowercase)
      $slugifiedString = strtolower($slugifiedString);

      // Hapus karakter selain huruf kecil (a-z), angka (0-9), spasi, dan dash (-)
      $slugifiedString = preg_replace('/[^a-z0-9\s-]/', '', $slugifiedString);

      // Ganti satu atau lebih spasi atau dash dengan satu dash tunggal
      // Kembalikan hasil normalisasi string
      return preg_replace('/[\s-]+/', '-', $slugifiedString);
    }
  }

