<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use App\Models\Text;

class Chunk extends Model
{
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $table = 'chunks';

    /**
     * @var string[]
     */
    protected $fillable = [
        'content',
        'hash'
    ];

    /**
     * @return \Staudenmeir\EloquentJsonRelations\Relations\HasManyJson
     */
    public function texts(): \Staudenmeir\EloquentJsonRelations\Relations\HasManyJson
    {
        return $this->hasManyJson(Text::class, 'chunks_ids');
    }
}
