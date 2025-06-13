<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category_id' => $this->category ? $this->category->id : null,
            'category_name' => $this->category ? $this->category->name : null,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'enabled' => $this->enabled,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
