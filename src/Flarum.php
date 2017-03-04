<?php

namespace Flagrow\Flarum\Api;

use Flagrow\Flarum\Api\Response\Factory;
use GuzzleHttp\Client as Guzzle;
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

        $this->fluent();
    }

    protected function fluent()
    {
        $this->fluent = new Fluent($this);

        return $this;
    }

    protected function handleRequest()
    {
        $method = $this->fluent->getMethod();

        /** @var ResponseInterface $response */
        $response = $this->rest->{$method}((string)$this->fluent);

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            // Reset the fluent builder for a new request.
            $this->fluent();
            return Factory::build($response);
        }
    }

    public function get()
    {
        $response = $this->rest->get((string)$this->fluent);
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
            Arr::set($headers, 'Authorization', "Bearer $token");
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
}