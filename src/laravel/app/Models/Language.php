<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'languages';

    /**
     * @var array
     */
    protected $fillable = ['language', 'code'];

}
