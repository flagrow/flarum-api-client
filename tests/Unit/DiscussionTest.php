<?php

namespace Flagrow\Flarum\Api\Tests\Unit;

use Flagrow\Flarum\Api\Flarum;
use Flagrow\Flarum\Api\Models\Discussion;
use Flagrow\Flarum\Api\Models\Model;
use Flagrow\Flarum\Api\Resource\Collection;
use Flagrow\Flarum\Api\Resource\Item;
use Flagrow\Flarum\Api\Tests\TestCase;

class DiscussionTest extends TestCase
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

        $this->assertEquals($discussion->id, $item->id, 'Requesting an existing discussion retrieves an incorrect result.');
        $this->assertEquals($discussion->type, $item->type, 'Requesting an existing discussion retrieves an incorrect resource type.');

        $cached = Flarum::getCache()->get($discussion->id, null, $discussion->type);

        $this->assertNotNull($cached, 'Discussion was not automatically persisted to global store.');
        $this->assertEquals($discussion->id, $cached->id, 'The wrong discussion was stored into cache.');

        $this->assertNotNull($discussion->title);
        $this->assertNotNull($discussion->slug);

        $this->assertNotNull($discussion->tags, 'The relation tags should be set on a discussion.');

        $this->assertNotNull($discussion->startPost, 'A discussion has a start post.');
    }

    /**
     * @test
     */
    public function createsDiscussions()
    {
        if (! $this->flarum->isAuthorized()) {
            $this->markTestSkipped('No authentication set.');
        }

        $discussion = new Discussion([
            'title' => 'Foo',
            'content' => 'Some testing content'
        ]);

        $resource = $discussion->save();

        $this->assertInstanceOf(Item::class, $resource);

        $this->assertEquals($discussion->title, $resource->title);
        $this->assertNotEmpty($resource->startPost);
        $this->assertEquals($discussion->content, $resource->startPost->content);

        return $resource;
    }

    /**
     * @test
     * @depends createsDiscussions
     * @param Item $resource
     */
    public function deletesDiscussions(Item $resource)
    {
        if (! $this->flarum->isAuthorized()) {
            $this->markTestSkipped('No authentication set.');
        }

        $discussion = Discussion::fromResource($resource);
        $model = Model::fromResource($resource);

        // Resolve the same instance.
        $this->assertEquals($discussion, $model);

        // See if we can delete things.
        $this->assertTrue($discussion->delete());
    }
}