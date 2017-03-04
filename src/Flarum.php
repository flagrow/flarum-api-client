<?php

namespace Flagrow\Flarum\Api;

use GuzzleHttp\Client as Guzzle;
use Illuminate\Support\Arr;

class Flarum {
    /**
     * @var Guzzle
     */
    protected $rest;

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
    }

    /**
     * @param array $authorization
     * @return array
     */
    protected function requestHeaders(array $authorization = [])
    {
        $headers = [];
        $token = Arr::get($authorization, 'token');

        if ($user = Arr::get($authorization, 'user') && $password = Arr::get($authorization, 'password')) {
            $this->retrieveToken($user, $password);
        }

        if ($token) {
            Arr::set($headers, 'Authorization', "Bearer $token");
        }

        return $headers;
    }
}