<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'date' =>date('d-m-Y',strtotime($this->date)) ,
            'client' => $this->client->fio ?? 'Удаленный клиент',
            'author' => $this->author->fio ?? 'Удаленный пользователь',
//            'authorId' => $this->author->id ?? 0,
            'client_id' => $this->client->id ?? 0,
            'type' => $this->type,
            'summa' => $this->summa,
            'desc' => $this->description ?? 'test',
        ];
    }
}
