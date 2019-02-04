<?php

namespace CodeChallenge\Tests\Domain\Model\Event;

use CodeChallenge\Domain\Model\Event\Event;
use CodeChallenge\Domain\Model\Event\Vo\EventId;
use CodeChallenge\Domain\Model\Event\Vo\Name;
use CodeChallenge\Domain\Model\Place\Place;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class EventTest
 *
 * @covers \CodeChallenge\Domain\Model\Event\Event
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class EventTest extends TestCase
{
    /** @var EventId|MockObject $eventId */
    private $eventId;

    /** @var Place|MockObject $place */
    private $place;

    /** @var Name|MockObject $name */
    private $name;

    /**
     * Sets up tests.
     */
    protected function setUp()
    {
        $this->eventId = $this->createMock(EventId::class);
        $this->place = $this->createMock(Place::class);
        $this->name = $this->createMock(Name::class);
    }

    /**
     * Tests it should create a valid place model.
     *
     * @throws \CodeChallenge\Domain\Exception\InvalidVoException
     */
    public function testItShouldCreateAValidModelWithEmptyNameAndEmptyPosts()
    {
        $event = new Event($this->eventId, $this->place);

        static::assertSame($event->id(), $this->eventId);
        static::assertSame($event->place(), $this->place);
        static::assertEmpty($event->name()->value());
        static::assertEmpty($event->posts());
    }

    /**
     * Tests it should create a valid place model with name.
     *
     * @throws \CodeChallenge\Domain\Exception\InvalidVoException
     */
    public function testItShouldCreateAValidModelWithName()
    {
        $event = new Event($this->eventId, $this->place, $this->name);

        static::assertSame($event->id(), $this->eventId);
        static::assertSame($event->place(), $this->place);
        static::assertSame($event->name(), $this->name);
    }
}
