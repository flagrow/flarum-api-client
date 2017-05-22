<?php

namespace Flagrow\Flarum\Api\Tests;

use Flagrow\Flarum\Api\Flarum;
use Flagrow\Flarum\Api\Models\Model;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Flarum
     */
    protected $flarum;

    protected function setUp()
    {
        parent::setUp();

        $token = getenv('FLARUM_TOKEN');

        $this->flarum = new Flarum(
            getenv('FLARUM_HOST') ?? 'https://discuss.flarum.org',
            $token ? compact('token') : []
        );

        Model::setDispatcher($this->flarum);
    }
}