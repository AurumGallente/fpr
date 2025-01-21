<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Project;
use App\Models\User;
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

}
