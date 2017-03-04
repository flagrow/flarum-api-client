<?php

namespace Flagrow\Flarum\Api;

class Fluent
{
    /**
     * @var array
     */
    protected $types = [
        'discussions',
        'users',
    ];

    /**
     * @var array
     */
    protected $segments = [];

    /**
     * @var array
     */
    protected $includes = [];

    /**
     * @param string $type
     * @return Fluent
     */
    protected function handleType($type = '')
    {
        $this->segments[] = $type;

        return $this;
    }

    /**
     * @param $id
     * @return Fluent
     */
    public function id($id)
    {
        $this->segments[] = $id;

        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @return Fluent
     */
    function __call($name, $arguments)
    {
        if (count($arguments) === 0 && in_array($name, $this->types)) {
            return $this->handleType($name);
        }
    }
}