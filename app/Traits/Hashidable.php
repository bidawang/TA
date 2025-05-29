<?php

namespace App\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait Hashidable
{
    public function getRouteKey()
    {
        return Hashids::encode($this->getKey());
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $decoded = Hashids::decode($value);
        if (empty($decoded)) {
            abort(404);
        }

        return $this->where($this->getKeyName(), $decoded[0])->firstOrFail();
    }
}
