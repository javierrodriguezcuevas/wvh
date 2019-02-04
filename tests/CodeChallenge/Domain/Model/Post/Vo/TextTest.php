<?php

namespace CodeChallenge\Tests\Domain\Model\Post\Vo;

use CodeChallenge\Domain\Exception\InvalidVoException;
use CodeChallenge\Domain\Model\Post\Vo\Text;
use PHPUnit\Framework\TestCase;

/**
 * Class TextTest
 *
 * @covers \CodeChallenge\Domain\Model\Post\Vo\Text
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class TextTest extends TestCase
{
    /**
     * Tests it should create a valid text model.
     *
     * @param string $value A valid name.
     *
     * @dataProvider validNameDataProvider
     *
     * @throws InvalidVoException
     */
    public function testItShouldCreateAValidModel(string $value)
    {
        $text = new Text($value);

        static::assertSame($text->value(), $value);
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

        new Text($value);
    }

    /**
     * Gets valid model data.
     *
     * @return array
     */
    public function validNameDataProvider(): array
    {
        return [
            ['foo'],
            [
                '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
                . '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
                . '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
                . '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
                . '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
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
            [''],
            [
                '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
                . '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
                . '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
                . '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
                . '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
                . '1'
            ],
        ];
    }
}
