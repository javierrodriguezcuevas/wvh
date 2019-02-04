<?php

namespace CodeChallenge\Application\Service\Event;

use CodeChallenge\Application\ApplicationException;
use CodeChallenge\Application\Dto\Event\ListEventsDto;
use CodeChallenge\Domain\Exception\InvalidVoException;
use CodeChallenge\Domain\Model\Event\EventRepository;
use CodeChallenge\Domain\Model\Place\Vo\Point;
use CodeChallenge\Domain\Model\Place\Vo\Radius;

/**
 * Class ListEventsService
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class ListEventsService
{
    /** @var EventRepository */
    private $eventRepository;

    /**
     * ListEventsService constructor.
     *
     * @param EventRepository $eventRepository Event repository.
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Creates a new place model.
     *
     * @param float $latitude  The place latitude.
     * @param float $longitude The place longitude.
     * @param int   $radius    The radius to search.
     *
     * @return ListEventsDto
     *
     * @throws ApplicationException
     */
    public function execute(float $latitude, float $longitude, int $radius): ListEventsDto
    {
        try {
            $point = new Point($latitude, $longitude);
            $radius = new Radius($radius);
            $events = $this->eventRepository->allByPlacePointInsideRadius($point, $radius);
        } catch (InvalidVoException $exception) {
            throw new ApplicationException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return new ListEventsDto($events);
    }
}
