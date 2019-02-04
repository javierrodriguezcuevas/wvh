<?php

namespace CodeChallenge\Tests\Infrastructure\Ui\Api;

use CodeChallenge\Application\ApplicationException;
use CodeChallenge\Application\Dto\Event\EventDetailDto;
use CodeChallenge\Application\Dto\Event\EventDto;
use CodeChallenge\Application\Dto\Event\ListEventsDto;
use CodeChallenge\Application\Service\Event\CreateEventService;
use CodeChallenge\Application\Service\Event\DetailEventService;
use CodeChallenge\Application\Service\Event\ListEventsService;
use CodeChallenge\Infrastructure\Api\ApiProblemException;
use CodeChallenge\Infrastructure\Ui\Api\EventController;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EventControllerTest
 *
 * @covers \CodeChallenge\Infrastructure\Ui\Api\EventController
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class EventControllerTest extends TestCase
{
    /** @var SerializerInterface|MockObject $serializer */
    private $serializer;

    /** @var Request|MockObject $request */
    private $request;

    /** @var ParameterBag|MockObject $queryParameterBag */
    private $queryParameterBag;

    /** @var ParameterBag|MockObject $requestParameterBag */
    private $requestParameterBag;

    /** @var ListEventsService|MockObject $listEventsService */
    private $listEventsService;

    /** @var DetailEventService|MockObject $detailEventService */
    private $detailEventService;

    /** @var CreateEventService|MockObject $createEventService */
    private $createEventService;

    /** @var EventController $controller */
    private $controller;

    /**
     * Sets up tests.
     */
    protected function setUp()
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->request = $this->createMock(Request::class);
        $this->request->query = $this->queryParameterBag = $this->createMock(ParameterBag::class);
        $this->request->request = $this->requestParameterBag = $this->createMock(ParameterBag::class);
        $this->listEventsService = $this->createMock(ListEventsService::class);
        $this->detailEventService = $this->createMock(DetailEventService::class);
        $this->createEventService = $this->createMock(CreateEventService::class);

        $this->controller = new EventController($this->serializer);
    }

    /**
     * Tests it should return success response on list.
     */
    public function testItShouldReturnSuccessResponseOnList()
    {
        $latitude = 41.374991;
        $longitude = 2.149186;
        $radius = 10;

        $this->queryParameterBag
            ->expects(static::once())
            ->method('all')
            ->willReturn([
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius
            ]);

        $this->queryParameterBag
            ->expects(static::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $latitude,
                $longitude
            );

        $this->queryParameterBag
            ->expects(static::once())
            ->method('getInt')
            ->willReturn($radius);

        $this->listEventsService
            ->expects(static::once())
            ->method('execute')
            ->willReturn($listEventsDto = $this->createMock(ListEventsDto::class));

        $response = $this->controller->list($this->request, $this->listEventsService);

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests it should throw ApiProblemException on list when ApplicationException.
     */
    public function testItShouldThrowApiProblemExceptionOnListWhenApplicationException()
    {
        static::expectException(ApiProblemException::class);

        $latitude = 41.374991;
        $longitude = 2.149186;
        $radius = 10;

        $this->queryParameterBag
            ->expects(static::once())
            ->method('all')
            ->willReturn([
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius
            ]);

        $this->queryParameterBag
            ->expects(static::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $latitude,
                $longitude
            );

        $this->queryParameterBag
            ->expects(static::once())
            ->method('getInt')
            ->willReturn($radius);

        $this->listEventsService
            ->expects(static::once())
            ->method('execute')
            ->willThrowException(new ApplicationException());

        $this->controller->list($this->request, $this->listEventsService);
    }

    /**
     * Tests it should return success response on detail.
     */
    public function testItShouldReturnSuccessResponseOnDetail()
    {
        $eventId = '35f843b7-2625-11e9-ab14-d663bd873d93';

        $this->detailEventService
            ->expects(static::once())
            ->method('execute')
            ->with(static::equalTo($eventId))
            ->willReturn($eventDto = $this->createMock(EventDetailDto::class));

        $response = $this->controller->detail($this->detailEventService, $eventId);

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests it should throw ApiProblemException on detail when ApplicationException.
     */
    public function testItShouldThrowApiProblemExceptionOnDetailWhenApplicationException()
    {
        static::expectException(ApiProblemException::class);

        $eventId = '35f843b7-2625-11e9-ab14-d663bd873d93';

        $this->detailEventService
            ->expects(static::once())
            ->method('execute')
            ->with(static::equalTo($eventId))
            ->willThrowException(new ApplicationException());

        $this->controller->detail($this->detailEventService, $eventId);
    }

    /**
     * Tests it should return success response on create.
     */
    public function testItShouldReturnsSuccessResponseOnCreateFromOwnEndpoint()
    {
        $placeId = '35f843b7-2625-11e9-ab14-d663bd873d93';
        $name = 'foo';

        $this->requestParameterBag
            ->expects(static::once())
            ->method('all')
            ->willReturn([
                'placeId' => $placeId,
                'name' => $name,
            ]);

        $this->requestParameterBag
            ->expects(static::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $placeId,
                $name
            );

        $this->createEventService
            ->expects(static::once())
            ->method('execute')
            ->willReturn($eventDto = $this->createMock(EventDto::class));

        $this->controller->create($this->request, $this->createEventService);
    }

    /**
     * Tests it should throw ApiProblemException on create when ApplicationException.
     */
    public function testItShouldThrowApiProblemExceptionOnCreateWhenApplicationException()
    {
        static::expectException(ApiProblemException::class);

        $placeId = '35f843b7-2625-11e9-ab14-d663bd873d93';
        $name = 'foo';

        $this->requestParameterBag
            ->expects(static::once())
            ->method('all')
            ->willReturn([
                'placeId' => $placeId,
                'name' => $name,
            ]);

        $this->requestParameterBag
            ->expects(static::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $placeId,
                $name
            );

        $this->createEventService
            ->expects(static::once())
            ->method('execute')
            ->willThrowException(new ApplicationException());

        $this->controller->create($this->request, $this->createEventService);
    }



    /**
     * Tests it should return success response on create from places.
     */
    public function testItShouldReturnsSuccessResponseOnCreateFromPlaces()
    {
        $placeId = '35f843b7-2625-11e9-ab14-d663bd873d93';
        $name = 'foo';

        $this->requestParameterBag
            ->expects(static::once())
            ->method('set')
            ->with(
                static::equalTo('placeId'),
                static::equalTo($placeId)
            );

        $this->requestParameterBag
            ->expects(static::once())
            ->method('all')
            ->willReturn([
                'placeId' => $placeId,
                'name' => $name,
            ]);

        $this->requestParameterBag
            ->expects(static::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $placeId,
                $name
            );

        $this->createEventService
            ->expects(static::once())
            ->method('execute')
            ->willReturn($eventDto = $this->createMock(EventDto::class));

        $this->controller->createFromPlaces($this->request, $this->createEventService, $placeId);
    }
}
