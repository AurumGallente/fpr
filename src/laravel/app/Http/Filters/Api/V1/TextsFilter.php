<?php

namespace App\Http\Filters\Api\V1;

use Illuminate\Database\Eloquent\Builder;

class TextsFilter extends QueryFilter
{
    /**
     * @param $v
     * @return Builder
     */
    public function include($v): Builder
    {
        return $this->builder->with($v);
    }

    /**
     * @param $v
     * @return Builder
     */
    public function version($v): Builder
    {
        return $this->builder->where('version', $v);
    }

    /**
     * @param bool $v
     * @return Builder
     */
    public function processed($v): Builder
    {
        return $this->builder->where('processed', $v);
    }

}
