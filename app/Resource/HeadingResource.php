<?php

namespace App\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeadingResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
