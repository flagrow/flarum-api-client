<?php

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