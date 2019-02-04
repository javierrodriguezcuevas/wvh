<?php

namespace CodeChallenge\Tests\Domain\Model\Post\Vo;

use CodeChallenge\Domain\Model\Event\Event;
use CodeChallenge\Domain\Model\Post\Post;
use CodeChallenge\Domain\Model\Post\Vo\PostId;
use CodeChallenge\Domain\Model\Post\Vo\Text;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class PostTest
 *
 * @covers \CodeChallenge\Domain\Model\Post\Post
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class PostTest extends TestCase
{
    /** @var PostId|MockObject $postId */
    private $postId;

    /** @var Event|MockObject $event */
    private $event;

    /** @var Text|MockObject $text */
    private $text;

    /**
     * Sets up tests.
     */
    protected function setUp()
    {
        $this->postId = $this->createMock(PostId::class);
        $this->event = $this->createMock(Event::class);
        $this->text = $this->createMock(Text::class);
    }

    /**
     * Tests it should create a valid place model.
     */
    public function testItShouldCreateAValidModel()
    {
        $place = new Post($this->postId, $this->event, $this->text);

        static::assertSame($place->id(), $this->postId);
        static::assertSame($place->text(), $this->text);
    }
}
