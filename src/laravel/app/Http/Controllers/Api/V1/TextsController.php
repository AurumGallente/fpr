<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\V1\TextsFilter;
use App\Http\Requests\V1\ShowProjectRequest;
use App\Http\Resources\V1\TextsResource;
use App\Models\Project;
use App\Traits\ApiFilter;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\Text;

class TextsController extends Controller
{
    use ApiFilter;
    /**
     * Display a listing of the resource.
     */
    public function index(TextsFilter $filter, Project $project): AnonymousResourceCollection
    {
        $request = new ShowProjectRequest([
            'project_id' => $project->id,
            'user_id' => $project->user_id
        ]);
        $request->validate($request->rules(), $request->messages());

        return TextsResource::collection(
            Text::filter($filter)->where('project_id', '=', $project->id)->orderByDesc('version')
            ->paginate()->withQueryString()
        );
    }


    /**
     * Display the specified resource.
     */
    public function show(TextsFilter $filter, Project $project, Text $text): AnonymousResourceCollection
    {
        $request = new ShowProjectRequest([
            'project_id' => $project->id,
            'user_id' => $project->user_id
        ]);
        $request->validate($request->rules(), $request->messages());

        return TextsResource::collection(Text::filter($filter)->paginate());
    }
}
