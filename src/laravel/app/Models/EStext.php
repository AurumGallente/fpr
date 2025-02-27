<?php

namespace App\Models;

use PDPhilip\Elasticsearch\Eloquent\Model as Eloquent;

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
}
