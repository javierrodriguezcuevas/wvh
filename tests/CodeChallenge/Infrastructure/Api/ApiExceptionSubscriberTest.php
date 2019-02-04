<?php

namespace CodeChallenge\Tests\Infrastructure\Api;

use CodeChallenge\Infrastructure\Api\ApiExceptionSubscriber;
use CodeChallenge\Infrastructure\Api\ApiProblem;
use CodeChallenge\Infrastructure\Api\ApiProblemException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ApiExceptionSubscriberTest
 *
 * @covers \CodeChallenge\Infrastructure\Api\ApiExceptionSubscriber
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class ApiExceptionSubscriberTest extends TestCase
{
    /** @var GetResponseForExceptionEvent|MockObject $event */
    private $event;

    /** @var ApiExceptionSubscriber */
    private $subscriber;

    /**
     * Sets up tests.
     */
    protected function setUp()
    {
        $this->event = $this->createMock(GetResponseForExceptionEvent::class);
        $this->subscriber = new ApiExceptionSubscriber();
    }

    /**
     * Tests it should create successfully.
     */
    public function testItShouldCreateSuccessfully()
    {
        $statusCode = 200;

        $this->event->expects(static::once())
            ->method('getException')
            ->willReturn($apiProblemException = $this->createMock(ApiProblemException::class));

        $this->event->expects(static::once())
            ->method('setResponse');

        $apiProblemException->expects(static::once())
            ->method('getApiProblem')
            ->willReturn($apiProblem = $this->createMock(ApiProblem::class));

        $apiProblem->expects(static::once())
            ->method('toArray')
            ->willReturn([]);

        $apiProblem->expects(static::once())
            ->method('getStatusCode')
            ->willReturn($statusCode);

        $this->subscriber->onKernelException($this->event);
    }

    /**
     * Tests it should return when no ApiProblemException.
     */
    public function testItShouldReturnWhenApiProblemException()
    {
        $this->event->expects(static::once())
            ->method('getException')
            ->willReturn($exception = $this->createMock(\InvalidArgumentException::class));

        $this->subscriber->onKernelException($this->event);
    }

    /**
     * Tests it should return subscribed event.
     */
    public function testItShouldReturnSubscribedEvent()
    {
        $expected = [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
        $events = $this->subscriber::getSubscribedEvents();

        static::assertSame($expected, $events);
    }
}
