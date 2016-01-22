<?php

namespace Flagrow\Flarum\Api;

use GuzzleHttp\Client as Guzzle;

class Client
{

    /**
     * Flarum user token.
     *
     * @var string
     */
    protected $token;

    /**
     * @var
     */
    protected $guzzle;

    /**
     * API endpoint of the Flarum installation.
     *
     * @var string
     */
    protected $apiUrl;

    /**
     * Client constructor.
     *
     * @param string $apiUrl
     * @param null   $token
     */
    public function __construct($apiUrl = 'https://discuss.flarum.org/api/', $token = null)
    {

        $options = [
            'base_uri' => $apiUrl,
            'headers'  => [
                'User-Agent' => 'Flagrow/Flarum/Api/Client',
                'Accept'     => 'application/json'
            ]
        ];

        if (!empty($token)) {
            $options['auth'] = 'token ' . $token;
        }

        $this->guzzle = new Guzzle($options);
    }

    /**
     * Loads a subset of discussions or an Id
     *
     * @param null $id
     * @return array
     */
    public function discussions($id = null)
    {
        $result = $this->guzzle->get('discussions' . ($id ? '/' . $id : null));

        return json_decode($result->getBody(), true);
    }


}