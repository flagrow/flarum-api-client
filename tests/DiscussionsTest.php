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

class DiscussionsTest extends TestCase
{
    public function testGetWelcome()
    {
        $response = (new Client())->discussions(3);

        $this->assertEquals(3, array_get($response, 'data.id'));
    }
}
