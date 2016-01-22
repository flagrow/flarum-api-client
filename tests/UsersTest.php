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

class UsersTest extends TestCase
{


    public function testGetFirst()
    {
        $response = $this->client->load('users', 1);

        $this->assertEquals(1, array_get($response, 'data.id'));
    }

    public function testCreate()
    {
        $response = $this->client->registerUser('test_' . mt_rand(100, 999), 'test_' . mt_rand(100, 999), mt_rand(100, 999) . '@example.com');
        $this->assertNotNull(array_get($response, 'data.attributes'));
    }

    public function testSetUserGroups()
    {
        $response = $this->client->setUserGroups(2, [4]);
        dd($response);
    }
}
