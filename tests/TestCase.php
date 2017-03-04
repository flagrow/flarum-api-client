<?php

namespace Flagrow\Flarum\Api\Tests;

use Flagrow\Flarum\Api\Flarum;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Flarum
     */
    protected $flarum;

    protected function setUp()
    {
        parent::setUp();

        $this->flarum = new Flarum('https://discuss.flarum.org');
    }
}