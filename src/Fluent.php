<?php

namespace Flagrow\Flarum\Api;

use Flagrow\Flarum\Api\Exceptions\UnauthorizedRequestMethodException;

class Fluent
{
    /**
     * @var array
     */
    protected $types = [
        'discussions',
        'users',
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

    /**
     * @param string $type
     * @return Fluent
     */
    protected function handleType(string $type): Fluent
    {
        $this->segments[] = $type;

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

    /**
     * @param array $variables
     * @return $this
     */
    public function setVariables(array $variables = [])
    {
        if (count($variables) === 1 && is_array($variables[0])) {
            $this->variables = $variables[0];
        } else {
            $this->variables = $variables;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @param string $type
     * @param $value
     * @return $this
     */
    protected function handlePagination(string $type, $value)
    {
        $this->query[$type] = $value;

        return $this;
    }

    /**
     * @param $id
     * @return Fluent
     */
    public function id(int $id): Fluent
    {
        $this->segments[] = $id;

        return $this;
    }

    /**
     * @param string $include
     * @return Fluent
     */
    public function include(string $include): Fluent
    {
        $this->includes[] = $include;

        return $this;
    }

    /**
     * @param int $number
     * @return Fluent
     */
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