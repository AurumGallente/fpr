<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\ShowProjectRequest;
use App\Http\Requests\V1\StoreProjectRequest;
use App\Http\Requests\V1\UpdateProjectRequest;
use App\Http\Resources\V1\ProjectsResource;
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
     * Display a listing of all projects user is allowed to see.
     * @group Project Management
     * @response 200 { "data": { "type": "project", "id": 165, "attributes": { "name": "The project I've created", "created_at": "2025-02-08 02:46:11" }, "relationships": [ { "language": { "type": "language", "id": 3 } } ], "links": { "self": "http://domain.com/api/V1/projects/165" } } }
     * @response 401 {"message": "Unauthenticated."}
     */
    public function index(): AnonymousResourceCollection
    {
        return ProjectsResource::collection(
            Project::where('user_id', Auth::user()->id)->paginate()
        );
    }

    /**
     * Create a new project
     * @group Project Management
     * @response 201 { "data": { "type": "project", "id": 165, "attributes": { "name": "The project I've created", "created_at": "2025-02-08 02:46:11" }, "relationships": [ { "language": { "type": "language", "id": 3 } } ], "links": { "self": "http://domain.com/api/V1/projects/165" } } }
     * @response 401 {"message": "Unauthenticated."}
     */
    public function store(StoreProjectRequest $request): JsonResponse|ProjectsResource
    {
        $project = new Project();
        if(!$this->authorize(__FUNCTION__, $project))
        {
            return $this->error('No rights to create a project', 403);
        }
        $project->name = $request->input('data.attributes.name');
        $project->description = $request->input('data.attributes.description');
        $project->language_id = $request->input('data.attributes.relationships.language.id');
        $project->user_id = Auth::user()->id;
        $project->save();

        return new ProjectsResource($project);
    }

    /**
     * Show a project
     * @group Project Management
     * @response 201 {"data":{"type":"project","id":160,"attributes":{"name":"API post load","description":"test descr","texts_count":2,"created_at":"2025-01-27 21:07:04"},"relationships":[{"language":{"type":"language","id":6}}],"links":{"self":"http://domain.com/api/V1/projects/160"}}}
     * @response 401 {"message": "Unauthenticated."}
     * @response 403 {"message":"Wrong owner of a project","status":403}
     * @response 404 {"message":"Project not found","status":404}
     */
    public function show(int $project_id): JsonResponse|ProjectsResource
    {
        $project = Project::where('id',$project_id)->first();
        if(!$project){
            return $this->error('Project not found', 404);
        }

        if(!$this->authorize(__FUNCTION__, $project))
        {
            return $this->error('Wrong owner of a project', 403);
        }

        $request = new ShowProjectRequest([
            'project_id' => $project->id,
            'user_id' => $project->user_id
        ]);
        $request->validate($request->rules(), $request->messages());
        return new ProjectsResource($project);
    }

    /**
     * Update a project.
     * @group Project Management
     * @response 200 {"data":{"type":"project","id":160,"attributes":{"name":"API post load","created_at":"2025-01-27 21:07:04"},"relationships":[{"language":{"type":"language","id":6}}],"links":{"self":"http://domain.com/api/V1/projects/160"}}}
     * @response 403 {"message":"Wrong owner of a project","status":403}
     * @response 404 {"message":"Project not found","status":404}
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
            return $this->error('Wrong owner of a project', 403);
        }

        $project->name = $request->input('data.attributes.name');
        $project->description = $request->input('data.attributes.description');
        $project->language_id = $request->input('data.attributes.relationships.language.id');
        $project->user_id = Auth::user()->id;
        $project->save();

        return new ProjectsResource($project);
    }

    /**
     * Delete the specified project.
     * @param int $project_id
     * @return JsonResponse
     * @urlParam id integer required The id of the project.
     * @group Project Management
     * @response 200 {"message": "Project id={id} is deleted","status": 200,"data": []}
     * @response 403 {"message":"Wrong owner of a project","status":403}
     * @response 404 {"message":"Project not found","status":404}
 */
    public function destroy(int $project_id): JsonResponse
    {
        $project = Project::where('id',$project_id)->first();

        if(!$this->authorize(__FUNCTION__, $project))
        {
            return $this->error('Wrong owner of a project', 403);

        }
        if(!$project)
        {
            return $this->error('Project not found', 404);
        }

        if(!$this->authorize(__FUNCTION__, $project))
        {
            return $this->error('Wrong owner of a project', 403);

        }
        $project->delete();
        return $this->success("Project id=$project_id successfully deleted");

    }
}
