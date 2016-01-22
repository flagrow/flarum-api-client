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

namespace Flagrow\Flarum\Api\Tests;

use Flagrow\Flarum\Api\Client;

class TagsTest extends TestCase
{
    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testCreation()
    {
        $response = (new Client())->createTag('test', 'newTest');
    }

    public function testAuthorizedCreation()
    {
        $response = $this->client->createTag('test_' . mt_rand(100, 999), 'test_' . mt_rand(100, 999));
        $this->assertNotNull(array_get($response, 'data.attributes'));
    }
}
