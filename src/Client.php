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
     * @param array  $options
     */
    public function __construct($apiUrl = 'https://discuss.flarum.org/api/', $token = null, $options = [])
    {

        $options = array_merge([
            'base_uri' => $apiUrl,
            'headers'  => [
                'User-Agent'   => 'Flagrow/Flarum/Api/Client',
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ], $options);

        if (!empty($token)) {
            $options['headers']['Authorization'] = 'Token ' . $token;
        }

        $this->guzzle = new Guzzle($options);
    }

    /**
     * Loads a subset of discussions or an Id.
     *
     * @param null $id
     * @return array
     */
    public function discussions($id = null)
    {
        $result = $this->guzzle->get('discussions' . ($id ? '/' . $id : null));

        return json_decode($result->getBody(), true);
    }

    /**
     * Creates a new tag.
     *
     * @param        $name
     * @param        $slug
     * @param string $description
     * @param string $color
     * @param bool   $isHidden
     * @return array
     */
    public function createTag($name, $slug, $description = '', $color = '', $isHidden = false)
    {
        $result = $this->guzzle->post('tags', [
            'json' => [
                'data' => [
                    'type' => 'tags',
                    'attributes' => compact('name', 'slug', 'description', 'color', 'isHidden')
                ]
            ]
        ]);
        return json_decode($result->getBody(), true);
    }

}