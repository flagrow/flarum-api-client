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
use PHPUnit_Framework_TestCase;

class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    protected $client;

    protected function setUp()
    {
        $this->client = new Client(
            'http://flarum.app/api/',
            'T(soY(ue4@Ku$vW9Wp7gBbci+Z+T#JHj>9]-!z}s; userId=1'
        );
    }

    protected function tearDown()
    {
        unset($this->client);
    }
}