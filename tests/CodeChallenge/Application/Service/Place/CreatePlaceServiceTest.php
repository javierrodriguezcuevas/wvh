<?php

namespace CodeChallenge\Tests\Application\Service\Place;

use CodeChallenge\Application\ApplicationException;
use CodeChallenge\Application\Service\Place\CreatePlaceService;
use CodeChallenge\Domain\Model\Place\Place;
use CodeChallenge\Domain\Model\Place\PlaceRepository;
use CodeChallenge\Domain\Model\Place\Vo\PlaceId;
use CodeChallenge\Domain\Model\Place\Vo\Point;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class CreatePlaceServiceTest
 *
 * @covers \CodeChallenge\Application\Service\Place\CreatePlaceService
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class CreatePlaceServiceTest extends TestCase
{
    /** @var PlaceRepository|MockObject */
    private $placeRepository;

    /** @var CreatePlaceService */
    private $createPlaceService;

    /**
     * Sets up tests.
     */
    protected function setUp()
    {
        $this->placeRepository = $this->createMock(PlaceRepository::class);

        $this->createPlaceService = new CreatePlaceService($this->placeRepository);
    }

    /**
     * Tests it should create a place successfully.
     *
     * @throws ApplicationException
     */
    public function testItShouldCreateAPlaceSuccessfully()
    {
        $latitude = 1.0;
        $longitude = 1.0;

        $this->placeRepository
            ->expects(static::once())
            ->method('nextIdentity')
            ->willReturn($placeId = $this->createMock(PlaceId::class));

        $this->placeRepository
            ->expects(static::once())
            ->method('save')
            ->willReturn($place = $this->createMock(Place::class));

        $place->expects(static::exactly(2))
            ->method('point')
            ->willReturn($point = $this->createMock(Point::class));

        $point->expects(static::once())
            ->method('latitude')
            ->willReturn($latitude);

        $point->expects(static::once())
            ->method('longitude')
            ->willReturn($longitude);

        $placeDto = $this->createPlaceService->execute($latitude, $longitude);

        static::assertSame($placeDto->latitude, $longitude);
        static::assertSame($placeDto->longitude, $longitude);
    }

    /**
     * Tests it should throw ApplicationException when invalid latitude.
     *
     * @throws ApplicationException
     */
    public function testItShouldThrowApplicationExceptionWhenInvalidLatitude()
    {
        static::expectException(ApplicationException::class);

        $latitude = 91.0;
        $longitude = 1.0;

        $this->createPlaceService->execute($latitude, $longitude);
    }

    /**
     * Tests it should throw ApplicationException when invalid longitude.
     *
     * @throws ApplicationException
     */
    public function testItShouldThrowApplicationExceptionWhenInvalidLongitude()
    {
        static::expectException(ApplicationException::class);

        $latitude = 1.0;
        $longitude = 181.0;

        $this->createPlaceService->execute($latitude, $longitude);
    }
}
