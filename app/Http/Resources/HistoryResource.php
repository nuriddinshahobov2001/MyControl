<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
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
            'client' => $this->client?->fio,
            'author' => $this->author?->fio,
            'authorId' => $this->author?->id,
            'client_id' => $this->author?->id,
            'type' => $this->type,
            'summa' => $this->summa
        ];
    }
}
