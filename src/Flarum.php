<?php

namespace Flagrow\Flarum\Api;

use Flagrow\Flarum\Api\Response\Factory;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Cache\ArrayStore;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;

class Flarum
{
    /**
     * @var Guzzle
     */
    protected $rest;

    /**
     * @var Fluent
     */
    protected $fluent;

    /**
     * @var bool
     */
    protected $authorized = false;

    /**
     * Whether to enforce specific markup/variables setting.
     *
     * @var bool
     */
    protected $strict = true;

    /**
     * @var Cache
     */
    protected static $cache;

    /**
     * Flarum constructor.
     * @param $host Full FQDN hostname to your Flarum forum, eg http://example.com/forum
     * @param array $authorization Holding either "token" or "username" and "password" as keys.
     */
    public function __construct($host, array $authorization = [])
    {
        $this->rest = new Guzzle([
            'base_uri' => "$host/api/",
            'headers' => $this->requestHeaders($authorization)
        ]);

        $this->fluent = new Fluent($this);

        static::$cache = new Cache(new ArrayStore);
    }

    /**
     * @return Cache
     */
    public static function getCache(): Cache
    {
        return self::$cache;
    }

    /**
     * @return null
     */
    public function request()
    {
        $method = $this->fluent->getMethod();

        /** @var ResponseInterface $response */
        try {
            $response = $this->rest->{$method}((string)$this->fluent, $this->getVariablesForMethod());
        } finally {
            // Reset the fluent builder for a new request.
            $this->fluent->reset();
        }

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return Factory::build($response);
        }
    }

    /**
     * @param array $authorization
     * @return array
     */
    protected function requestHeaders(array $authorization = [])
    {
        $headers = [
            'Accept' => 'application/vnd.api+json, application/json',
            'User-Agent' => 'Flagrow Api Client'
        ];

        $token = Arr::get($authorization, 'token');

        if ($token) {
            $this->authorized = true;
            Arr::set($headers, 'Authorization', "Token $token");
        }

        return $headers;
    }

    /**
     * {@inheritdoc}
     */
    function __call($name, $arguments)
    {
        return call_user_func_array([$this->fluent, $name], $arguments);
    }

    /**
     * @return array
     */
    protected function getVariablesForMethod(): array
    {
        $variables = $this->fluent->getVariables();

        if (empty($variables)) {
            return [];
        }

        switch ($this->fluent->getMethod()) {
            case 'get':
                return $variables;
                break;
            default:
                return [
                    'json' => ['data' => $variables]
                ];
        }
    }

    /**
     * @return Fluent
     */
    public function getFluent(): Fluent
    {
        return $this->fluent;
    }

    /**
     * @param bool $strict
     * @return Flarum
     */
    public function setStrict(bool $strict): Flarum
    {
        $this->strict = $strict;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStrict(): bool
    {
        return $this->strict;
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->authorized;
    }
}
