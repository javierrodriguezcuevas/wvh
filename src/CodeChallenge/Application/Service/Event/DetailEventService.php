<?php

namespace CodeChallenge\Application\Service\Event;

use CodeChallenge\Application\ApplicationException;
use CodeChallenge\Application\Dto\Event\EventDetailDto;
use CodeChallenge\Domain\Exception\InvalidVoException;
use CodeChallenge\Domain\Exception\ModelNotFoundException;
use CodeChallenge\Domain\Model\Event\EventRepository;
use CodeChallenge\Domain\Model\Event\Vo\EventId;

/**
 * Class DetailEventService
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class DetailEventService
{
    /** @var EventRepository */
    private $eventRepository;

    /**
     * DetailEventService constructor.
     *
     * @param EventRepository $eventRepository Place repository.
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Gets event detail.
     *
     * @param string $eventId The event id.
     *
     * @return EventDetailDto
     *
     * @throws ApplicationException
     */
    public function execute(string $eventId): EventDetailDto
    {
        try {
            $event = $this->eventRepository->oneById(new EventId($eventId));
        } catch (InvalidVoException | ModelNotFoundException $exception) {
            throw new ApplicationException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return new EventDetailDto($event);
    }
}
