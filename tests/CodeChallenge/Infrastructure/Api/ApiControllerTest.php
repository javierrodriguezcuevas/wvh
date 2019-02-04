<?php

namespace CodeChallenge\Tests\Infrastructure\Api;

use CodeChallenge\Infrastructure\Api\ApiProblemException;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiControllerTest
 *
 * @covers \CodeChallenge\Infrastructure\Api\ApiController
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class ApiControllerTest extends TestCase
{
    /** @var SerializerInterface|MockObject */
    private $serializer;

    /** @var PhpUnitApiController */
    private $controller;

    /**
     * Sets up tests.
     */
    protected function setUp()
    {
        $this->serializer = $this->createMock(SerializerInterface::class);

        $this->controller = new PhpUnitApiController($this->serializer);
    }

    /**
     * Tests it should return a json response.
     */
    public function testItShouldReturnJsonResponse()
    {
        $response = $this->controller->createApiResponseTest();

        static::assertSame(200, $response->getStatusCode());
        static::assertSame('application/json', $response->headers->get('content-type'));
    }

    /**
     * Tests it should return true when parameters exists.
     */
    public function testItShouldReturnTrueWhenParametersExists()
    {
        $required = ['foo'];
        $parameters = ['foo' => '42', 'bar' => '23'];

        static::assertTrue($this->controller->assertRequiredParametersTest($required, $parameters));
    }

    /**
     * Tests it should throw ApiProblemException when missing parameters.
     */
    public function testItShouldThrowApiProblemExceptionWhenMissingParameters()
    {
        static::expectException(ApiProblemException::class);

        $required = ['foo'];
        $parameters = ['bar' => '42', 'doe' => '23'];

        $this->controller->assertRequiredParametersTest($required, $parameters);
    }
}
