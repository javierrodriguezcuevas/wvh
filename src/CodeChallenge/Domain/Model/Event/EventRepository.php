<?php

namespace CodeChallenge\Domain\Model\Event;

use CodeChallenge\Domain\Exception\ModelNotFoundException;
use CodeChallenge\Domain\Model\Event\Vo\EventId;
use CodeChallenge\Domain\Model\Place\Vo\Point;
use CodeChallenge\Domain\Model\Place\Vo\Radius;

/**
 * Class EventRepository
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
interface EventRepository
{
    /**
     * Gets next model id.
     *
     * @return EventId
     */
    public function nextIdentity(): EventId;

    /**
     * Gets a place by id.
     *
     * @param EventId $id The event id.
     *
     * @return Event
     *
     * @throws ModelNotFoundException
     */
    public function oneById(EventId $id): Event;

    /**
     * Gets events where place point is inside radius.
     *
     * @param Point  $point  The center point.
     * @param Radius $radius The circle radius.
     *
     * @return Event[]
     */
    public function allByPlacePointInsideRadius(Point $point, Radius $radius): array;
}
