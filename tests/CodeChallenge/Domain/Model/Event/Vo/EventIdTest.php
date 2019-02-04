<?php

namespace CodeChallenge\Tests\Domain\Model\Event\Vo;

use CodeChallenge\Domain\Exception\InvalidVoException;
use CodeChallenge\Domain\Model\Event\Vo\EventId;
use PHPUnit\Framework\TestCase;

/**
 * Class EventIdTest
 *
 * @covers \CodeChallenge\Domain\Model\Event\Vo\EventId
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class EventIdTest extends TestCase
{
    /**
     * Tests it should create a valid event id model.
     *
     * @param string $id A valid uuid.
     *
     * @dataProvider validEventIdDataProvider
     *
     * @throws InvalidVoException
     */
    public function testItShouldCreateAValidModel(string $id)
    {
        $eventId = new EventId($id);

        static::assertSame($eventId->value(), $id);
        static::assertSame((string) $eventId, $id);
    }

    /**
     * Tests it should throw InvalidVoException when invalid model data.
     *
     * @param string $id An invalid uuid.
     *
     * @dataProvider invalidEventIdDataProvider
     *
     * @throws InvalidVoException
     */
    public function testItShouldThrowInvalidVoExceptionWhenInvalidModelData(string $id)
    {
        static::expectException(InvalidVoException::class);

        new EventId($id);
    }

    /**
     * Gets valid model data.
     *
     * @return array
     */
    public function validEventIdDataProvider(): array
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
    public function invalidEventIdDataProvider(): array
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
