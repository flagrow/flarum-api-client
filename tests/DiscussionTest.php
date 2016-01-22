<?php

namespace Flagrow\Flarum\Api\Tests;

use PHPUnit_Framework_TestCase;
use Flagrow\Flarum\Api\Client;

class DiscussionTest extends PHPUnit_Framework_TestCase
{
    public function testGetWelcome()
    {
        var_dump((new Client())->discussions(3));
    }
}