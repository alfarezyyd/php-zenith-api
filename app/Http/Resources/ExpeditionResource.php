<?php

  namespace App\Http\Resources;

  use Illuminate\Http\Request;
  use Illuminate\Http\Resources\Json\JsonResource;
  use Illuminate\Support\Str;

  class ExpeditionResource extends JsonResource
  {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
        'name' => Str::upper($this->name)
      ];
    }
  }
