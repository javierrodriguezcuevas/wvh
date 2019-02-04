<?php

namespace CodeChallenge\Tests\Application\Service\Event;

use CodeChallenge\Application\ApplicationException;
use CodeChallenge\Application\Service\Event\DetailEventService;
use CodeChallenge\Domain\Exception\ModelNotFoundException;
use CodeChallenge\Domain\Model\Event\Event;
use CodeChallenge\Domain\Model\Event\EventRepository;
use CodeChallenge\Domain\Model\Event\Vo\EventId;
use CodeChallenge\Domain\Model\Event\Vo\Name;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class DetailEventServiceTest
 *
 * @covers \CodeChallenge\Application\Service\Event\DetailEventService
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class DetailEventServiceTest extends TestCase
{
    /** @var EventRepository|MockObject */
    private $eventRepository;

    /** @var DetailEventService */
    private $detailEventService;

    /**
     * Sets up tests.
     */
    protected function setUp()
    {
        $this->eventRepository = $this->createMock(EventRepository::class);

        $this->detailEventService = new DetailEventService($this->eventRepository);
    }

    /**
     * Tests it should create an event successfully.
     *
     * @throws ApplicationException
     */
    public function testItShouldCreateAnEventSuccessfully()
    {
        $eventId = '35f843b2-2625-11e9-ab14-d663bd873d93';

        $this->eventRepository
            ->expects(static::once())
            ->method('oneById')
            ->willReturn($event = $this->createMock(Event::class));

        $event->expects(static::exactly(2))
            ->method('id')
            ->willReturn($eventIdVo = $this->createMock(EventId::class));
        $event->expects(static::exactly(2))
            ->method('name')
            ->willReturn($nameVo = $this->createMock(Name::class));

        $eventDto = $this->detailEventService->execute($eventId);

        /** @var Event|MockObject $event */
        static::assertSame($eventDto->id, $event->id()->value());
        static::assertSame($eventDto->name, $event->name()->value());
    }

    /**
     * Tests it should throw ApplicationException when invalid event id.
     *
     * @throws ApplicationException
     */
    public function testItShouldThrowApplicationExceptionWhenInvalidEventId()
    {
        static::expectException(ApplicationException::class);

        $eventId = '35f843b2-2625-11e9-ab14-663bd873d93';

        $this->detailEventService->execute($eventId);
    }

    /**
     * Tests it should throw ApplicationException when place not found.
     *
     * @throws ApplicationException
     */
    public function testItShouldThrowApplicationExceptionWhenPlaceNotFound()
    {
        static::expectException(ApplicationException::class);

        $eventId = '35f843b2-2625-11e9-ab14-d663bd873d93';

        $this->eventRepository
            ->expects(static::once())
            ->method('oneById')
            ->willThrowException(new ModelNotFoundException());

        $this->detailEventService->execute($eventId);
    }
}
