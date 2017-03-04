<?php

namespace Flagrow\Flarum\Api\Tests\Unit;

use Flagrow\Flarum\Api\Flarum;
use Flagrow\Flarum\Api\Resource\Collection;
use Flagrow\Flarum\Api\Resource\Item;
use Flagrow\Flarum\Api\Tests\TestCase;

class FlarumTest extends TestCase
{
    /**
     * @test
     */
    public function frontpage()
    {
        /** @var Collection $collection */
        $collection = $this->flarum->discussions()->request();

        $this->assertTrue($collection instanceof Collection);

        $this->assertGreaterThan(0, $collection->collect()->count());

        return $collection;
    }

    /**
     * @test
     * @depends frontpage
     * @param Collection $collection
     */
    public function discussion(Collection $collection)
    {
        /** @var Item $discussion */
        $discussion = $collection->collect()->first();

        /** @var Item $item */
        $item = $this->flarum->discussions()->id($discussion->id)->request();

        $this->assertEquals($discussion->id, $item->id);
        $this->assertEquals($discussion->type, $item->type);

        $cached = Flarum::getCache()->get($discussion->id, null, $discussion->type);

        $this->assertNotNull($cached);
        $this->assertEquals($discussion->id, $cached->id);
    }
}