<?php

/*
 * This file is part of flagrow/flarum-api-client.
 *
 * Copyright (c) Flagrow.
 *
 * http://flagrow.github.io
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

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

    protected function request($method = 'get', $url, $options = [])
    {
        /** @var \Psr\Http\Message\ResponseInterface $result */
        $result = $this->guzzle->{$method}($url, $options);

        switch ($result->getStatusCode()) {
            case 200:
            case 201:
                return json_decode($result->getBody(), true);
                break;
        }

        // let's keep this debugger friend here for now
        // mark as @todo
        dd($result->getStatusCode());
    }

    /**
     * Loads one or a set of the specified type.
     *
     * @param       $type
     * @param null  $id
     * @param array $options
     * @return mixed
     */
    public function load($type, $id = null, $options = [])
    {
        return $this->request('get', $type . ($id ? '/' . $id : null), $options);
    }

    /**
     * Creates an object of the specified type.
     *
     * @param       $type
     * @param array $attributes
     * @param array $relations
     * @param array $options
     * @return mixed
     */
    public function create($type, $attributes = [], $relations = [], $options = [])
    {
        return $this->request('post', $type, array_merge($options, [
            'json' => [
                'data' => [
                    'type'          => $type,
                    'attributes'    => $attributes,
                    'relationships' => $this->parseRelationships($relations)
                ]
            ]
        ]));
    }

    /**
     * @param       $type
     * @param       $id
     * @param array $attributes
     * @param array $relations
     * @param array $options
     * @return mixed
     */
    public function update($type, $id, $attributes = [], $relations = [], $options = [])
    {
        return $this->request('patch', $type . '/' . $id, array_merge($options, [
            'json' => [
                'data' => [
                    'type'          => $type,
                    'attributes'    => $attributes,
                    'relationships' => $this->parseRelationships($relations)
                ]
            ]
        ]));
    }

    /**
     * Loads a subset of discussions or an Id.
     *
     * @param null $id
     * @return array
     */
    public function discussions($id = null)
    {
        return $this->load('discussions', $id);
    }


    /**
     * Creates a first discussion with the authenticated user as actor.
     *
     * @param $title
     * @param $content
     * @return array
     */
    public function createDiscussion($title, $content)
    {
        return $this->create('discussions', compact('title', 'content'));
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
        return $this->create('tags', compact('name', 'slug', 'description', 'color', 'isHidden'));
    }

    /**
     * Registers a user.
     *
     * @info this requires an admin user.
     *
     * @param      $username
     * @param      $password
     * @param      $email
     * @param null $token
     * @return array
     */
    public function registerUser($username, $password, $email, $token = null)
    {
        return $this->create('users', compact('username', 'password', 'email', 'token'));
    }

    /**
     * @param       $userId
     * @param array $groups
     * @return mixed
     */
    public function setUserGroups($userId, $groups = [])
    {
        array_walk($groups, function (&$group) {
            $group = ['id' => $group];
        });
        return $this->update('users', $userId, [], [
            'groups' => $groups
        ]);
    }

    /**
     * @param array $relations
     * @return array
     */
    protected function parseRelationships($relations = [])
    {
        if (empty($relations)) {
            return $relations;
        }
        foreach ($relations as $type => &$relation) {
            $relation = [
                'data' => $relation
            ];
        }

        return $relations;
    }

}
