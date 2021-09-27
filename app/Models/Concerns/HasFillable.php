<?php

namespace App\Models\Concerns;

trait HasFillable
{
    /**
     * Get fillable attributes.
     *
     * @return array
     */
    public static function getFillableAttributes()
    {
        return (new static)->getFillable();
    }
}