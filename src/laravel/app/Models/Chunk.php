<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Text;
use Illuminate\Support\Facades\DB;

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

    /**
     * @param array $chunks
     * @return array
     */
    public static function insertBatchReturnIds(array $chunks): array
    {
        $chunks = array_unique($chunks, SORT_STRING);
        $chunks = array_values($chunks);
        $questionMarks = implode(',', array_fill(0, count($chunks), '(?)'));
        $result = [];
        $query = DB::select('
            INSERT INTO chunks (content)
                VALUES '.$questionMarks.'
                ON CONFLICT (content) DO UPDATE SET content = chunks.content returning "id"', $chunks);
        foreach($query as $row)
        {
            $result[] = $row->id;
        }
        return array_values($result);
    }
}
