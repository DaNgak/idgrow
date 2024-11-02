<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MutationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'id' => $this->id,
            'id' => $this->uuid,
            'type' => $this->type === 'in' ? 'Masuk' : 'Keluar',
            'quantity' => $this->quantity,
            'date' => $this->date,
            'user' => $this->whenLoaded('user', function () {
                if ($this->user) {
                    return [
                        'id' => $this->user->uuid,
                        'name' => $this->user->name,
                        'email' => $this->user->email,
                        'created_at' => $this->user->created_at->isoFormat('D MMMM YYYY (HH:mm:ss)'),
                    ];
                }
                return null;
            }),
            'product' => $this->whenLoaded('product', function () {
                return $this->product ? new ProductResource($this->product) : null;
            }),
            'created_at' => $this->created_at->isoFormat('D MMMM YYYY (HH:mm:ss)'),
        ];
    }
}
