<?php

namespace CodeChallenge\Tests\Application\Dto\Event;

use CodeChallenge\Application\Dto\Event\ListEventsDto;
use CodeChallenge\Domain\Model\Event\Event;
use CodeChallenge\Domain\Model\Event\Vo\EventId;
use CodeChallenge\Domain\Model\Event\Vo\Name;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ListEventsDtoTest
 *
 * @covers \CodeChallenge\Application\Dto\Event\ListEventsDto
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class ListEventsDtoTest extends TestCase
{
    /**
     * Tests it should create dto successfully.
     */
    public function testItShouldCreateSuccessfully()
    {
        $eventId = '35f843b2-2625-11e9-ab14-d663bd873d93';
        $nameValue = 'foo';

        /** @var Event|MockObject $event */
        $events = [$event = $this->createMock(Event::class)];

        $event->expects(static::once())
            ->method('id')
            ->willReturn($id = $this->createMock(EventId::class));

        $event->expects(static::once())
            ->method('name')
            ->willReturn($name = $this->createMock(Name::class));

        $id->expects(static::once())
            ->method('value')
            ->willReturn($eventId);

        $name->expects(static::once())
            ->method('value')
            ->willReturn($nameValue);

        $listEventsDto = new ListEventsDto($events);

        static::assertSame($listEventsDto->events[0]->id, $eventId);
        static::assertSame($listEventsDto->events[0]->name, $nameValue);
    }
}
