<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TextsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'text',
            'id' => $this->id,
            'attributes' => [
                'version' => $this->version,
                'project_id' => $this->project_id,
                'words' => $this->words,
                $this->mergeWhen(
                    $request->routeIs('api.projects.texts.show'), [
                        'metrics' => json_decode($this->metrics),
                        'content' => $this->content
                    ]
                ),
                'processed' => $this->processed,
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            ],
            'relationships' => [
                'project_id' => $this->project_id,
                'user' => $this->when(
                    $request->routeIs('api.projects.texts.show'), $this->user->name
                ),
            ],
            'links' => [
                'self' => route('api.projects.texts.show', [$this->project_id, $this->id]),
                'project' => $this->when(
                    $request->routeIs('api.projects.texts.show'), route('api.projects.show', $this->project_id)
                ),
            ]
        ];
    }
}
