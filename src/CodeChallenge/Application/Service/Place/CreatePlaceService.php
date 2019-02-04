<?php

namespace CodeChallenge\Application\Service\Place;

use CodeChallenge\Application\ApplicationException;
use CodeChallenge\Application\Dto\Place\PlaceDto;
use CodeChallenge\Domain\Exception\InvalidVoException;
use CodeChallenge\Domain\Model\Place\Place;
use CodeChallenge\Domain\Model\Place\PlaceRepository;
use CodeChallenge\Domain\Model\Place\Vo\Name;
use CodeChallenge\Domain\Model\Place\Vo\Point;

/**
 * Class CreatePlaceService
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class CreatePlaceService
{
    /** @var PlaceRepository */
    private $placeRepository;

    /**
     * CreatePlaceService constructor.
     *
     * @param PlaceRepository $placeRepository Place repository.
     */
    public function __construct(PlaceRepository $placeRepository)
    {
        $this->placeRepository = $placeRepository;
    }

    /**
     * Creates a new place model.
     *
     * @param float       $latitude  The place latitude.
     * @param float       $longitude The place longitude.
     * @param string|null $name      The place name.
     *
     * @return PlaceDto
     *
     * @throws ApplicationException
     */
    public function execute(float $latitude, float $longitude, ?string $name = null): PlaceDto
    {
        try {
            $point = new Point($latitude, $longitude);
            $name = new Name($name);
            $placeId = $this->placeRepository->nextIdentity();
            $place = new Place($placeId, $point, $name);

            $place = $this->placeRepository->save($place);
        } catch (InvalidVoException $exception) {
            throw new ApplicationException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return new PlaceDto($place);
    }
}
