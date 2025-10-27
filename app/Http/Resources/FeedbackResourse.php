<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResourse extends JsonResource
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
            "location" => [
                "id" => $this->location->id,
                "name" => $this->location->name,
                "longitude" => $this->location->longitude,
                "latitude" => $this->location->latitude,
            ],
            "emotion" => $this->emotion,
            "comment" => $this->comment,
            "status" => $this->status,
            "created_at" => Carbon::make($this->created_at)->format("Y-m-d H:i:s"),
            "updated_at" => Carbon::make($this->updated_at)->format("Y-m-d H:i:s"),
        ];
    }

    public function withResponse($request, $response) {

    }
}
