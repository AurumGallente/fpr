<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreTextRequest;
use App\Http\Resources\V1\TextsResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\Text;

class TextsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return TextsResource::collection(
            Text::where('project_id', '=', request()->project_id)
            ->paginate()->withQueryString()
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTextRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Text $text): TextsResource
    {
        return new TextsResource($text);
    }
}
