<?php

namespace Flagrow\Flarum\Api\Resource;

use Flagrow\Flarum\Api\Flarum;
use Illuminate\Support\Arr;

class Item extends Resource
{

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

    /**
     * @var array
     */
    public $relationships = [];

    public function __construct(array $item = [])
    {
        $this->id = (int) Arr::get($item, 'id');
        $this->type = Arr::get($item, 'type');
        $this->attributes = Arr::get($item, 'attributes', []);

        $this->relations(Arr::get($item, 'relationships', []));
    }

    /**
     * @return Item
     */
    public function cache(): Item
    {
        Flarum::getCache()->set($this->id, $this, $this->type);

        return $this;
    }

    /**
     * @param array $relations
     */
    protected function relations(array $relations = [])
    {
        foreach ($relations as $attribute => $relation) {
            $data = Arr::get($relation, 'data');

            if (Arr::get($data, 'type')) {
                $this->relationships[$attribute] = $this->parseRelationshipItem(
                    Arr::get($data, 'type'),
                    Arr::get($data, 'id')
                );
            } else {
                $this->relationships[$attribute] = [];

                foreach ($data as $item) {
                    $id = (int) Arr::get($item, 'id');
                    $this->relationships[$attribute][$id] = $this->parseRelationshipItem(
                        Arr::get($item, 'type'),
                        $id
                    );
                }
            }
        }
    }

    /**
     * @param string $type
     * @param int $id
     * @return Item|null
     */
    protected function parseRelationshipItem(string $type, int $id)
    {
        return Flarum::getCache()->get($id, null, $type);
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