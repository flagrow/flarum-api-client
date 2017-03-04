<?php

namespace Flagrow\Flarum\Api\Response;

use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;

class Factory
{
    public static function build(ResponseInterface $response)
    {
        $body = $response->getBody();

        if (empty($body)) {
            return null;
        }

        $json = json_decode($body, true);

        $data = Arr::get($json, 'data');

        // Collection, paginated
        if (is_array($data)) {

        }
    }
}