<?php

namespace Flagrow\Flarum\Api;

use Flagrow\Flarum\Api\Exceptions\UnauthorizedRequestMethodException;

/**
 * Class Fluent
 * @package Flagrow\Flarum\Api
 *
 * @method Fluent discussions
 * @method Fluent groups
 * @method Fluent users
 * @method Fluent tags
 *
 * @method Fluent get
 * @method Fluent head
 * @method Fluent post(array $variables = [])
 * @method Fluent put(array $variables = [])
 * @method Fluent patch(array $variables = [])
 * @method Fluent delete
 */
class Fluent
{
    /**
     * @var array
     */
    protected $types = [
        'discussions',
        'users',
        'groups',
        'tags'
    ];

    protected $methods = [
        'get', 'head',
        'post', 'put', 'patch',
        'delete'
    ];

    protected $methodsRequiringAuthorization = [
        'post', 'put', 'patch', 'delete'
    ];

    /**
     * @var array
     */
    protected $pagination = [
        'filter',
        'page'
    ];

    /**
     * @var array
     */
    protected $segments = [];

    /**
     * @var array
     */
    protected $query = [];

    /**
     * @var array
     */
    protected $includes = [];

    /**
     * @var Flarum
     */
    protected $flarum;

    /**
     * @var string
     */
    protected $method = 'get';

    /**
     * @var array
     */
    protected $variables = [];

    public function __construct(Flarum $flarum)
    {
        $this->flarum = $flarum;
    }

    public function reset()
    {
        $this->segments = [];
        $this->includes = [];
        $this->query = [];
        $this->variables = [];
        $this->method = 'get';
        
        return $this;
    }

    protected function handleType(string $type): Fluent
    {
        $this->segments[] = $type;

        return $this;
    }

    public function setPath(string $path): Fluent
    {
        $this->segments = [$path];

        return $this;
    }

    /**
     * @param string $method
     * @return Fluent
     * @throws UnauthorizedRequestMethodException
     */
    public function setMethod(string $method): Fluent
    {
        $this->method = strtolower($method);

        if (
            $this->flarum->isStrict() &&
            !$this->flarum->isAuthorized() &&
            in_array($this->method, $this->methodsRequiringAuthorization)) {
            throw new UnauthorizedRequestMethodException($this->method);
        }

        return $this;
    }

    public function setVariables(array $variables = [])
    {
        if (count($variables) === 1 && is_array($variables[0])) {
            $this->variables = $variables[0];
        } else {
            $this->variables = $variables;
        }

        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    protected function handlePagination(string $type, $value)
    {
        $this->query[$type] = $value;

        return $this;
    }

    public function id(int $id): Fluent
    {
        $this->segments[] = $id;

        return $this;
    }

    public function include(string $include): Fluent
    {
        $this->includes[] = $include;

        return $this;
    }

    public function offset(int $number): Fluent
    {
        return $this->handlePagination('page[offset]', $number);
    }

    /**
     * {@inheritdoc}
     */
    function __toString()
    {
        $path = implode('/', $this->segments);

        if ($this->includes || $this->query) {
            $path .= '?';
        }

        if ($this->includes) {
            $path .= sprintf(
                'include=%s&',
                implode(',', $this->includes)
            );
        }

        if ($this->query) {
            $path .= http_build_query($this->query);
        }

        return $path;
    }

    /**
     * @param $name
     * @param $arguments
     * @return Fluent
     */
    function __call($name, $arguments)
    {
        if (in_array($name, $this->methods)) {
            if (!empty($arguments)) {
                $this->setVariables($arguments);
            }
            return $this->setMethod($name, $arguments);
        }

        if (count($arguments) === 0 && in_array($name, $this->types)) {
            return $this->handleType($name);
        }

        if (in_array($name, $this->pagination) && count($arguments) === 1) {
            return call_user_func_array([$this, 'handlePagination'], array_prepend($arguments, $name));
        }

        if (method_exists($this->flarum, $name)) {
            return call_user_func_array([$this->flarum, $name], $arguments);
        }
    }
}
