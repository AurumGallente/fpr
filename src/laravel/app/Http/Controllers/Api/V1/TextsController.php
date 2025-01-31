<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ShowProjectRequest;
use App\Http\Requests\V1\StoreTextRequest;
use App\Http\Resources\V1\TextsResource;
use App\Models\Project;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\Text;

class TextsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project): AnonymousResourceCollection
    {
        $request = new ShowProjectRequest([
            'project_id' => $project->id,
            'user_id' => $project->user_id
        ]);
        $request->validate($request->rules(), $request->messages());

        return TextsResource::collection(
            Text::where('project_id', '=', $project->id)->orderByDesc('version')
            ->paginate()->withQueryString()
        );
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
    public function show(Project $project, Text $text): TextsResource
    {
        $request = new ShowProjectRequest([
            'project_id' => $project->id,
            'user_id' => $project->user_id
        ]);
        $request->validate($request->rules(), $request->messages());

        return new TextsResource($text);
    }
}
