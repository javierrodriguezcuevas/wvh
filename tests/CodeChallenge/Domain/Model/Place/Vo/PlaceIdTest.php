<?php

namespace CodeChallenge\Tests\Domain\Model\Place\Vo;

use CodeChallenge\Domain\Exception\InvalidVoException;
use CodeChallenge\Domain\Model\Place\Vo\PlaceId;
use PHPUnit\Framework\TestCase;

/**
 * Class PlaceIdTest
 *
 * @covers \CodeChallenge\Domain\Model\Place\Vo\PlaceId
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class PlaceIdTest extends TestCase
{
    /**
     * Tests it should create a valid place id model.
     *
     * @param string $id A valid uuid.
     *
     * @dataProvider validPlaceIdDataProvider
     *
     * @throws InvalidVoException
     */
    public function testItShouldCreateAValidModel(string $id)
    {
        $placeId = new PlaceId($id);

        static::assertSame($placeId->value(), $id);
        static::assertSame((string) $placeId, $id);
    }

    /**
     * Tests it should throw InvalidVoException when invalid model data.
     *
     * @param string $id An invalid uuid.
     *
     * @dataProvider invalidPlaceIdDataProvider
     *
     * @throws InvalidVoException
     */
    public function testItShouldThrowInvalidVoExceptionWhenInvalidModelData(string $id)
    {
        static::expectException(InvalidVoException::class);

        new PlaceId($id);
    }

    /**
     * Gets valid model data.
     *
     * @return array
     */
    public function validPlaceIdDataProvider(): array
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
    public function invalidPlaceIdDataProvider(): array
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
