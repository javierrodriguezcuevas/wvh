<?php

namespace CodeChallenge\Tests\Infrastructure\Api;

use CodeChallenge\Infrastructure\Api\ApiProblem;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiProblemTest
 *
 * @covers \CodeChallenge\Infrastructure\Api\ApiProblem
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class ApiProblemTest extends TestCase
{
    /**
     * Tests it should create successfully.
     */
    public function testItShouldCreateSuccessfully()
    {
        $statusCode = 400;
        $type = ApiProblem::TYPE_MISSING_REQUIRE_PARAMETERS;
        $expectedTitle = 'Missing required parameters';
        $expectedArray = [
            'status' => $statusCode,
            'type' => $type,
            'title' => $expectedTitle,
        ];

        $apiProblem = new ApiProblem($statusCode, $type);

        static::assertSame($statusCode, $apiProblem->getStatusCode());
        static::assertSame($expectedTitle, $apiProblem->getTitle());
        static::assertSame($expectedArray, $apiProblem->toArray());
    }

    /**
     * Tests it should throw InvalidArgumentException when invalid type.
     */
    public function testItShouldThrowInvalidArgumentExceptionWhenInvalidType()
    {
        static::expectException(\InvalidArgumentException::class);

        $statusCode = 400;
        $type = 'foo';

        new ApiProblem($statusCode, $type);
    }
}
