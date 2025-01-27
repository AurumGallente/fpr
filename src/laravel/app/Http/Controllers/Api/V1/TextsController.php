<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreTextRequest;
use App\Http\Requests\V1\UpdateTextRequest;
use App\Http\Resources\V1\TextsResource;
use App\Models\Text;

class TextsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TextsResource::collection(
            Text::where('project_id', '=', request()->input('project_id'))
            ->paginate()
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
    public function show(Text $text)
    {
        return new TextsResource($text);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Text $text)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTextRequest $request, Text $text)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Text $text)
    {
        //
    }
}
