<?php

namespace App\Models;

use Carbon\Carbon;
use PDPhilip\Elasticsearch\Eloquent\Model as Eloquent;
use PDPhilip\Elasticsearch\Collection\ElasticCollection;
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
