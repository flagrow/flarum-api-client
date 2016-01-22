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

use PHPUnit_Framework_TestCase;
use Flagrow\Flarum\Api\Client;

class TagsTest extends PHPUnit_Framework_TestCase
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
        $response = (new Client(
            'http://flarum.app/api/',
            'T(soY(ue4@Ku$vW9Wp7gBbci+Z+T#JHj>9]-!z}s; userId=1'
        ))->createTag('test_' . mt_rand(100, 999), 'test_' . mt_rand(100, 999));
        $this->assertNotNull(array_get($response, 'data.attributes'));
    }
}
