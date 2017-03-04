<?php

namespace Flagrow\Flarum\Api;

use Flagrow\Flarum\Api\Resource\Item;
use Illuminate\Contracts\Cache\Store;

class Cache
{
    /**
     * @var Store
     */
    protected $store;

    /**
     * @var array|Store[]
     */
    protected $stores = [];

    /**
     * Current active store.
     *
     * @var string
     */
    protected $active;

    /**
     * Time in minutes to cache the stored values.
     *
     * @var int
     */
    protected $duration = 60;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * @param string $type
     * @return Cache
     */
    public function setActive(string $type): Cache
    {
        $this->active = $type;

        return $this;
    }

    /**
     * @return Store
     */
    public function getActive(): Store
    {
        return $this->active;
    }

    /**
     * @param string|null $store
     * @return Store
     */
    public function getStore(string $store = null): Store
    {
        $store = $store ?? $this->active;

        if (!array_key_exists($store, $this->stores)) {
            $this->stores[$store] = clone $this->store;
        }

        return $this->stores[$store];
    }

    /**
     * @param int $id
     * @param Item $item
     * @param string|null $type
     * @return Cache
     */
    public function set(int $id, Item $item, string $type = null): Cache
    {
        $this->getStore($type)->put($id, $item, $this->duration);

        return $this;
    }

    /**
     * @param int $id
     * @param null $default
     * @param string|null $type
     * @return mixed
     */
    public function get(int $id, $default = null, string $type = null)
    {
        $value = $this->getStore($type)->get($id);

        return $value ?: $default;
    }

    /**
     * @param string|null $type
     * @return mixed
     */
    public function all(string $type = null)
    {
        return $this->getStore($type)->all();
    }
}