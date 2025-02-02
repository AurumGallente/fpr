<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ShowProjectRequest;
use App\Http\Requests\V1\StoreProjectRequest;
use App\Http\Requests\V1\UpdateProjectRequest;
use App\Http\Resources\V1\ProjectsResource;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ProjectsResource::collection(
            Project::where('user_id', Auth::user()->id)->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $project = new Project();
        $project->name = $request->input('data.attributes.name');
        $project->description = $request->input('data.attributes.description');
        $project->language_id = $request->input('data.attributes.relationships.language.id');
        $project->user_id = Auth::user()->id;
        $project->save();

        return new ProjectsResource($project);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): ProjectsResource
    {
        $request = new ShowProjectRequest([
            'project_id' => $project->id,
            'user_id' => $project->user_id
        ]);
        $request->validate($request->rules(), $request->messages());
        return new ProjectsResource($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
