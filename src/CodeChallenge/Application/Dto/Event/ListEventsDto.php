<?php

namespace CodeChallenge\Application\Dto\Event;

use CodeChallenge\Domain\Model\Event\Event;

/**
 * Class ListEventsDto
 *
 * @author Javier Rodriguez <javierrodriguezcuevas@gmail.com>
 */
class ListEventsDto
{
    /** @var EventDto[] */
    public $events;

    /**
     * ListEventsDto constructor.
     *
     * @param Event[] $events An array of events.
     */
    public function __construct(array $events)
    {
        $this->events = array_map(function (Event $event) {
            return new EventDto($event);
        }, $events);
    }
}
