<?php

namespace CodeChallenge\Tests\Domain\Model\Place\Vo;

use CodeChallenge\Domain\Exception\InvalidVoException;
use CodeChallenge\Domain\Model\Place\Vo\Point;
use PHPUnit\Framework\TestCase;

/**
 * Class PointTest
 *
 * @covers \CodeChallenge\Domain\Model\Place\Vo\Point
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class PointTest extends TestCase
{
    /**
     * Tests it should create a valid point model.
     *
     * @param float $latitude A valid latitude.
     * @param float $longitude A valid longitude.
     *
     * @dataProvider validPointDataProvider
     *
     * @throws InvalidVoException
     */
    public function testItShouldCreateAValidModel(float $latitude, float $longitude)
    {
        $point = new Point($latitude, $longitude);

        static::assertSame($point->latitude(), $latitude);
        static::assertSame($point->longitude(), $longitude);
    }

    /**
     * Tests it should throw InvalidVoException when invalid model data.
     *
     * @param float $latitude An invalid latitude.
     * @param float $longitude An invalid longitude.
     *
     * @dataProvider invalidPointDataProvider
     *
     * @throws InvalidVoException
     */
    public function testItShouldThrowInvalidVoExceptionWhenInvalidModelData(float $latitude, float $longitude)
    {
        static::expectException(InvalidVoException::class);

        new Point($latitude, $longitude);
    }

    /**
     * Gets valid model data.
     *
     * @return array
     */
    public function validPointDataProvider(): array
    {
        return [
            [+90, +180.0],
            [-90.00, -180.000],
            [+42.0000, -23.00000],
            [0, 0],
        ];
    }

    /**
     * Gets invalid model data.
     *
     * @return array
     */
    public function invalidPointDataProvider(): array
    {
        return [
            [90.000001, 180.000001],
            [-90.000001, +180.000001],
            [+42.0000000, -23.0000005],
        ];
    }
}
