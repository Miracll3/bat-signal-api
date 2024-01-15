<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PanicResource extends JsonResource
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
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'panic_type' => $this->panic_type,
            'details' => $this->details,
            'created_at' => $this->created_at,
            'created_by' => new UserResource(User::findOrFail($this->created_by)),
        ];
    }
}
