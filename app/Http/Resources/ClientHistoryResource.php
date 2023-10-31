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
            'author_id' => $this->author_id ?? 0,
            'author' => $this->author->fio ?? 'Удаленный пользователь',
            'summa' => $this->summa,
            'type' => $this->type,
            'client_id' => $this->client_id ?? 0,
            'client' => $this->client->fio ?? 'Удаленный клиент',
            'desc' => $this->description ?? ''
        ];
    }
}
