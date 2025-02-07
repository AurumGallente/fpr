<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\ShowProjectRequest;
use App\Http\Requests\V1\StoreProjectRequest;
use App\Http\Requests\V1\UpdateProjectRequest;
use App\Http\Resources\V1\ProjectsResource;
use App\Models\Permission;
use App\Models\Project;
use App\Policies\Api\V1\ProjectPolicy;
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\JsonResponse;
use \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectsController extends BaseController
{
    use ApiResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection|JsonResponse
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::API_V1_SEE_ALL_PROJECTS))
        {
            return ProjectsResource::collection(
                Project::paginate()
            );
        }
        if($user->tokenCan(Permission::API_V1_SEE_OWN_PROJECTS))
        {
            return ProjectsResource::collection(
                Project::where('user_id', Auth::user()->id)->paginate()
            );
        }
        return $this->error('No permissions for this resource', 403);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): JsonResponse|ProjectsResource
    {
        $project = new Project();
        if(!$this->authorize(__FUNCTION__, $project))
        {
            return $this->error('No permissions to create a project', 403);
        }
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
    public function show(int $project_id): JsonResponse|ProjectsResource
    {
        $project = Project::where('id',$project_id)->first();
        if(!$project){
            return $this->error('Project not found', 404);
        }



        if(!$this->authorize(__FUNCTION__, $project))
        {
            return $this->error('No permissions for this resource', 403);
        }

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
    public function update(UpdateProjectRequest $request, int $project_id): JsonResponse|ProjectsResource
    {
        $project = Project::where('id',$project_id)->first();
        if(!$project)
        {
            return $this->error('Project not found', 404);
        }
        if(!$this->authorize(__FUNCTION__, $project))
        {
            return $this->error('No permissions for this resource', 403);
        }

        $project->name = $request->input('data.attributes.name');
        $project->description = $request->input('data.attributes.description');
        $project->language_id = $request->input('data.attributes.relationships.language.id');
        $project->user_id = Auth::user()->id;
        $project->save();

        return new ProjectsResource($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $project_id): JsonResponse
    {
        $project = Project::where('id',$project_id)->first();

        if(!$this->authorize(__FUNCTION__, $project))
        {
            return $this->error('No permissions for this resource', 403);

        }
        if(!$project)
        {
            return $this->error('Project not found', 404);
        }

        if(!$this->authorize(__FUNCTION__, $project))
        {
            return $this->error('No permissions for this resource', 403);

        }
        $project->delete();
        return $this->success("Project id=$project_id successfully deleted");

    }
}
