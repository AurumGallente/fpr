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
                'metrics' => json_decode($this->metrics),
                'content' => $this->when(
                    $request->routeIs('texts.show'), $this->content
                ),
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            ],
            'links' => ['self' => route('api.texts.show', $this->id)]
        ];
    }
}
