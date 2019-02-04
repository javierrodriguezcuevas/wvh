<?php

namespace CodeChallenge\Application\Service\Event;

use CodeChallenge\Application\ApplicationException;
use CodeChallenge\Application\Dto\Event\EventDto;
use CodeChallenge\Domain\Exception\InvalidVoException;
use CodeChallenge\Domain\Exception\ModelNotFoundException;
use CodeChallenge\Domain\Model\Event\EventRepository;
use CodeChallenge\Domain\Model\Event\Vo\Name;
use CodeChallenge\Domain\Model\Place\PlaceRepository;
use CodeChallenge\Domain\Model\Place\Vo\PlaceId;

/**
 * Class CreateEventService
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class CreateEventService
{
    /** @var PlaceRepository */
    private $placeRepository;

    /** @var EventRepository */
    private $eventRepository;

    /**
     * CreatePlaceService constructor.
     *
     * @param PlaceRepository $placeRepository Place repository.
     * @param EventRepository $eventRepository Event repository.
     */
    public function __construct(PlaceRepository $placeRepository, EventRepository $eventRepository)
    {
        $this->placeRepository = $placeRepository;
        $this->eventRepository = $eventRepository;
    }

    /**
     * Creates a new event model.
     *
     * @param string      $placeId The place id.
     * @param string|null $name    The place name.
     *
     * @return EventDto
     *
     * @throws ApplicationException
     */
    public function execute(string $placeId, ?string $name = null): EventDto
    {
        try {
            $name = new Name($name);
            $place = $this->placeRepository->oneById(new PlaceId($placeId));
            $eventId = $this->eventRepository->nextIdentity();
            $event = $place->addEvent($eventId, $name);

            $this->placeRepository->save($place);
        } catch (InvalidVoException | ModelNotFoundException $exception) {
            throw new ApplicationException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return new EventDto($event);
    }
}
