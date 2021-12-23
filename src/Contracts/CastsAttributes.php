<?php

namespace JoeMugen\EloquentyModel\Contracts;

interface CastsAttributes
{
    /**
     * Transform the attribute from the underlying model values.
     */
    public function get($model, $key, $value, $attributes);

    /**
     * Transform the attribute to its underlying model values.
     */
    public function set($model, $key, $value, $attributes);
}
