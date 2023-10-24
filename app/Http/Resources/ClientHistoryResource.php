<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientHistoryResource extends JsonResource
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
            'date' => date('d-m-Y', strtotime($this->date)),
            'store_id' => $this->store_id,
            'store' => $this->store->name,
            'author_id' => $this->author_id,
            'author' => $this->author->fio,
            'summa' => $this->summa,
            'type' => $this->type,
            'client_id' => $this->client_id,
            'client' => $this->client->fio,
            'desc' => $this->description
        ];
    }
}
