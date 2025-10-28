<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackSimpleResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "user" => [
                "id" => $this->user->id,
                "first_name" => $this->user->first_name,
                "last_name" => $this->user->last_name,
                "patronymic" => $this->user->patronymic,
                "avatar" => $this->user->avatar,
            ],
            "location" => $this->location->name,
            "emotion" => $this->emotion,
            "comment" => $this->comment,
            "status" => $this->status,
        ];
    }
}
