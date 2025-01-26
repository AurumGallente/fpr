<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

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
        'chunks_ids' => 'json',
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
     * @return \Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson
     */
    public function chunks(): \Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson
    {
        return $this->belongsToJson(Chunk::class, 'chunks_ids');
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
        $step = round(self::CHUNK_LENGTH/2);
        $chunks = [];
        foreach(explode("\n", $this->content) as $text)
        {
            // remove punctuation
            $text= preg_replace('/[^\w\s]/', ' ', $text);
            // remove extra whitespaces
            $text = preg_replace('/\s+/', ' ', $text);
            // remove diacritics
            $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
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

}
