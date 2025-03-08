<?php

namespace App\Models;

use PDPhilip\Elasticsearch\Eloquent\Model as Eloquent;
use \PDPhilip\Elasticsearch\Collection\ElasticCollection;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Text;

class EStext extends Eloquent
{
    /**
     * @var string
     */
    protected $connection = 'elasticsearch';

    /**
     * @var string
     */
    protected $index = '*_text_index';

    /**
     *
     */
    const null UPDATED_AT = null;

    /**
     *
     */
    const string INDEX_PATTERN = '_text_index';

    /**
     * @return \PDPhilip\Elasticsearch\Relations\BelongsTo
     */
    public function text(): \PDPhilip\Elasticsearch\Relations\BelongsTo
    {
        return $this->belongsTo(Text::class, 'external_id', 'id');
    }

    public function findSimilarByChunks(Collection $chunks): ElasticCollection
    {
        if(!env('ES_HOSTS'))
        {
            return new ElasticCollection;
        }

        $project_id = $this->text->project_id;
        $id = $this->text->id;

        $bodyParams = [
            'query' => [
                'bool'  => [
                    'must_not' => [

                    ],
                    'should' => [
                        [
                            'more_like_this' => [
                                'fields' => ['content', 'normalized_content'],
                                'like' => $this->text->content
                            ]
                        ]
                    ]
                ],
            ],
            'sort' => [
                '_score' => [
                    'order' => 'desc'
                ]
            ],
        ];

        foreach ($chunks as $chunk)
        {
            $bodyParams['query']['bool']['should'][] = [
                'match_phrase' => [
                    'normalized_content' => $chunk->content,
                ],
            ];
            $bodyParams['query']['bool']['should'][] = [
                'match_phrase' => [
                    'content' => $chunk->content,
                ],
            ];
        }

        $bodyParams['query']['bool']['must_not'][] = [
            'term' => [
                'project_id' => $project_id,
            ],
        ];
        $bodyParams['query']['bool']['must_not'][] = [
            'term' => [
                'external_id' => $id,
            ]
        ];


        return self::rawSearch($bodyParams);
    }
}
