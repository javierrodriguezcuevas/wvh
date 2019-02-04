<?php

namespace CodeChallenge\Tests\Domain\Model\Place\Vo;

use CodeChallenge\Domain\Model\Event\Vo\EventId;
use CodeChallenge\Domain\Model\Place\Place;
use CodeChallenge\Domain\Model\Place\Vo\Name;
use CodeChallenge\Domain\Model\Place\Vo\PlaceId;
use CodeChallenge\Domain\Model\Place\Vo\Point;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class PlaceTest
 *
 * @covers \CodeChallenge\Domain\Model\Place\Place
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class PlaceTest extends TestCase
{
    /** @var PlaceId|MockObject $placeId */
    private $placeId;

    /** @var Point|MockObject $point */
    private $point;

    /** @var Name|MockObject $name */
    private $name;

    /**
     * Sets up tests.
     */
    protected function setUp()
    {
        $this->placeId = $this->createMock(PlaceId::class);
        $this->point = $this->createMock(Point::class);
        $this->name = $this->createMock(Name::class);
    }

    /**
     * Tests it should create a valid place model.
     */
    public function testItShouldCreateAValidModelWithEmptyNameAndEmptyEvents()
    {
        $place = new Place($this->placeId, $this->point);

        static::assertSame($place->id(), $this->placeId);
        static::assertSame($place->point(), $this->point);
        static::assertEmpty($place->name()->value());
        static::assertEmpty($place->events());
    }

    /**
     * Tests it should create a valid place model with name.
     */
    public function testItShouldCreateAValidModelWithName()
    {
        $place = new Place($this->placeId, $this->point, $this->name);

        static::assertSame($place->id(), $this->placeId);
        static::assertSame($place->point(), $this->point);
        static::assertSame($place->name(), $this->name);
        static::assertEmpty($place->events());
    }


    /**
     * Tests it should add an event successfully.
     */
    public function testItShouldAddAnEventSuccessfully()
    {
        /** @var EventId|MockObject $eventId */
        $eventId = $this->createMock(EventId::class);
        $place = new Place($this->placeId, $this->point);

        $event = $place->addEvent($eventId);

        static::assertEquals($event, $place->events()[0]);
    }

    /**
     * Tests it should not add an event with the same id and return the event.
     */
    public function testItShouldNotAddAnEventWithSameId()
    {
        /** @var EventId|MockObject $eventId */
        $eventId = $this->createMock(EventId::class);
        $place = new Place($this->placeId, $this->point);

        $place->addEvent($eventId);
        $place->addEvent($eventId);

        static::assertCount(1, $place->events());
    }
}
