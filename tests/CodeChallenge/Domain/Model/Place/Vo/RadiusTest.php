<?php

namespace CodeChallenge\Tests\Domain\Model\Place\Vo;

use CodeChallenge\Domain\Exception\InvalidVoException;
use CodeChallenge\Domain\Model\Place\Vo\Radius;
use PHPUnit\Framework\TestCase;

/**
 * Class RadiusTest
 *
 * @covers \CodeChallenge\Domain\Model\Place\Vo\Radius
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class RadiusTest extends TestCase
{
    /**
     * Tests it should create a valid radius model.
     *
     * @throws InvalidVoException
     */
    public function testItShouldCreateAValidModel()
    {
        $value = 42;
        $radius = new Radius($value);

        static::assertSame($value / 100, $radius->value());
    }

    /**
     * Tests it should throw InvalidVoException when invalid model data.
     *
     * @param int $value
     *
     * @throws InvalidVoException
     *
     * @dataProvider invalidRadiusDataProvider
     */
    public function testItShouldThrowInvalidVoExceptionWhenInvalidModelData(int $value)
    {
        static::expectException(InvalidVoException::class);

        new Radius($value);
    }

    /**
     * Gets invalid model data.
     *
     * @return array
     */
    public function invalidRadiusDataProvider(): array
    {
        return [
            [0],
            [-1],
        ];
    }
}
