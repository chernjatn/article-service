<?php

namespace App\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeoResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        return [
            'header' => $this->header,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
