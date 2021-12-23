<?php

namespace JoeMugen\EloquentyModel\Contracts;

interface CastsInboundAttributes
{
    /**
     * Transform the attribute to its underlying model values.
     */
    public function set($model, $key, $value, $attributes);
}
