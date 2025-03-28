<?php

namespace App\Models;

use App\Helpers\ReadabilityHelper;
use App\Http\Filters\Api\V1\QueryFilter;
use App\Http\Filters\Api\V1\TextsFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PDPhilip\Elasticsearch\Eloquent\HybridRelations;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Project;
use App\Models\User;
use App\Models\Chunk;
use Illuminate\Database\Eloquent\Builder;
use StdClass;
use App\Casts\Pgarray;


class Text extends Model
{
    use SoftDeletes, HybridRelations;

    /**
     * @var string
     */
    protected $table = 'texts';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'content',
        'user_id',
        'project_id',
        'version',
        'metrics',
    ];

    protected function casts(): array
    {
        return [
            'chunks_ids' => Pgarray::class,
        ];
    }

    const CHUNK_LENGTH = 5;

    /**
     *
     */
    const RESULTS_LIMIT = 10;

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    /**
     * @return BelongsToMany
     */
    public function chunks(): BelongsToMany
    {
        return $this->belongsToMany(Chunk::class, 'chunk_text', 'text_id', 'chunk_id');
    }



    /**
     * @return StdClass
     */
    public function readabilityMetrics(): stdClass
    {
        if($this->metrics)
        {
            return json_decode(trim($this->metrics));
        }
        else
        {
            return new stdClass();
        }
    }

    /**
     * @return array
     */
    public function chunking(): array
    {
        return new ReadabilityHelper($this->content, $this->project->language->code)->chunking();
    }

    /**
     * @param Builder $builder
     * @param QueryFilter $filter
     * @return Builder
     */
    public function scopeFilter(Builder $builder, TextsFilter $filter): Builder
    {
        return $filter->apply($builder);
    }


    /**
     * @return \PDPhilip\Elasticsearch\Relations\HasOne|\Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function EStext(): \PDPhilip\Elasticsearch\Relations\HasOne|\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(EStext::class, 'external_id', 'id');
    }


    /**
     * @return Collection
     */
    public function findSimilarByChunks(int $limit = self::RESULTS_LIMIT): Collection
    {
        $project = $this->project()->withTrashed()->first();
        return self::where('project_id', '!=', $project->id)
            ->whereRaw("texts.chunks_ids && '{".implode(' ,', $this->chunks_ids)."}'::int[]")
            ->limit($limit)
            ->get();
    }

    /**
     * @param Text $text
     * @param int $limit
     * @return Collection
     */
    public function getCommonChunks(Text $text, int $limit = self::RESULTS_LIMIT): Collection
    {
        $chunksIDs =array_intersect($this->chunks_ids, $text->chunks_ids);
        return Chunk::whereIn('id', $chunksIDs)->where('content','!=',' ')->limit($limit)->get();
    }

}
