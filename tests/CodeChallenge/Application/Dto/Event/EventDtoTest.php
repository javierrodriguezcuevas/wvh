<?php

namespace CodeChallenge\Tests\Application\Dto\Event;

use CodeChallenge\Application\Dto\Event\EventDto;
use CodeChallenge\Domain\Model\Event\Event;
use CodeChallenge\Domain\Model\Event\Vo\EventId;
use CodeChallenge\Domain\Model\Event\Vo\Name;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class EventDtoTest
 *
 * @covers \CodeChallenge\Application\Dto\Event\EventDto
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class EventDtoTest extends TestCase
{
    /**
     * Tests it should create dto successfully.
     */
    public function testItShouldCreateSuccessfully()
    {
        $eventId = '35f843b2-2625-11e9-ab14-d663bd873d93';
        $nameValue = 'foo';

        /** @var Event|MockObject $event */
        $event = $this->createMock(Event::class);

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

        $eventDto = new EventDto($event);

        static::assertSame($eventDto->id, $eventId);
        static::assertSame($eventDto->name, $nameValue);
    }
}
