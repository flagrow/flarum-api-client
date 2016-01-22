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

class DiscussionTest extends PHPUnit_Framework_TestCase
{
    public function testGetWelcome()
    {
        $response = (new Client())->discussions(3);

        $this->assertEquals(3, array_get($response, 'data.id'));
    }
}
