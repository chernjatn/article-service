<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Heading;
use App\Resource\HeadingResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HeadingController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $headings = Heading::all();

        return HeadingResource::collection($headings);
    }
}
