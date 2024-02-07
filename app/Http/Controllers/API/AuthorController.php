<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Resource\AuthorDetailResource;
use App\Resource\AuthorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->input('per_page');

        $authors = Author::query()
            ->with('media')
            ->paginate($perPage);

        return AuthorResource::collection($authors);
    }

    public function show(Author $author): AuthorDetailResource
    {
        return new AuthorDetailResource($author);
    }
}
