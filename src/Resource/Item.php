<?php

namespace Flagrow\Flarum\Api\Resource;

use Flagrow\Flarum\Api\Traits\HasRelationships;
use Flagrow\Flarum\Api\Traits\UsesCache;
use Illuminate\Support\Arr;

class Item extends Resource
{
    use HasRelationships, UsesCache;
    /**
     * @var string
     */
    public $type;

    /**
     * @var int
     */
    public $id;

    /**
     * @var array
     */
    public $attributes = [];

    public function __construct(array $item = [])
    {
        $this->id = (int) Arr::get($item, 'id');
        $this->type = Arr::get($item, 'type');
        $this->attributes = Arr::get($item, 'attributes', []);

        $this->relations(Arr::get($item, 'relationships', []));
    }

    /**
     * {@inheritdoc}
     */
    function __get($name)
    {
        if (Arr::has($this->attributes, $name)) {
            return Arr::get($this->attributes, $name);
        }

        if (Arr::has($this->relationships, $name)) {
            return Arr::get($this->relationships, $name);
        }
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'attributes' => $this->attributes,
            'relationships' => $this->relationships
        ];
    }
}