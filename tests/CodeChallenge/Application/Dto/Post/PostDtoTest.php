<?php

namespace CodeChallenge\Tests\Application\Dto\Post;

use CodeChallenge\Application\Dto\Post\PostDto;
use CodeChallenge\Domain\Model\Post\Post;
use CodeChallenge\Domain\Model\Post\Vo\Text;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class PostDtoTest
 *
 * @covers \CodeChallenge\Application\Dto\Post\PostDto
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class PostDtoTest extends TestCase
{
    /**
     * Tests it should create dto successfully.
     */
    public function testItShouldCreateSuccessfully()
    {
        $textValue = 'foo';

        /** @var Post|MockObject $event */
        $post = $this->createMock(Post::class);

        $post->expects(static::once())
            ->method('text')
            ->willReturn($text = $this->createMock(Text::class));

        $text->expects(static::once())
            ->method('value')
            ->willReturn($textValue);

        $postDto = new PostDto($post);

        static::assertSame($postDto->text, $textValue);
    }
}
