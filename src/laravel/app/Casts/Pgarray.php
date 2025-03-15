<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use JsonException;

class Pgarray implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param array<string, mixed> $attributes
     * @throws JsonException
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if($value)
        {
            return json_decode(str_replace(['{', '}'], ['[', ']'], $value), true, 512, JSON_THROW_ON_ERROR);
        }

        return null;

    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return '{'.implode(' ,', $value).'}';
    }
}
