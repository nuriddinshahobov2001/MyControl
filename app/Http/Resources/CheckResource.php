<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'author' => $this->author?->fio,
            'store' => $this->store?->name,
            'summa' => $this->summa,
            'date' => $this->date,
            'type' => $this->type
        ];
    }
}
