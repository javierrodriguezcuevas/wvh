<?php

namespace CodeChallenge\Tests\Application\Service\Event;

use CodeChallenge\Application\ApplicationException;
use CodeChallenge\Application\Service\Event\ListEventsService;
use CodeChallenge\Domain\Exception\ModelNotFoundException;
use CodeChallenge\Domain\Model\Event\Event;
use CodeChallenge\Domain\Model\Event\EventRepository;
use CodeChallenge\Domain\Model\Event\Vo\EventId;
use CodeChallenge\Domain\Model\Event\Vo\Name;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ListEventsServiceTest
 *
 * @covers \CodeChallenge\Application\Service\Event\ListEventsService
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class ListEventsServiceTest extends TestCase
{
    /** @var EventRepository|MockObject */
    private $eventRepository;

    /** @var ListEventsService */
    private $listEventService;

    /**
     * Sets up tests.
     */
    protected function setUp()
    {
        $this->eventRepository = $this->createMock(EventRepository::class);

        $this->listEventService = new ListEventsService($this->eventRepository);
    }

    /**
     * Tests it should create an event successfully.
     *
     * @throws ApplicationException
     */
    public function testItShouldCreateAnEventSuccessfully()
    {
        $latitude = 41.484590;
        $longitude = 2.175202;
        $radius = 42;

        $this->eventRepository
            ->expects(static::once())
            ->method('allByPlacePointInsideRadius')
            ->willReturn($events = [
                $event1 = $this->createMock(Event::class),
                $event2 = $this->createMock(Event::class),
            ]);

        $event1->expects(static::exactly(2))
            ->method('id')
            ->willReturn($eventIdVo = $this->createMock(EventId::class));
        $event1->expects(static::exactly(2))
            ->method('name')
            ->willReturn($nameVo = $this->createMock(Name::class));

        $event2->expects(static::exactly(2))
            ->method('id')
            ->willReturn($eventIdVo = $this->createMock(EventId::class));
        $event2->expects(static::exactly(2))
            ->method('name')
            ->willReturn($nameVo = $this->createMock(Name::class));

        $listEventsDto = $this->listEventService->execute($latitude, $longitude, $radius);

        /** @var Event|MockObject $event1 */
        /** @var Event|MockObject $event2 */
        static::assertCount(2, $listEventsDto->events);
        static::assertSame($listEventsDto->events[0]->id, $event1->id()->value());
        static::assertSame($listEventsDto->events[0]->name, $event1->name()->value());
        static::assertSame($listEventsDto->events[1]->id, $event2->id()->value());
        static::assertSame($listEventsDto->events[1]->name, $event2->name()->value());
    }

    /**
     * Tests it should throw ApplicationException when invalid latitude.
     *
     * @throws ApplicationException
     */
    public function testItShouldThrowApplicationExceptionWhenInvalidLatitude()
    {
        static::expectException(ApplicationException::class);

        $latitude = 141.484590;
        $longitude = 2.175202;
        $radius = 42;

        $this->listEventService->execute($latitude, $longitude, $radius);
    }

    /**
     * Tests it should throw ApplicationException when invalid longitude.
     *
     * @throws ApplicationException
     */
    public function testItShouldThrowApplicationExceptionWhenInvalidLongitude()
    {
        static::expectException(ApplicationException::class);

        $latitude = 41.484590;
        $longitude = 182.175202;
        $radius = 42;

        $this->listEventService->execute($latitude, $longitude, $radius);
    }

    /**
     * Tests it should throw ApplicationException when invalid radius.
     *
     * @throws ApplicationException
     */
    public function testItShouldThrowApplicationExceptionWhenInvalidRadius()
    {
        static::expectException(ApplicationException::class);

        $latitude = 41.484590;
        $longitude = 2.175202;
        $radius = 0;

        $this->listEventService->execute($latitude, $longitude, $radius);
    }
}
