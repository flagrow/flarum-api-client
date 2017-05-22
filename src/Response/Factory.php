<?php

namespace Flagrow\Flarum\Api\Response;

use Flagrow\Flarum\Api\Resource\Collection;
use Flagrow\Flarum\Api\Resource\Item;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;

class Factory
{
    public static function build(ResponseInterface $response)
    {
        $body = $response->getBody();

        if ($response->getStatusCode() === 204) {
            return true;
        }

        if (empty($body)) {
            return null;
        }

        $json = json_decode($body, true);

        $data = Arr::get($json, 'data');
        $included = Arr::get($json, 'included', []);

        // Sets included values to global store.
        if (!empty($included)) {
            (new Collection($included))->cache();
        }

        // Collection, paginated
        if ($data && !array_key_exists('type', $data)) {
            return (new Collection($data))->cache();
        }

        return (new Item($data))->cache();
    }
}