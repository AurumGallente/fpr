<?php

namespace App\Models;

use Carbon\Carbon;
use PDPhilip\Elasticsearch\Eloquent\Model as Eloquent;
use PDPhilip\Elasticsearch\Collection\ElasticCollection;
use Illuminate\Support\Collection;
use PDPhilip\Elasticsearch\Relations\BelongsTo;

class ESChunk extends Eloquent
{
    /**
     * @var string
     */
    protected $connection = 'elasticsearch';

    /**
     * @var string
     */
    protected $index = 'chunks';

    const null UPDATED_AT = null;

    /**
     * @var string|null
     */
    public string|null $content;


    const int ES_CHUNKS_LIMIT = 1024;

    /**
     * @return BelongsTo
     */
    public function chunk(): BelongsTo
    {
        return $this->belongsTo(Chunk::class, 'external_id', 'id');
    }

    /**
     * @param string $text
     * @return ElasticCollection
     */
    public function findSimilarAgainstChunks(string $text): ElasticCollection
    {
        $bodyParams = [
            'query' => [
                'bool'  => [
                    'must_not' => [

                    ],
                    'should' => [

                    ],
                    'must' => [

                    ],
                ],
            ],
            'track_scores' => true,
            'sort' => [
                '_score' => [
                    'order' => 'desc'
                ]
            ],
        ];

        $bodyParams['query']['bool']['should'][] = [
            'match_phrase' => [
                'original_content' => $text,
            ],
        ];

        return self::rawSearch($bodyParams);
    }

    /**
     * @param string $query
     * @return string
     */
    public function escapeElasticsearchQuery(string $query): string
    {
        $special_chars = ['+', '-', '&&', '||', '!', '(', ')', '{', '}', '[', ']', '"', '~', '*', '?'];
        foreach ($special_chars as $char)
        {
            $query = str_replace($char, '', $query);
        }
        return $query;
    }
}
