<?php

namespace App\Models;

use App\Helpers\ReadabilityHelper;
use App\Http\Filters\Api\V1\QueryFilter;
use App\Http\Filters\Api\V1\TextsFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Project;
use App\Models\User;
use App\Models\Chunk;
use Illuminate\Database\Eloquent\Builder;
use StdClass;


class Text extends Model
{
    use SoftDeletes;

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

    protected $casts = [
        'chunks_ids' => 'array',
    ];

    const CHUNK_LENGTH = 5;

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
        $helper = new ReadabilityHelper($this->content, $this->project->language->code);
        $step = round(self::CHUNK_LENGTH/2);
        $chunks = [];
        foreach(explode("\n", $this->content) as $text)
        {
            // remove punctuation
            $text=  preg_replace("#[[:punct:]]#", "", $text);
            // remove extra whitespaces
            $text = preg_replace('/\s+/', ' ', $text);
            // remove diacritics
            $text = $helper->remove_accents($text);
            $words = explode(' ', $text);
            $count = count($words);
            if($count > self::CHUNK_LENGTH)
            {
                for($i = 0; $i <= ($count-self::CHUNK_LENGTH); $i = $i + $step)
                {
                    $chunk = [];
                    for($j = 0; $j < self::CHUNK_LENGTH; $j++)
                    {
                        $chunk[] = $words[$i + $j];
                    }
                    $chunks[] = implode(' ', $chunk);
                }
            } else
            {
                $chunks[] = implode(' ', $words);
            }
        }

        return $chunks;
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


}
