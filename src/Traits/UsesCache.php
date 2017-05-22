<?php

namespace Flagrow\Flarum\Api\Traits;

use Flagrow\Flarum\Api\Flarum;
use Flagrow\Flarum\Api\Resource\Item;

trait UsesCache
{
    /**
     * @return Item
     */
    public function cache()
    {
        Flarum::getCache()->set($this->id, $this, $this->type);

        return $this;
    }
}