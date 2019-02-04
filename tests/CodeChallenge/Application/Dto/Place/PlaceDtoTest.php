<?php

namespace CodeChallenge\Tests\Application\Dto\Place;

use CodeChallenge\Application\Dto\Place\PlaceDto;
use CodeChallenge\Domain\Model\Place\Place;
use CodeChallenge\Domain\Model\Place\Vo\Name;
use CodeChallenge\Domain\Model\Place\Vo\PlaceId;
use CodeChallenge\Domain\Model\Place\Vo\Point;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class PlaceDtoTest
 *
 * @author javierrodriguezcuevas@gmail.com
 */
class PlaceDtoTest extends TestCase
{
    /**
     * Tests it should create dto successfully.
     */
    public function testItShouldCreateSuccessfully()
    {
        $placeId = '35f843b2-2625-11e9-ab14-d663bd873d93';
        $latitudeValue = 41.374991;
        $longitudeValue = 2.149186;

        /** @var Place|MockObject $event */
        $event = $this->createMock(Place::class);

        $event->expects(static::once())
            ->method('id')
            ->willReturn($id = $this->createMock(PlaceId::class));

        $event->expects(static::exactly(2))
            ->method('point')
            ->willReturn($point = $this->createMock(Point::class));

        $id->expects(static::once())
            ->method('value')
            ->willReturn($placeId);

        $point->expects(static::once())
            ->method('latitude')
            ->willReturn($latitudeValue);

        $point->expects(static::once())
            ->method('longitude')
            ->willReturn($longitudeValue);

        $placeDto = new PlaceDto($event);

        static::assertSame($placeDto->id, $placeId);
        static::assertSame($placeDto->latitude, $latitudeValue);
        static::assertSame($placeDto->longitude, $longitudeValue);
    }
}
