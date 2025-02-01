<?php

namespace App\Http\Filters\Api\V1;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
abstract class QueryFilter
{
    /**
     * @var Builder
     */
    protected Builder $builder;

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $k=>$v)
        {

            if(method_exists($this, $k))
            {
                $this->$k($v);
            }
        }


        return $this->builder;
    }

    /**
     * @param $data
     * @return Builder
     */
    public function filter($data): Builder
    {
        if($data)
        {
            foreach($data as $k=>$v)
            {
                if(method_exists($this, $k))
                {
                    $this->$k($v);
                }
            }
        }

        return $this->builder;
    }
}
