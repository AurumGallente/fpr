<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Language;
use App\Models\Text;

class Project extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'projects';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'language_id',
        'name',
        'description',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function texts(): HasMany
    {
        return $this->hasMany(Text::class, 'project_id', 'id');
    }

    /**
     * @return mixed
     */
    public function lastText()
    {
        return $this->texts()->orderBy('version', 'desc')->first();
    }

}
