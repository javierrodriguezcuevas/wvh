<?php

namespace CodeChallenge\Tests\Domain\Model\Place\Vo;

use CodeChallenge\Domain\Exception\InvalidVoException;
use CodeChallenge\Domain\Model\Place\Vo\Name;
use PHPUnit\Framework\TestCase;

/**
 * Class NameTest
 *
 * @covers \CodeChallenge\Domain\Model\Place\Vo\Name
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class NameTest extends TestCase
{
    /**
     * Tests it should create a valid name model.
     *
     * @param string $value A valid name.
     *
     * @dataProvider validNameDataProvider
     *
     * @throws InvalidVoException
     */
    public function testItShouldCreateAValidModel(string $value)
    {
        $name = new Name($value);

        static::assertSame($name->value(), $value);
    }

    /**
     * Tests it should throw InvalidVoException when invalid model data.
     *
     * @param string $value An inalid name.
     *
     * @dataProvider invalidNameDataProvider
     *
     * @throws InvalidVoException
     */
    public function testItShouldThrowInvalidVoExceptionWhenInvalidModelData(string $value)
    {
        static::expectException(InvalidVoException::class);

        new Name($value);
    }

    /**
     * Gets valid model data.
     *
     * @return array
     */
    public function validNameDataProvider(): array
    {
        return [
            [''],
            ['foo'],
            [
                '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
                . '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
                . '12345678901234567890123456789012345678901234567890'
            ],
        ];
    }

    /**
     * Gets invalid model data.
     *
     * @return array
     */
    public function invalidNameDataProvider(): array
    {
        return [
            [
                '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
                . '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
                . '123456789012345678901234567890123456789012345678901'
            ],
        ];
    }
}
