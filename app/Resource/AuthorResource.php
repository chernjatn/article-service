<?php

namespace App\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'status' => $this->status,
            'gender' => $this->gender,
            'speciality' => $this->speciality,
            'place_of_work' => $this->place_of_work,
            'education' => $this->education,
            'experience' => $this->experience,
            'documents' => $this->getMedia('documents')
        ];
    }
}
