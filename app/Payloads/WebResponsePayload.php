<?php

  namespace App\Payloads;

  use Illuminate\Http\Resources\Json\JsonResource;

  class WebResponsePayload
  {
    private JsonResource|null $jsonResource;
    private mixed $responseMessage;
    private mixed $errorInformation;
    private mixed $dataPaging;

    /**
     * @param JsonResource|null $jsonResource
     * @param mixed $responseMessage
     * @param mixed $errorInformation
     * @param mixed $dataPaging
     */
    public function __construct(mixed $responseMessage, mixed $errorInformation = null, ?JsonResource $jsonResource = null, mixed $dataPaging = null)
    {
      $this->jsonResource = $jsonResource;
      $this->responseMessage = $responseMessage;
      $this->errorInformation = $errorInformation;
      $this->dataPaging = $dataPaging;
    }

    public function getJsonResource(): array
    {
      return [
        'data' => $this->jsonResource,
        'message' => $this->responseMessage,
        'errors' => $this->errorInformation,
        'paging' => $this->dataPaging,
      ];
    }

  }
