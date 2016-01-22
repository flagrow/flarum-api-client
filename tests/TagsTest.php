<?php

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
}