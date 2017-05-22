<?php

namespace Flagrow\Flarum\Api\Traits;

use Flagrow\Flarum\Api\Flarum;
use Flagrow\Flarum\Api\Resource\Item;
use Illuminate\Support\Arr;

trait HasRelationships
{


    /**
     * @var array
     */
    public $relationships = [];

    /**
     * @param array $relations
     */
    protected function relations(array $relations = [])
    {
        foreach ($relations as $attribute => $relation) {
            $data = Arr::get($relation, 'data');

            // Single item.
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
}