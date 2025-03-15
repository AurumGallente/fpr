<?php

namespace App\Models;

use Carbon\Carbon;
use PDPhilip\Elasticsearch\Eloquent\Model as Eloquent;
use \PDPhilip\Elasticsearch\Collection\ElasticCollection;
use Illuminate\Support\Collection;

class ESChunk extends Eloquent
{
    protected $connection = 'elasticsearch';

    protected $index = 'chunks';

    const null UPDATED_AT = null;

    public string|null $content;

    public function chunk(): \PDPhilip\Elasticsearch\Relations\BelongsTo
    {
        return $this->belongsTo(Chunk::class, 'external_id', 'id');
    }
}
