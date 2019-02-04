<?php

namespace CodeChallenge\Tests\Application\Service\Event;

use CodeChallenge\Application\ApplicationException;
use CodeChallenge\Application\Service\Event\CreateEventService;
use CodeChallenge\Domain\Exception\ModelNotFoundException;
use CodeChallenge\Domain\Model\Event\Event;
use CodeChallenge\Domain\Model\Event\EventRepository;
use CodeChallenge\Domain\Model\Event\Vo\EventId;
use CodeChallenge\Domain\Model\Event\Vo\Name;
use CodeChallenge\Domain\Model\Place\Place;
use CodeChallenge\Domain\Model\Place\PlaceRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class CreateEventServiceTest
 *
 * @covers \CodeChallenge\Application\Service\Event\CreateEventService
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class CreateEventServiceTest extends TestCase
{
    /** @var EventRepository|MockObject */
    private $eventRepository;

    /** @var PlaceRepository|MockObject */
    private $placeRepository;

    /** @var CreateEventService */
    private $createEventService;

    /**
     * Sets up tests.
     */
    protected function setUp()
    {
        $this->eventRepository = $this->createMock(EventRepository::class);
        $this->placeRepository = $this->createMock(PlaceRepository::class);

        $this->createEventService = new CreateEventService($this->placeRepository, $this->eventRepository);
    }

    /**
     * Tests it should create an event successfully.
     *
     * @throws ApplicationException
     */
    public function testItShouldCreateAnEventSuccessfully()
    {
        $placeId = '35f843b2-2625-11e9-ab14-d663bd873d93';
        $name = 'foo';

        $this->placeRepository
            ->expects(static::once())
            ->method('oneById')
            ->willReturn($place = $this->createMock(Place::class));

        $this->eventRepository
            ->expects(static::once())
            ->method('nextIdentity')
            ->willReturn($eventId = $this->createMock(EventId::class));

        $place->expects(static::once())
            ->method('addEvent')
            ->willReturn($event = $this->createMock(Event::class));

        $this->placeRepository
            ->expects(static::once())
            ->method('save')
            ->with(static::equalTo($place));

        $event->expects(static::exactly(2))
            ->method('id')
            ->willReturn($eventId);
        $event->expects(static::exactly(2))
            ->method('name')
            ->willReturn($nameVo = $this->createMock(Name::class));

        $eventDto = $this->createEventService->execute($placeId, $name);

        /** @var Event|MockObject $event */
        static::assertSame($eventDto->id, $event->id()->value());
        static::assertSame($eventDto->name, $event->name()->value());
    }

    /**
     * Tests it should throw ApplicationException when invalid name.
     *
     * @throws ApplicationException
     */
    public function testItShouldThrowApplicationExceptionWhenInvalidName()
    {
        static::expectException(ApplicationException::class);

        $placeId = '35f843b2-2625-11e9-ab14-d663bd873d93';
        $name = '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
            . '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
            . '123456789012345678901234567890123456789012345678901';

        $this->createEventService->execute($placeId, $name);
    }

    /**
     * Tests it should throw ApplicationException when invalid place id.
     *
     * @throws ApplicationException
     */
    public function testItShouldThrowApplicationExceptionWhenInvalidPlaceId()
    {
        static::expectException(ApplicationException::class);

        $placeId = '35f843b2-2625-11e9-ab14-663bd873d93';
        $name = 'foo';

        $this->createEventService->execute($placeId, $name);
    }

    /**
     * Tests it should throw ApplicationException when place not found.
     *
     * @throws ApplicationException
     */
    public function testItShouldThrowApplicationExceptionWhenPlaceNotFound()
    {
        static::expectException(ApplicationException::class);

        $placeId = '35f843b2-2625-11e9-ab14-d663bd873d93';
        $name = 'foo';

        $this->placeRepository
            ->expects(static::once())
            ->method('oneById')
            ->willThrowException(new ModelNotFoundException());

        $this->createEventService->execute($placeId, $name);
    }
}
