<?php

namespace App\Models;

use App\Helpers\DiffHelper;
use Carbon\Carbon;
use JsonException;
use PDPhilip\Elasticsearch\Eloquent\Model as Eloquent;
use PDPhilip\Elasticsearch\Collection\ElasticCollection;
use Illuminate\Support\Collection;
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
     * @var string|null
     */
    public string|null $content;

    /**
     * @return \PDPhilip\Elasticsearch\Relations\BelongsTo
     */
    public function text(): \PDPhilip\Elasticsearch\Relations\BelongsTo
    {
        return $this->belongsTo(Text::class, 'external_id', 'id');
    }

    /**
     * @param Collection $chunks
     * @param int|null $start
     * @param int|null $limit
     * @return ElasticCollection
     */
    public function findSimilarByChunks(Collection $chunks, int|null $start = null, int|null $limit = null): ElasticCollection
    {
        if(!env('ES_HOSTS'))
        {
            return new ElasticCollection;
        }
        $chunks = $chunks->take(round(env('ES_MAX_CLAUSE_COUNT', ESChunk::ES_CHUNKS_LIMIT)/2 - 1));
        $project_id = $this->text?->project_id;
        $id = $this->text?->id;
        $content = $this->text ? $this->text->content : $this->content;

        $bodyParams = [
            'query' => [
                'bool'  => [
                    'must_not' => [

                    ],
                    'should' => [

                    ]
                ],
            ],
            'track_scores' => true,
            'sort' => [
                '_score' => [
                    'order' => 'desc'
                ]
            ],
        ];

        if($limit !== null)
        {
            $bodyParams['size'] = $limit;
        }

        if($start !== null)
        {
            $bodyParams['from'] = $start;
        }

        foreach ($chunks as $chunk)
        {
            if($chunk instanceof Chunk)
            {
                $chunkContent = $chunk->content;
            } else
            {
                $chunkContent = $chunk;
            }
            $bodyParams['query']['bool']['should'][] = [
                'match_phrase' => [
                    'normalized_content' => $chunkContent,
                ],
            ];
            $bodyParams['query']['bool']['should'][] = [
                'match_phrase' => [
                    'original_content' => $chunkContent,
                ],
            ];
        }
        if($project_id)
        {
            $bodyParams['query']['bool']['must_not'][] = [
                'term' => [
                    'project_id' => $project_id,
                ],
            ];
        }
        if($id)
        {
            $bodyParams['query']['bool']['must_not'][] = [
                'term' => [
                    'external_id' => $id,
                ]
            ];
        }
        return self::rawSearch($bodyParams);
    }

    /**
     * @param array $searchResults
     * @param string $text
     * @return array
     * @throws JsonException
     */
    public function prettySearchResults(array $searchResults, string $text = ''): array
    {
        $helper = new DiffHelper();
        foreach($searchResults as &$searchResult)
        {
            $searchResult['link'] = route('texts.show', ['id' => $searchResult['external_id']]);
            $searchResult['project_link'] = route('projects.show', ['id' => $searchResult['project_id']]);
            $searchResult['date_formatted'] = Carbon::parse($searchResult['created_at'])->format('Y-m-d H:i');
            if($text)
            {
                $searchResult['common_string'] = $helper->longestCommonSubstring($searchResult['original_content'], $text)->longest_common_sequence;
            } else
            {
                $searchResult['common_string'] = '';
            }

        }
        return $searchResults;
    }


}
