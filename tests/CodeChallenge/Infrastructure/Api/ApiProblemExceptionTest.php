<?php

namespace CodeChallenge\Tests\Infrastructure\Api;

use CodeChallenge\Infrastructure\Api\ApiProblem;
use CodeChallenge\Infrastructure\Api\ApiProblemException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiProblemExceptionTest
 *
 * @covers \CodeChallenge\Infrastructure\Api\ApiProblemException
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class ApiProblemExceptionTest extends TestCase
{
    /**
     * Tests it should create successfully.
     */
    public function testItShouldCreateSuccessfully()
    {
        /** @var ApiProblem|MockObject $apiProblem */
        $apiProblem = $this->createMock(ApiProblem::class);

        $apiProblemException = new ApiProblemException($apiProblem);

        static::assertSame($apiProblem, $apiProblemException->getApiProblem());
    }
}
