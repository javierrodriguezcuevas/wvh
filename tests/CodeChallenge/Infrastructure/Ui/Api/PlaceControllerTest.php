<?php

namespace CodeChallenge\Tests\Infrastructure\Ui\Api;

use CodeChallenge\Application\ApplicationException;
use CodeChallenge\Application\Dto\Place\PlaceDto;
use CodeChallenge\Application\Service\Place\CreatePlaceService;
use CodeChallenge\Infrastructure\Api\ApiProblemException;
use CodeChallenge\Infrastructure\Ui\Api\PlaceController;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PlaceControllerTest
 *
 * @covers \CodeChallenge\Infrastructure\Ui\Api\PlaceController
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class PlaceControllerTest extends TestCase
{
    /** @var SerializerInterface|MockObject $serializer */
    private $serializer;

    /** @var Request|MockObject $request */
    private $request;

    /** @var ParameterBag|MockObject $request */
    private $parameterBag;

    /** @var CreatePlaceService|MockObject $createPlaceService */
    private $createPlaceService;

    /** @var PlaceController $controller */
    private $controller;

    /**
     * Sets up tests.
     */
    protected function setUp()
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->request = $this->createMock(Request::class);
        $this->request->request = $this->parameterBag = $this->createMock(ParameterBag::class);
        $this->createPlaceService = $this->createMock(CreatePlaceService::class);

        $this->controller = new PlaceController($this->serializer);
    }

    /**
     * Tests it should return default message.
     */
    public function testItShouldReturnSuccessResponse()
    {
        $latitude = 41.374991;
        $longitude = 2.149186;
        $name = 'foo';

        $this->parameterBag
            ->expects(static::once())
            ->method('all')
            ->willReturn([
                'latitude' => $latitude,
                'longitude' => $longitude,
                'name' => $name
            ]);

        $this->parameterBag
            ->expects(static::exactly(3))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $latitude,
                $longitude,
                $name
            );

        $this->createPlaceService
            ->expects(static::once())
            ->method('execute')
            ->willReturn($placeDto = $this->createMock(PlaceDto::class));

        $response = $this->controller->create($this->request, $this->createPlaceService);

        static::assertSame(201, $response->getStatusCode());
    }

    /**
     * Tests it should throw ApiProblemException when ApplicationException.
     */
    public function testItShouldThrowApiProblemExceptionWhenApplicationException()
    {
        static::expectException(ApiProblemException::class);

        $latitude = 41.374991;
        $longitude = 2.149186;
        $name = 'foo';

        $this->parameterBag
            ->expects(static::once())
            ->method('all')
            ->willReturn([
                'latitude' => $latitude,
                'longitude' => $longitude,
                'name' => $name
            ]);

        $this->parameterBag
            ->expects(static::exactly(3))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $latitude,
                $longitude,
                $name
            );

        $this->createPlaceService
            ->expects(static::once())
            ->method('execute')
            ->willThrowException(new ApplicationException());

        $response = $this->controller->create($this->request, $this->createPlaceService);

        static::assertSame(201, $response->getStatusCode());
    }
}
