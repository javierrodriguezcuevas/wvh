<?php

namespace CodeChallenge\Application\Dto\Place;

use CodeChallenge\Domain\Model\Place\Place;

/**
 * Class PlaceDto
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class PlaceDto
{
    /** @var string */
    public $id;

    /** @var float */
    public $latitude;

    /** @var float */
    public $longitude;

    /**
     * PlaceDto constructor.
     *
     * @param Place $place A place model.
     */
    public function __construct(Place $place)
    {
        $this->id = $place->id()->value();
        $this->latitude = $place->point()->latitude();
        $this->longitude = $place->point()->longitude();
    }
}
