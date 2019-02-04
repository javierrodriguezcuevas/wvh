<?php

namespace CodeChallenge\Tests\Infrastructure\Ui\Api;

use CodeChallenge\Infrastructure\Ui\Api\HomepageController;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class HomepageControllerTest
 *
 * @covers \CodeChallenge\Infrastructure\Ui\Api\HomepageController
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class HomepageControllerTest extends TestCase
{
    /**
     * Tests it should return default message.
     */
    public function testItShouldReturnDefaultMessage()
    {
        $message = 'Welcome to the CodeChallenge API.';
        $data = [
            'message' => $message,
        ];

        /** @var SerializerInterface|MockObject $serializer */
        $serializer = $this->createMock(SerializerInterface::class);

        $serializer->expects(static::once())
            ->method('serialize')
            ->with(
                static::equalTo($data),
                static::equalTo('json')
            )
            ->willReturn(json_encode($data));

        $controller = new HomepageController($serializer);

        $response = $controller->index();

        static::assertEquals($message, json_decode($response->getContent())->message);
    }
}
