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

class UsersTest extends PHPUnit_Framework_TestCase
{
    public function testGetFirst()
    {
        $response = (new Client())->load('users', 1);

        $this->assertEquals(1, array_get($response, 'data.id'));
    }

    public function testCreate()
    {
        $response = (new Client(
            'http://flarum.app/api/',
            'T(soY(ue4@Ku$vW9Wp7gBbci+Z+T#JHj>9]-!z}s; userId=1'
        ))->registerUser('test_' . mt_rand(100, 999), 'test_' . mt_rand(100, 999), 'some@example.com');
        $this->assertNotNull(array_get($response, 'data.attributes'));
    }
}
