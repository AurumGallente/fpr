<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'project',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'description' => $this->when(
                    $request->routeIs('projects.show'), $this->description
                ),
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            ],
            'relationships' => [
                [
                    'language' => [
                        'type' => 'language',
                        'id' => $this->language_id,
                    ],
                    'texts' => [

                    ]
                ]
            ],
            'links' =>  [
                ['self' => route('projects.show', $this->id)]
            ],
        ];
    }
}
