<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'date' => Carbon::parse($this->date)->format('Y-m-d'),
            'client_id' => $this->client?->fio,
            'author_id' => $this->author?->fio,
            'summa' => (double)$this->summa,
            'description' => $this->description,
            'type' => $this->type
        ];
    }
}
