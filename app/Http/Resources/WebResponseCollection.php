<?php

  namespace App\Http\Resources;

  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Http\Request;
  use Illuminate\Http\Resources\Json\JsonResource;
  use Illuminate\Http\Resources\Json\ResourceCollection;

  class WebResponseCollection extends ResourceCollection
  {
    private JsonResource|null $responseModel;
    private string $responseMessage;
    private mixed $responseError;
    private mixed $responsePaging;

    /**
     * @param JsonResource|null $responseModel
     * @param string $responseMessage
     * @param mixed $responseError
     * @param mixed $responsePaging
     */
    public function __construct(string $responseMessage, JsonResource|null $responseModel = null, mixed $responseError = null, mixed $responsePaging = null)
    {
      parent::__construct($responseModel);
      $this->responseMessage = $responseMessage;
      $this->responseModel = $responseModel;
      $this->responseError = $responseError;
      $this->responsePaging = $responsePaging;
    }
    /**
     * @param Model $responseModel
     * @param String $responseMessage
     */

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
        'data' => [
          'value' => $this->responseModel,
          'message' => $this->responseMessage,
        ],
        'errors' => $this->responseError,
        'paging' => $this->responsePaging,
      ];
    }
  }
