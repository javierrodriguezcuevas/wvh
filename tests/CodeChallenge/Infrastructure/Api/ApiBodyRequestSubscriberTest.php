<?php

namespace CodeChallenge\Tests\Infrastructure\Api;

use CodeChallenge\Infrastructure\Api\ApiBodyRequestSubscriber;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ApiBodyRequestSubscriberTest
 *
 * @covers \CodeChallenge\Infrastructure\Api\ApiBodyRequestSubscriber
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class ApiBodyRequestSubscriberTest extends TestCase
{
    /** @var GetResponseEvent|MockObject $event */
    private $event;

    /** @var ApiBodyRequestSubscriber */
    private $subscriber;

    /**
     * Sets up tests.
     */
    protected function setUp()
    {
        $this->event = $this->createMock(GetResponseEvent::class);
        $this->subscriber = new ApiBodyRequestSubscriber();
    }

    /**
     * Tests it should create successfully.
     */
    public function testItShouldCreateSuccessfully()
    {
        $this->event->expects(static::once())
            ->method('isMasterRequest')
            ->willReturn(true);

        $this->event->expects(static::once())
            ->method('getRequest')
            ->willReturn($request = $this->createMock(Request::class));

        $request->expects(static::once())
            ->method('getMethod')
            ->willReturn('POST');

        $parameterBag = $this->createMock(ParameterBag::class);

        $parameterBag->expects(static::once())
            ->method('get')
            ->with(static::equalTo('content-type'))
            ->willReturn('application/json');

        $parameterBag->expects(static::once())
            ->method('replace');

        $request->headers = $parameterBag;
        $request->request = $parameterBag;

        $request->expects(static::once())
            ->method('getContent')
            ->willReturn(json_encode(['foo' => 'bar']));

        $this->subscriber->onKernelRequest($this->event);
    }

    /**
     * Tests it should throw exception when invalid content.
     */
    public function testItShouldThrowExceptionWhenInvalidContent()
    {
        static::expectException(HttpException::class);

        $this->event->expects(static::once())
            ->method('isMasterRequest')
            ->willReturn(true);

        $this->event->expects(static::once())
            ->method('getRequest')
            ->willReturn($request = $this->createMock(Request::class));

        $request->expects(static::once())
            ->method('getMethod')
            ->willReturn('POST');

        $parameterBag = $this->createMock(ParameterBag::class);

        $parameterBag->expects(static::once())
            ->method('get')
            ->with(static::equalTo('content-type'))
            ->willReturn('application/json');

        $request->headers = $parameterBag;

        $request->expects(static::once())
            ->method('getContent')
            ->willReturn('');

        $this->subscriber->onKernelRequest($this->event);
    }

    /**
     * Tests it should return when no master request.
     */
    public function testItShouldReturnWhenNoMasterRequest()
    {
        $this->event->expects(static::once())
            ->method('isMasterRequest')
            ->willReturn(false);

        $this->subscriber->onKernelRequest($this->event);
    }

    /**
     * Tests it should return when no decode method.
     */
    public function testItShouldReturnWhenNoDecodeMethod()
    {
        $this->event->expects(static::once())
            ->method('isMasterRequest')
            ->willReturn(true);

        $this->event->expects(static::once())
            ->method('getRequest')
            ->willReturn($request = $this->createMock(Request::class));

        $request->expects(static::once())
            ->method('getMethod')
            ->willReturn('GET');

        $this->subscriber->onKernelRequest($this->event);
    }

    /**
     * Tests it should return subscribed event.
     */
    public function testItShouldReturnSubscribedEvent()
    {
        $expected = [
            KernelEvents::REQUEST => ['onKernelRequest', 10],
        ];
        $events = $this->subscriber::getSubscribedEvents();

        static::assertSame($expected, $events);
    }
}
