<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\Api\V1\TextsFilter;
use App\Http\Requests\V1\ShowProjectRequest;
use App\Http\Resources\V1\TextsResource;
use App\Models\Project;
use App\Traits\ApiFilter;
use App\Traits\ApiResponses;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\Text;

class TextsController extends BaseController
{
    use ApiFilter, ApiResponses;
    /**
     * Display a listing of all texts of a given project.
     * @group Text Management
     * @queryParam filter[version] int Version of a text
     * @queryParam filter[processed] bool Filters out processed/unprocessed by NLTK texts
     * @response 200 {"data":[{"type":"text","id":1988,"attributes":{"version":2,"project_id":160,"words":1008,"processed":true,"created_at":"2025-01-30 02:14:56"},"relationships":{"project_id":160},"links":{"self":"http://domain.com/api/V1/projects/160/texts/1988"}},{"type":"text","id":1987,"attributes":{"version":1,"project_id":160,"words":1007,"processed":true,"created_at":"2025-01-30 02:00:18"},"relationships":{"project_id":160},"links":{"self":"http://domain.com/api/V1/projects/160/texts/1987"}}],"links":{"first":"http://domain.com/api/V1/projects/160/texts?page=1","last":"http://domain.com/api/V1/projects/160/texts?page=1","prev":null,"next":null},"meta":{"current_page":1,"from":1,"last_page":1,"links":[{"url":null,"label":"&laquo; Previous","active":false},{"url":"http://domain.com/api/V1/projects/160/texts?page=1","label":"1","active":true},{"url":null,"label":"Next &raquo;","active":false}],"path":"http://domain.com/api/V1/projects/160/texts","per_page":15,"to":2,"total":2}}
     * @response 404 {"message":"Project not found","status":404}
     * @response 403 {"message":"Wrong owner of a project","status":403}
     */
    public function index(TextsFilter $filter, Project $project): \Illuminate\Http\JsonResponse|AnonymousResourceCollection
    {
        $request = new ShowProjectRequest([
            'project_id' => $project->id,
            'user_id' => $project->user_id
        ]);
        if(!$this->authorize(__FUNCTION__.'-text', $project))
        {
            return $this->error('No permissions for this resource', 403);
        }

        $request->validate($request->rules(), $request->messages());

        return TextsResource::collection(
            Text::filter($filter)->where('project_id', '=', $project->id)->orderByDesc('version')
            ->paginate()->withQueryString()
        );
    }


    /**
     * Display the specified text of specified project
     * @group Text Management
     * @response 200 {"data":[{"type":"text","id":1988,"attributes":{"version":2,"project_id":160,"words":1008,"processed":true,"created_at":"2025-01-30 02:14:56"},"relationships":{"project_id":160},"links":{"self":"http://domain.com/api/V1/projects/160/texts/1988"}},{"type":"text","id":1987,"attributes":{"version":1,"project_id":160,"words":1007,"processed":true,"created_at":"2025-01-30 02:00:18"},"relationships":{"project_id":160},"links":{"self":"http://domain.com/api/V1/projects/160/texts/1987"}}],"links":{"first":"http://domain.com/api/V1/projects/160/texts?page=1","last":"http://domain.com/api/V1/projects/160/texts?page=1","prev":null,"next":null},"meta":{"current_page":1,"from":1,"last_page":1,"links":[{"url":null,"label":"&laquo; Previous","active":false},{"url":"http://domain.com/api/V1/projects/160/texts?page=1","label":"1","active":true},{"url":null,"label":"Next &raquo;","active":false}],"path":"http://domain.com/api/V1/projects/160/texts","per_page":15,"to":2,"total":2}}
     * @response 404 {"message":"Project not found","status":404}
     * @response 404 {"message":"Text not found","status":404}
     * @response 403 {"message":"Wrong owner of a project","status":403}
     * @response 403 {"message":"No permissions for this resource":403}
     */
    public function show(TextsFilter $filter, int $project_id, int $text_id): \Illuminate\Http\JsonResponse|AnonymousResourceCollection
    {
        $project = Project::where('id', $project_id)->first();
        $text= Text::where('id', $text_id)->first();

        if(!$project){
            return $this->error('Project not found', 404);
        }
        if(!$text){
            return $this->error('Text not found', 404);
        }
        if(!$this->authorize(__FUNCTION__.'-text', $project))
        {
            return $this->error('No permissions for this resource', 403);
        }
        $request = new ShowProjectRequest([
            'project_id' => $project->id,
            'user_id' => $project->user_id
        ]);
        $request->validate($request->rules(), $request->messages());

        return TextsResource::collection(Text::filter($filter)->paginate());
    }
}
