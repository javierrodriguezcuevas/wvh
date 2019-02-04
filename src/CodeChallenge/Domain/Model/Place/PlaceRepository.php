<?php

namespace CodeChallenge\Domain\Model\Place;

use CodeChallenge\Domain\Exception\ModelNotFoundException;
use CodeChallenge\Domain\Model\Place\Vo\PlaceId;

/**
 * Class PlaceRepository
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
interface PlaceRepository
{
    /**
     * Gets next model id.
     *
     * @return PlaceId
     */
    public function nextIdentity(): PlaceId;

    /**
     * Gets a place by id.
     *
     * @param PlaceId $id The place id.
     *
     * @return Place
     *
     * @throws ModelNotFoundException
     */
    public function oneById(PlaceId $id): Place;

    /**
     * Persists place in repository.
     *
     * @param Place $place The place to persist.
     *
     * @return Place
     */
    public function save(Place $place): Place;
}
