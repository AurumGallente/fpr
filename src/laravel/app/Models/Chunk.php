<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Text;

class Chunk extends Model
{

    protected $table = 'chunks';

    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'content',
        'hash'
    ];

    /**
     * @return BelongsToMany
     */
    public function texts(): BelongsToMany
    {
        return $this->belongsToMany(Text::class, 'chunk_text', 'chunk_id', 'text_id');
    }
}
