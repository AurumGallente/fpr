<?php
namespace App\Traits;

trait ApiFilter {
    /**
     * @param string $relationship
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function include(string $relationship): bool
    {
        $param = request()->get('include');

        if(!isset($param)) {
            return false;
        }

        $values = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $values);
    }
}
