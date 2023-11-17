<?php

namespace App\Resource;

use Illuminate\Http\Request;

class AuthorDetailResource extends AuthorResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        return parent::toArray($request) + [
                'place_of_work' => $this->place_of_work,
                'education' => $this->education,
                'status' => $this->status,
                'gender' => $this->gender,
                'meta' => new SeoResource($this->resource->seo),
                'documents' => $this->getMedia('documents')?->pluck('original_url', 'name'),
            ];
    }
}
