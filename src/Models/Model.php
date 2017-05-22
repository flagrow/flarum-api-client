<?php

namespace Flagrow\Flarum\Api\Models;

use Flagrow\Flarum\Api\Flarum;
use Flagrow\Flarum\Api\Resource\Item;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class Model
{
    /**
     * @var Flarum
     */
    protected static $dispatcher;
    /**
     * @var int|null
     */
    protected $id;
    /**
     * @var array
     */
    protected $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * @param Flarum $dispatcher
     */
    public static function setDispatcher(Flarum $dispatcher)
    {
        self::$dispatcher = $dispatcher;
    }

    /**
     * @return Flarum
     */
    public static function getDispatcher(): Flarum
    {
        return self::$dispatcher;
    }

    /**
     * Resource type.
     *
     * @return string
     */
    public function type(): string
    {
        return Str::plural(Str::lower(
            Str::replaceFirst(__NAMESPACE__ . '\\', '', static::class)
        ));
    }

    /**
     * Generated resource item.
     *
     * @return Item
     */
    public function item(): Item
    {
        return new Item([
            'type' => $this->type(),
            'attributes' => $this->attributes
        ]);
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * Create or update.
     */
    public function save()
    {
        return static::$dispatcher
            ->{$this->type()}()
            ->post(
            $this->item()->toArray()
        )->request();
    }

    /**
     * {@inheritdoc}
     */
    function __set($name, $value)
    {
        if ($name === 'id') {
            $this->id = $value;
        } else {
            $this->attributes[$name] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    function __get($name)
    {
        return Arr::get($this->attributes, $name);
    }
}