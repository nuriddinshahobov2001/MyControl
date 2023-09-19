<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditDebitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'client_id' => $this->client_id,
            'author_id' => $this->author_id,
            'store_id' => $this->store_id,
            'summa' => $this->summa,
            'description' => $this->description
        ];
    }
}
