<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

abstract class BaseController extends Controller
{
    public function __construct()
    {
        $this->apiVersion = 'v1';
    }

    /**
     * @param string $action
     * @param Model $model
     * @return bool
     */
    protected function authorize(string $action, Model $model): bool
    {
        return Gate::allows($this->apiVersion.'-'.$action, $model);
    }

}
