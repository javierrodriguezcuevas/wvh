<?php

namespace CodeChallenge\Tests\Application\Dto\Event;

use CodeChallenge\Application\Dto\Event\EventDetailDto;
use CodeChallenge\Domain\Model\Event\Event;
use CodeChallenge\Domain\Model\Event\Vo\EventId;
use CodeChallenge\Domain\Model\Event\Vo\Name;
use CodeChallenge\Domain\Model\Post\Post;
use CodeChallenge\Domain\Model\Post\Vo\Text;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class EventDetailDtoTest
 *
 * @covers \CodeChallenge\Application\Dto\Event\EventDetailDto
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class EventDetailDtoTest extends TestCase
{
    /**
     * Tests it should create dto successfully.
     */
    public function testItShouldCreateSuccessfully()
    {
        $eventId = '35f843b2-2625-11e9-ab14-d663bd873d93';
        $nameValue = 'foo';
        $textValue = 'bar';

        /** @var Event|MockObject $event */
        $event = $this->createMock(Event::class);

        $event->expects(static::once())
            ->method('id')
            ->willReturn($id = $this->createMock(EventId::class));

        $event->expects(static::once())
            ->method('name')
            ->willReturn($name = $this->createMock(Name::class));

        $event->expects(static::once())
            ->method('posts')
            ->willReturn($posts = [$post = $this->createMock(Post::class)]);

        $id->expects(static::once())
            ->method('value')
            ->willReturn($eventId);

        $name->expects(static::once())
            ->method('value')
            ->willReturn($nameValue);

        $post->expects(static::once())
            ->method('text')
            ->willReturn($text = $this->createMock(Text::class));

        $text->expects(static::once())
            ->method('value')
            ->willReturn($textValue);

        $eventDto = new EventDetailDto($event);

        static::assertSame($eventDto->id, $eventId);
        static::assertSame($eventDto->name, $nameValue);
        static::assertSame($eventDto->posts[0]->text, $textValue);
    }
}
