<?php

namespace CodeChallenge\Tests\Domain\Model\Post\Vo;

use CodeChallenge\Domain\Exception\InvalidVoException;
use CodeChallenge\Domain\Model\Post\Vo\PostId;
use PHPUnit\Framework\TestCase;

/**
 * Class PostIdTest
 *
 * @covers \CodeChallenge\Domain\Model\Post\Vo\PostId
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class PostIdTest extends TestCase
{
    /**
     * Tests it should create a valid post id model.
     *
     * @param string $id A valid uuid.
     *
     * @dataProvider validPostIdDataProvider
     *
     * @throws InvalidVoException
     */
    public function testItShouldCreateAValidModel(string $id)
    {
        $postId = new PostId($id);

        static::assertSame($postId->value(), $id);
        static::assertSame((string) $postId, $id);
    }

    /**
     * Tests it should throw InvalidVoException when invalid model data.
     *
     * @param string $id An invalid uuid.
     *
     * @dataProvider invalidPostIdDataProvider
     *
     * @throws InvalidVoException
     */
    public function testItShouldThrowInvalidVoExceptionWhenInvalidModelData(string $id)
    {
        static::expectException(InvalidVoException::class);

        new PostId($id);
    }

    /**
     * Gets valid model data.
     *
     * @return array
     */
    public function validPostIdDataProvider(): array
    {
        return [
            ['35f843b2-2625-11e9-ab14-d663bd873d93'],
            ['5e9ac4c0-2625-11e9-b56e-0800200c9a66'],
            ['32f4aa09-8da6-1db5-9b6c-937ae0f84ec9'],
        ];
    }

    /**
     * Gets invalid model data.
     *
     * @return array
     */
    public function invalidPostIdDataProvider(): array
    {
        return [
            ['00000000-0000-0000-0000-000000000000'],
            ['4f67726a-ed72-2b65-a3e9-733ac8bdf376'], // uuid v2
            ['4f67726a-ed72-3b65-a3e9-733ac8bdf376'], // uuid v3
            ['4f67726a-ed72-4b65-a3e9-733ac8bdf376'], // uuid v4
            ['asd-as-qwe-c'],
        ];
    }
}
